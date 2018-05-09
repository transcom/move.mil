<?php

namespace Drupal\parser\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection as Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;

/**
 * Class LocationsController.
 */
class LocationsController extends ControllerBase {

  private $databaseConnection;
  protected $entityTypeManager;

  /**
   * Constructs a LocationsController.
   *
   * @param \Drupal\Core\Database\Connection $databaseConnection
   *   A Database Connection object.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager interface.
   */
  public function __construct(Connection $databaseConnection, EntityTypeManagerInterface $entity_type_manager) {
    $this->databaseConnection = $databaseConnection;
    $this->entityTypeManager = $entity_type_manager;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database'),
      $container->get('entity_type.manager')
    );
  }

  /**
   * Get all search results.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return locations as a Json object
   */
  public function locations() {
    try {
      $taxonomy_terms = $this->entityTypeManager
        ->getStorage('taxonomy_term')
        ->loadByProperties(['vid' => 'location_type']);
      $entities = $this->entityTypeManager->getStorage('node')->loadByProperties(['type' => 'location']);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      $msg = "Error while creating response => {$ipde->getMessage()}";
      return JsonResponse::create($msg, 500);
    }
    $taxonomies = [];
    foreach ($taxonomy_terms as $term) {
      $taxonomies[$term->id()] = $term->label();
    }
    $data = [];
    foreach ($entities as $entity) {
      $location = $entity->toArray();
      $type = $this->locationType($location, $taxonomies);
      if (!$this->searchable($type)) {
        continue;
      }
      $data[$entity->label()] = $this->parse($location, $type);
      $shipping = $this->shippingOffice($location);
      $data[$entity->label()]['shipping_office'] = $this->parse($shipping, 'Shipping Office');
    }
    return $this->response($data);
  }

  /**
   * Get location type (taxonomy term).
   *
   * @param array $location
   *   Array of the location entity.
   * @param array $taxonomies
   *   Array of taxonomy terms found of type 'location_type'.
   *
   * @return string
   *   Term value.
   */
  private function locationType(array $location, array $taxonomies) {
    $type_id = $location['field_location_type'][0]['target_id'];
    return $taxonomies[$type_id];
  }

  /**
   * Evaluate if should be include in the closest locations search.
   *
   * @param string $type
   *   Location type.
   *
   * @return bool
   *   Whether a entity should be included.
   */
  private function searchable($type) {
    return $type == 'Transportation Office' ||
      $type == 'Weight Scales';
  }

  /**
   * Get values from Drupal fields array.
   *
   * @param array $fields
   *   Array of fields to strip the value from.
   *
   * @return array
   *   Array of fields values.
   */
  private function values(array $fields) {
    $values = [];
    foreach ($fields as $field) {
      $values[] = $field['value'];
    }
    return $values;
  }

  /**
   * Get uri from Drupal links array.
   *
   * @param array $links
   *   Array of Drupal links to strip the uri from.
   *
   * @return array
   *   Array of fields uris.
   */
  private function uris(array $links) {
    $uris = [];
    foreach ($links as $link) {
      $uris[] = $link['uri'];
    }
    return $uris;
  }

  /**
   * Parse a drupal entity data to more human readable data.
   *
   * @param array $location
   *   Drupal entity array with a lot of nested arrays.
   * @param string $type
   *   Location type.
   *
   * @return array
   *   Flattened and filtered array.
   */
  private function parse(array $location = NULL, $type) {
    if ($location == NULL || !count($location)) {
      return NULL;
    }
    $data = [];
    $data['type'] = $type;
    $data['title'] = $location['title'][0]['value'];
    $data['location'] = $location['field_location_address'][0];
    $data['location']['lat'] = $location['field_geolocation'][0]['lat'];
    $data['location']['lon'] = $location['field_geolocation'][0]['lng'];
    $data['email_addresses'] = $this->values($location['field_location_email']);
    $data['hours'] = $location['field_location_hours'][0]['value'];
    $data['websites'] = $this->uris($location['field_location_link']);
    $data['notes'] = $location['field_location_note'][0]['value'];
    $data['services'] = $this->values($location['field_location_services']);
    $data['phones'] = $this->values($location['field_location_telephone']);
    return $data;
  }

  /**
   * Evaluate if a transportation office has a shipping office.
   *
   * @param array $location
   *   Drupal entity array with a lot of nested arrays.
   *
   * @return bool
   *   Whether location has a shipping office.
   */
  private function hasShippingOffice(array $location) {
    return array_key_exists('field_location_reference', $location) &&
      count($location['field_location_reference']);
  }

  /**
   * Look for a location entity, of type shipping office, by id.
   *
   * @param array $location
   *   Drupal entity array with a lot of nested arrays.
   *
   * @return mixed[]|\Symfony\Component\HttpFoundation\JsonResponse
   *   Drupal entity array with a lot of nested arrays.
   */
  private function shippingOffice(array $location) {
    if (!$this->hasShippingOffice($location)) {
      // Not all PPPOs or scales have a shipping office, or data is missing.
      return NULL;
    }
    $id = $location['field_location_reference'][0]['target_id'];
    try {
      $entity = $this->entityTypeManager->getStorage('node')->load($id);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      return NULL;
    }
    return $entity->toArray();
  }

  /**
   * Create JSON response.
   *
   * @param array $data
   *   Data to encode.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   The JSON response.
   */
  private function response(array $data) {
    $response = JsonResponse::create($data, 200);
    $response->setEncodingOptions(
      $response->getEncodingOptions() |
      JSON_PRETTY_PRINT
    );
    if (gettype($response) == 'object') {
      return $response;
    }
    return JsonResponse::create('Error while creating response.', 500);
  }

}
