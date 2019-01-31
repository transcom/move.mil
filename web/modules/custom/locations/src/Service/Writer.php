<?php

namespace Drupal\locations\Service;

use Drupal\Component\Plugin\Exception\PluginNotFoundException;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactory;
use Drupal\Core\Database\Connection;
use Drupal\node\Entity\Node;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Symfony\Component\DependencyInjection\ContainerInterface;
use SimpleXMLElement;

/**
 * Class Writer.
 *
 * Parse a given array and saves it in Drupal Locations.
 */
class Writer {

  protected $entityTypeManager;
  protected $paragraphStorage;
  protected $loggerFactory;
  protected $db;
  protected $cnslTypeId;
  protected $ppsoTypeId;

  /**
   * Writer constructor.
   *
   * Needed for the EntityTypeManager dependency injection.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   The entity type manager interface.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $loggerFactory
   *   The logger channel factory.
   * @param \Drupal\Core\Database\Connection $db
   *   The db connection.
   *
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager, LoggerChannelFactory $loggerFactory, Connection $db) {
    $this->entityTypeManager = $entityTypeManager;
    try {
      $this->paragraphStorage = $this->entityTypeManager->getStorage('paragraph');
    }
    catch (InvalidPluginDefinitionException $ipde) {
      throw new \Exception('Exception on location_telephone paragraph,  ' . $ipde->getMessage());
    }
    $this->db = $db;
    $this->loggerFactory = $loggerFactory;
    $this->cnslTypeId = $this->getDrupalTaxonomyTermId('Transportation Office');
    $this->ppsoTypeId = $this->getDrupalTaxonomyTermId('Shipping Office');
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('logger.factory'),
      $container->get('database')
    );
  }

  /**
   * Normalizes data then creates Location nodes.
   *
   * @param \SimpleXMLElement $xml_offices
   *   XML location offices.
   *
   * @throws \Exception
   */
  public function writeFrom(SimpleXMLElement $xml_offices) {
    // Report locations parsed.
    $locationsUpdated = [];
    $locationsCreated = [];
    // PPSOs (Shipping offices) handled.
    $ppsos = [];
    // Get all telephone paragraphs.
    $phone_paragraphs = $this->paragraphStorage
      ->loadByProperties(['type' => 'location_telephone']);
    $phones = [];
    foreach ($phone_paragraphs as $phone) {
      $phones[$phone->id()] = $phone;
    }
    // Update each XML offices that is found in Drupal content.
    foreach ($xml_offices as $xml_office) {
      $nodeData = $this->getNodeData($xml_office, FALSE);
      // Verify that this office's ppso has been handled.
      // Get PPSO Drupal entity.
      $ppso = $this->getDrupalLocationByCnslId($nodeData['ppsoId']);
      if (!in_array($nodeData['ppsoId'], $ppsos)) {
        // Not handled, let's update or create it.
        $ppsoData = $this->getNodeData($xml_office, TRUE);
        if (!empty($ppso)) {
          $this->updateDrupalLocation($ppso, $ppsoData);
          $locationsUpdated[] = $ppso->toUrl()->setAbsolute()->toString();
        }
        else {
          $location = $this->createDrupalLocation($ppsoData, TRUE);
          $locationsCreated[] = $location->toUrl()->setAbsolute()->toString();
        }
        // Update or create location phone paragraphs.
        // $this->updateLocationPhones($location, $xml_office, $phones);
        // Add PPSO to handled ppsos array.
        $ppsos[] = $nodeData['ppsoId'];
      }
      $location = $this->getDrupalLocationByCnslId($nodeData['id']);
      if (!empty($location)) {
        $this->updateDrupalLocation($location, $nodeData);
        $locationsUpdated[] = $location->toUrl()->setAbsolute()->toString();
      }
      else {
        $location = $this->createDrupalLocation($nodeData, FALSE);
        $locationsCreated[] = $location->toUrl()->setAbsolute()->toString();
      }
      // Update or create location phone paragraphs.
      $this->updateLocationPhones($location, $xml_office, $phones);
    }
    if (count($locationsUpdated) > 1) {
      $this->loggerFactory
        ->get('locations')
        ->notice(count($locationsUpdated) . ' locations parsed and updated: ' . implode(', ', $locationsUpdated));
    }
    if (count($locationsCreated)) {
      $this->loggerFactory
        ->get('locations')
        ->notice(count($locationsCreated) . ' new locations parsed and created: ' . implode(', ', $locationsCreated));
    }
  }

  /**
   * Delete Location nodes that don't exist in the XML file.
   */
  public function deleteFrom(SimpleXMLElement $xml) {
    try {
      $storageHandler = $this->entityTypeManager->getStorage('node');
    }
    catch (InvalidPluginDefinitionException $e) {
    }
    catch (PluginNotFoundException $e) {
    }
    $nodeEntities = $storageHandler->loadMultiple($db_objs);
    $storageHandler->delete($nodeEntities);
  }

  /**
   * Get Drupal location by its CNSL id.
   *
   * @param string $id
   *   The CNSL id to look in Drupal.
   *
   * @return \Drupal\node\Entity\Node
   *   The node found with CNSL id or NULL.
   *
   * @throws \Exception
   */
  public function getDrupalLocationByCnslId($id) {
    // Get all location entity.
    try {
      $locations = $this->entityTypeManager
        ->getStorage('node')
        ->loadByProperties([
          'type' => 'location',
          'field_location_cnsl_id' => $id,
        ]);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      throw new \Exception('Exception on location node, ' . $ipde->getMessage());
    }
    return current($locations);
  }

