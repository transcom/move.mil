<?php

namespace Drupal\locations\Service;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\node\Entity\Node;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class Writer.
 *
 * Parse a given array and saves it in Drupal Locations.
 */
class Writer {

  /**
   * Updates and creates Location nodes.
   *
   * @throws \Exception
   */
  public static function update($nodeData, &$context) {
    $cnslTypeId = self::getDrupalTaxonomyTermId('Transportation Office');
    $ppsoTypeId = self::getDrupalTaxonomyTermId('Shipping Office');
    $googleApi = $_ENV['GOOGLE_MAPS_API_KEY'];
    // Update each XML offices that is found in Drupal content.
    $node = Writer::getDrupalLocationByCnslId($nodeData['id'], $cnslTypeId, $ppsoTypeId);
    if (!empty($node)) {
      Writer::updateDrupalLocation($node, $nodeData);
      $context['results']['update'][] = $nodeData['id'];
    }
    else {
      $node = Writer::createDrupalLocation($nodeData, $cnslTypeId, $ppsoTypeId);
      $context['results']['create'][] = $nodeData['id'];
    }
    // Update or create location phone paragraphs.
    if (!empty($nodeData['phones'])) {
      Writer::updateLocationPhones($node, $nodeData['phones']);
    }
    // Update or add geolocation.
    $error = Writer::updateGeolocation($node, $googleApi);
    if (!empty($error)) {
      \Drupal::messenger()->addWarning($error);
    }
  }

  /**
   * Delete Location nodes that don't exist in the XML file.
   *
   * @throws \Exception
   */
  public static function deleteLocations($batchSize, $xmlOffices, &$context) {
    // Retrieve location types only once per iteration.
    $cnslTypeId = self::getDrupalTaxonomyTermId('Transportation Office');
    $ppsoTypeId = self::getDrupalTaxonomyTermId('Shipping Office');
    // Load all Drupal locations nodes.
    $allLocations = \Drupal::entityTypeManager()
      ->getStorage('node')
      ->loadByProperties([
        'type' => 'location',
      ]);
    // Remove Weight Scales, they're not part of the XML.
    $locations = array_filter($allLocations, function ($location) use ($cnslTypeId, $ppsoTypeId) {
      $locType = $location->get('field_location_type')->getValue()[0]['target_id'];
      return $locType == $ppsoTypeId || $locType == $cnslTypeId;
    });
    // Initialize batch context sandbox.
    if (empty($context['sandbox'])) {
      $context['sandbox']['progress'] = 0;
      $context['sandbox']['count'] = count($locations);
    }
    // Start where we left off last time.
    $nextNodes = array_slice($locations, $context['sandbox']['progress'], $batchSize, TRUE);
    // Delete each Drupal node that is not found in XML offices.
    $toDelete = [];
    foreach ($nextNodes as $node) {
      $cnslId = $node->get('field_location_cnsl_id')->getValue()[0]['value'];
      if (!empty($cnslId) && empty($xmlOffices[$cnslId])) {
        // Not found in XML file.
        $toDelete[] = $node;
        $title = $node->getTitle();
        $message = $cnslId . ' - ' . $title . ' deleted';
        $context['results'][] = $message;
        $context['message'] = $message;
        \Drupal::messenger()->addMessage($message);
      }
      // Update our progress!
      $context['sandbox']['progress']++;
    }
    \Drupal::entityTypeManager()
      ->getStorage('node')
      ->delete($toDelete);
    if ($context['sandbox']['progress'] != $context['sandbox']['count']) {
      $context['finished'] = $context['sandbox']['progress'] / $context['sandbox']['count'];
    }
  }

