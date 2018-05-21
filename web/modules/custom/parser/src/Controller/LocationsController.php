<?php

namespace Drupal\parser\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection as Connection;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class LocationsController.
 */
class LocationsController extends ControllerBase {

  private $databaseConnection;
  private $googleApi;
  protected $entityTypeManager;
  protected $errors;

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
    $this->googleApi = $_SERVER['GOOGLE_MAPS_API_KEY'];
    $this->errors = [];
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
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The http request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return locations as a Json object
   */
  public function locations(Request $request) {
    $params = NULL;
    $content = $request->getContent();
    if (!empty($content)) {
      $params = json_decode($content, TRUE);
    }
    if (!$params || !$this->validate($params)) {
      return $this->response();
    }
    $geolocation = $this->geoLocation($params);
    if (empty($this->errors)) {
      $data['geolocation'] = $geolocation;
      $locations = $this->loadLocations($geolocation);
      $data['offices'] = $this->orderedLocations($locations);
      return $this->response($data);
    }
    return $this->response();
  }

  /**
   * Evaluate if the request has search params.
   *
   * @param array $params
   *   Params sent within the http request.
   *
   * @return bool
   *   Whether the params are valid.
   */
  private function validate(array $params) {
    $lat_regex = "/^[-+]?([1-8]?\d(\.\d+)?|90(\.0+)?)$/";
    $lon_regex = "/^[-+]?(180(\.0+)?|((1[0-7]\d)|([1-9]?\d))(\.\d+)?)$/";
    $loc = $params['latitude'] && $params['longitude'];
    $valid = $loc && preg_match($lat_regex, $params['latitude']);
    $valid = $valid && $loc && preg_match($lon_regex, $params['longitude']);
    if ($loc && !$valid) {
      $this->errors[] = 'Invalid parameters format: latitude/longitude.';
      return $valid;
    }
    $valid = $valid || $params['query'];
    if (!$valid) {
      $this->errors[] = "Missing parameters: 'query', OR 'latitude and longitude'.";
    }
    return $valid;
  }

  /**
   * Get geo location according to the params given.
   *
   * @param array $params
   *   Params sent within the http request.
   *
   * @return array
   *   Array with the geolocation of the search.
   */
  private function geoLocation(array $params) {
    if ($params['latitude'] && $params['longitude']) {
      return [
        'lat' => $params['latitude'],
        'lon' => $params['longitude'],
      ];
    }
    elseif ($params['query'] && preg_match("/^\d{5}$/", $params['query'])) {
      $uszipcode = $this->uszipcode($params['query']);
      if ($uszipcode) {
        return [
          'lat' => floatval($uszipcode['lat']),
          'lon' => floatval($uszipcode['lon']),
        ];
      }
    }
    $key = $this->googleApi;
    $request = "https://maps.google.com/maps/api/geocode/json?address={$params['query']}&key=$key";
    $client = new Client();
    try {
      $res = $client->request('GET', $request);
    }
    catch (GuzzleException $ge) {
      $this->errors[] = 'There was an network problem. Mind trying again?';
      return NULL;
    }
    $data = json_decode($res->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
    if ($data['status'] == 'ZERO_RESULTS' || empty($data['results'])) {
      $this->errors[] = 'There was a problem performing that search. Mind trying again with another location?';
      return NULL;
    }
    $location = $data['results'][0]['geometry']['location'];
    $address = $data['results'][0]['formatted_address'];
    return [
      'lat' => $location['lat'],
      'lon' => $location['lng'],
      'result' => $address,
    ];
  }

  /**
   * Get uszipcode object according to the given zip code.
   */
  private function uszipcode($zipcode) {
    $uszipcode = $this->databaseConnection
      ->select('parser_zipcodes')
      ->fields('parser_zipcodes')
      ->condition('code', $zipcode)
      ->execute()
      ->fetch();
    return (array) $uszipcode;
  }

  /**
   * Load and parse all location entities.
   */
  private function loadLocations($origin) {
    try {
      $taxonomy_terms = $this->entityTypeManager
        ->getStorage('taxonomy_term')
        ->loadByProperties(['vid' => 'location_type']);
      $entities = $this->entityTypeManager->getStorage('node')->loadByProperties(['type' => 'location']);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      $this->errors[] = "Error while creating response: {$ipde->getMessage()}.";
      return NULL;
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
      $data[$entity->label()]['distance'] = $this->distance($origin, $data[$entity->label()]['location']);
    }
    return $data;
  }

  /**
   * Return closest locations first.
   */
  private function orderedLocations($locations) {
    $locs = $locations;
    uasort($locs, function ($a, $b) {
      if ($a['distance'] == $b['distance']) {
        return 0;
      }
      return ($a['distance'] < $b['distance']) ? -1 : 1;
    });
    return $locs;
  }

  /**
   * Return miles from the origin to the given location.
   *
   * @param array $origin
   *   Geolocation of the origin or search.
   * @param array $location
   *   Geolocation of the trasnportation office or weight scale.
   *
   * @return float
   *   The distance in miles.
   */
  private function distance(array $origin, array $location) {
    // Convert from degrees to radians.
    $lat1 = deg2rad($origin['lat']);
    $lon1 = deg2rad($origin['lon']);
    $lat2 = deg2rad($location['lat']);
    $lon2 = deg2rad($location['lon']);
    $theta = deg2rad($lon1 - $lon2);
    // Get distance.
    $dist = rad2deg(
      acos(
        sin($lat1) * sin($lat2) + cos($lat1) * cos($lat2) * cos($theta)
      )
    );
    $miles = $dist * 60.0 * 1.1515;
    return $miles;
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
      $type == 'Weight Scale';
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
  private function response(array $data = []) {
    if (empty($this->errors)) {
      $response = JsonResponse::create($data, 200);
    }
    else {
      $response = JsonResponse::create($this->errors, 500);
    }
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