  /**
   * Get Drupal taxonomy term id.
   *
   * @param string $office_type
   *   The CNSL id to look in Drupal.
   *
   * @return int
   *   The node found with CNSL id or NULL.
   */
  public function getDrupalTaxonomyTermId($office_type) {
    return $this->db
      ->select('taxonomy_term_field_data', 't')
      ->fields('t', ['tid'])
      ->condition('name', $office_type, '=')
      ->execute()
      ->fetchField();
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
  private function updateDrupalLocation(Node $location, array $nodeData) {
    $location->set('title', $nodeData['name']);
    $location->set('field_location_address', $nodeData['address']);
    $location->set('field_location_email', $nodeData['emails']);
    $location->save();
  }

  /**
   * Update existing Drupal location.
   *
   * @param array $nodeData
   *   The source data from XML office.
   * @param bool $isPPSO
   *   If it is a PPSO (Shipping Office).
   *
   * @return \Drupal\node\Entity\Node
   *   The Drupal location created.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function createDrupalLocation(array $nodeData, $isPPSO) {
    $location = Node::create([
      'title'                  => $nodeData['name'],
      'field_location_cnsl_id' => $nodeData['id'],
      'type'                   => 'location',
      'field_location_type'    => [
        'target_id' => $isPPSO ? $this->ppsoTypeId : $this->cnslTypeId,
        'target_type' => "taxonomy_term",
      ],
    ]);
    $location->save();
    return $location;
  }

  /**
   * Get data needed to fill in a location node.
   *
   * @param \SimpleXMLElement $xml_office
   *   The source data from XML office.
   * @param bool $isPPSO
   *   If it is a PPSO (Shipping Office).
   *
   * @return array
   *   The node data.
   */
  private function getNodeData(SimpleXMLElement $xml_office, $isPPSO) {
    $officeInfo = $xml_office->LIST_G_CNSL_INFO->G_CNSL_INFO;
    $suffix = $isPPSO ? 'PPSO_' : 'CNSL_';
    $node = [];
    // Get XML file id element.
    $node['id'] = (string) $isPPSO ? $xml_office->PPSO_ORG_ID : $xml_office->CNSL_ORG_ID1;
    // Get XML file name element.
    $node['name'] = $officeInfo->{$suffix . 'NAME'};
    // Get XML file address element.
    $node['address'] = [
      'country_code' => $officeInfo->{$suffix . 'COUNTRY'},
      'address_line1' => $officeInfo->{$suffix . 'ADDR1'},
      'address_line2' => $officeInfo->{$suffix . 'ADDR2'},
      'locality' => $officeInfo->{$suffix . 'CITY'},
      'administrative_area' => $officeInfo->{$suffix . 'STATE'},
      'postal_code' => $officeInfo->{$suffix . 'ZIP'},
    ];
    // Get XML file email elements.
    $xmlEmails = $isPPSO ? $officeInfo->xpath('//G_ppso_email') : $xml_office->xpath('//G_CNSL_EMAIL');
    $node['emails'] = array_map(
      function ($email) use ($isPPSO) {
        if ($isPPSO) {
          if ($email->EMAIL_TYPEP == 'Customer Service') {
            return $email->EMAIL_ADDRESSP;
          }
        }
        return $email->EMAIL_ADDRESS;
      },
      $xmlEmails
    );
    if (!$isPPSO) {
      $node['ppsoId'] = $officeInfo->PPSO_ORG_ID;
    }
    return $node;
  }

  /**
   * Update Drupal Location with the XML phone content.
   *
   * @param \Drupal\node\Entity\Node $location
   *   Drupal Location entity.
   * @param \SimpleXMLElement $element
   *   XML file element.
   * @param array $phones
   *   All phone paragraphs.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  private function updateLocationPhones(Node $location, SimpleXMLElement $element, array $phones) {
    // Get XML file phone elements.
    $xmlPhones = $element
      ->LIST_G_CNSL_PHONE_ORG_ID
      ->G_CNSL_PHONE_ORG_ID
      ->LIST_G_CNSL_PHONE_NOTES
      ->G_CNSL_PHONE_NOTES;
    $xml_phone_numbers = [];
    // Parse XML file phone elements.
    foreach ($xmlPhones as $phone) {
      $dns = (string) $phone->CNSL_COMM_OR_DSN == 'D';
      $number = $dns ? (string) $phone->CNSL_DSN_NUM : (string) $phone->CNSL_PHONE_NUM;
      $type = (string) $phone->CNSL_VOICE_OR_FAX == 'V' ? 'voice' : 'fax';
      $xml_phone_numbers[] = [
        'dns' => $dns,
        'number' => $number,
        'type' => $type,
      ];
    }
    // Get this location phones references.
    $phone_references = $location
      ->get('field_location_telephone')
      ->getValue();
    $updatesCount = count($phone_references);
    // Update existing location telephone paragraphs.
    if ($updatesCount > 0) {
      $updates = array_slice($xml_phone_numbers, 0, $updatesCount);
      foreach ($updates as $key => $update) {
        $ref = $phone_references[$key];
        $id = $ref['target_id'];
        $paragraph = $phones[$id];
        $paragraph->set('field_dsn', $update['dns']);
        $paragraph->set('field_phonenumber', $update['number']);
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
      $paragraph = $this->paragraphStorage->create([
        'type' => 'location_telephone',
        'field_dsn' => $new['dns'],
        'field_phonenumber' => $new['number'],
        'field_type' => $new['type'],
      ]);
      $paragraph->save();
      $phone_references[] = [
        'target_id' => $paragraph->id(),
      ];
    }
    $location->set('field_location_telephone', $phone_references);
    $location->save();
  }

}