  /**
   * Get Drupal location by its CNSL id.
   *
   * @param string $id
   *   The CNSL id to look in Drupal.
   * @param int $cnslTypeId
   *   CNSL term id.
   * @param int $ppsoTypeId
   *   PPSO term id.
   *
   * @return \Drupal\node\Entity\Node
   *   The node found with CNSL id or NULL.
   *
   * @throws \Exception
   */
  public static function getDrupalLocationByCnslId($id, $cnslTypeId, $ppsoTypeId) {
    // Get all location entity.
    try {
      $locations = \Drupal::entityTypeManager()
        ->getStorage('node')
        ->loadByProperties([
          'type' => 'location',
          'field_location_cnsl_id' => $id,
        ]);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      throw new \Exception('Exception on location node, ' . $ipde->getMessage());
    }
    // Remove Weight Scales, they shouldn't have CNSL id.
    $locations = array_filter($locations, function ($location) use ($cnslTypeId, $ppsoTypeId) {
      $locType = $location->get('field_location_type')->getValue()[0]['target_id'];
      return $locType == $ppsoTypeId || $locType == $cnslTypeId;
    });
    return current($locations);
  }

  /**
   * Get Drupal taxonomy term id.
   *
   * @param string $office_type
   *   The office type which is the term name.
   *
   * @return int
   *   The taxonomy term id.
   *
   * @throws \Exception
   */
  private static function getDrupalTaxonomyTermId($office_type) {
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => trim($office_type)]);
    $term = current($terms);
    return $term->id();
  }

  /**
   * Update existing Drupal location.
   *
   * @param \Drupal\node\Entity\Node $location
   *   The Drupal location to update.
   * @param array $nodeData
   *   The source data from XML office.
   *
   * @throws \Exception
   */
  private static function updateDrupalLocation(Node $location, array $nodeData) {
    $location->set('title', $nodeData['name']);
    $location->set(
      'field_location_address',
      empty($nodeData['address']) ? NULL : $nodeData['address']
    );
    $location->set(
      'field_location_email_address',
      empty($nodeData['emails']) ? NULL : $nodeData['emails']
    );
    $location->save();
  }

  /**
   * Update existing Drupal location.
   *
   * @param array $nodeData
   *   The source data from XML office.
   * @param int $cnslTypeId
   *   Term id for Transportation Office or CNSL.
   * @param int $ppsoTypeId
   *   Term id for Shipping Office or PPSO.
   *
   * @return \Drupal\node\Entity\Node
   *   The Drupal location created.
   *
   * @throws \Exception
   */
  private static function createDrupalLocation(array $nodeData, $cnslTypeId, $ppsoTypeId) {
    // Get Shipping office reference for Transportation Offices.
    $ref = NULL;
    if (!$nodeData['isPPSO']) {
      $ppso = Writer::getDrupalLocationByCnslId($nodeData['ppsoId'], $cnslTypeId, $ppsoTypeId);
      if (!empty($ppso)) {
        $ref = [
          'target_id' => $ppso->id(),
          'target_type' => 'node',
        ];
      }
    }
    $node = Node::create([
      'title'                  => $nodeData['name'],
      'field_location_cnsl_id' => $nodeData['id'],
      'type'                   => 'location',
      'field_location_type'    => [
        'target_id' => $nodeData['isPPSO'] ? $ppsoTypeId : $cnslTypeId,
        'target_type' => "taxonomy_term",
      ],
      'field_location_address' => $nodeData['address'],
      'field_location_email_address'   => empty($nodeData['emails']) ? NULL : $nodeData['emails'],
      'field_location_reference'  => $ref,
    ]);
    $node->save();
    return $node;
  }

  /**
   * Update Drupal Location with the XML phone content.
   *
   * @param \Drupal\node\Entity\Node $node
   *   Drupal Location entity.
   * @param array $xml_phone_numbers
   *   The source data from XML office.
   *
   * @throws \Exception
   */
  private static function updateLocationPhones(Node $node, array $xml_phone_numbers) {
    // Get this location phones references.
    $phone_references = $node
      ->get('field_location_telephone')
      ->getValue();
    $updatesCount = count($phone_references);
    // Update existing location telephone paragraphs.
    if ($updatesCount > 0) {
      $updates = array_slice($xml_phone_numbers, 0, $updatesCount);
      foreach ($updates as $key => $update) {
        $ref = $phone_references[$key];
        $id = $ref['target_id'];
        $paragraph = \Drupal::entityTypeManager()
          ->getStorage('paragraph')
          ->load($id);
        $paragraph->set('field_dsn', $update['dns']);
        $paragraph->set('field_phonenumber', $update['number']);
        $paragraph->set('field_voice', $update['voice']);
        $paragraph->set('field_type', $update['type']);
        $paragraph->save();
      }
      $newones = array_slice($xml_phone_numbers, $updatesCount);
    }
    else {
      $newones = $xml_phone_numbers;
    }
    // Create the new location telephone paragraphs.
    foreach ($newones as $new) {
      $paragraph = \Drupal::entityTypeManager()
        ->getStorage('paragraph')
        ->create([
          'type' => 'location_telephone',
          'field_dsn' => $new['dns'],
          'field_phonenumber' => $new['number'],
          'field_voice' => $new['voice'],
          'field_type' => $new['type'],
        ]);
      $paragraph->save();
      $phone_references[] = [
        'target_id' => $paragraph->id(),
        'target_revision_id' => $paragraph->getRevisionId(),
      ];
    }
    $node->set('field_location_telephone', $phone_references);
    $node->save();
  }

  /**
   * Update Drupal Location with the XML phone content.
   *
   * @param \Drupal\node\Entity\Node $node
   *   Drupal Location entity.
   * @param string $googleApi
   *   Environment Google API key.
   *
   * @return string|null
   *   Return the error or null if the call was successful.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private static function updateGeolocation(Node $node, $googleApi) {
    $title = $node->getTitle();
    $request = "https://maps.google.com/maps/api/geocode/json?address={$title}&key=$googleApi";
    $client = new Client();
    try {
      $res = $client->request('GET', $request);
    }
    catch (GuzzleException $ge) {
      $error = 'There was a network problem while searching the geolocation of ' . $title;
      return $error;
    }
    $data = json_decode($res->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
    if ($data['status'] == 'REQUEST_DENIED') {
      $error = $data['error_message'];
      return $error;
    }
    if ($data['status'] == 'ZERO_RESULTS' || empty($data['results'])) {
      $error = 'There are zero results while searching the geolocation of ' . $title;
      return $error;
    }
    $location = $data['results'][0]['geometry']['location'];
    $node->set('field_geolocation', [
      'lat' => $location['lat'],
      'lng' => $location['lng'],
    ]);
    $node->save();
    return NULL;
  }

  /**
   * Finalize updating location batch.
   */
  public static function finishedUpdateCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $updates = empty($results['update']) ? 0 : count($results['update']);
      $creates = empty($results['create']) ? 0 : count($results['create']);
      if ($updates) {
        $message = \Drupal::translation()
          ->formatPlural($updates, 'One location updated.', '@count locations updated.');
        \Drupal::messenger()->addMessage($message);
      }
      if ($creates) {
        $message = \Drupal::translation()
          ->formatPlural($creates, 'One location created.', '@count locations created.');
        \Drupal::messenger()->addMessage($message);
      }
    }
    else {
      \Drupal::messenger()->addError('Finished with an error.');
    }
  }

  /**
   * Finalize deleting locations batch.
   */
  public static function finishedDeleteCallback($success, $results, $operations) {
    // The 'success' parameter means no fatal PHP errors were detected. All
    // other error management should be handled using 'results'.
    if ($success) {
      $message = \Drupal::translation()
        ->formatPlural(count($results), 'One location deleted.', '@count locations deleted.');
      \Drupal::messenger()->addMessage($message);
    }
    else {
      \Drupal::messenger()->addError('Finished with an error.');
    }
  }

}
