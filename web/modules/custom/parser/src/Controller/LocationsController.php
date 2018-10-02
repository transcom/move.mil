<?php

namespace Drupal\parser\Controller;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\node\Entity\Node;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\parser\Service\DbReader;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Logger\LoggerChannelFactory;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;

/**
 * Class LocationsController.
 */
class LocationsController extends ControllerBase {

  private $dbReader;
  private $googleApi;
  protected $entityTypeManager;
  private $nodeStorage;
  protected $errors;
  protected $logger;

  /**
   * Constructs a LocationsController.
   *
   * @param \Drupal\parser\Service\DbReader $dbReader
   *   A Database Connection object.
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager interface.
   * @param \Drupal\Core\Logger\LoggerChannelFactory $logger
   *   Drupal logger.
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public function __construct(DbReader $dbReader, EntityTypeManagerInterface $entity_type_manager, LoggerChannelFactory $logger) {
    $this->dbReader = $dbReader;
    $this->entityTypeManager = $entity_type_manager;
    $this->logger = $logger;
    $this->nodeStorage = $this->entityTypeManager->getStorage('node');
    $this->googleApi = $_ENV['GOOGLE_MAPS_API_KEY'];
    $this->errors = [];
  }

  /**
   * {@inheritdoc}
   *
   * @throws \Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException
   * @throws \Drupal\Component\Plugin\Exception\PluginNotFoundException
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('parser.reader'),
      $container->get('entity_type.manager'),
      $container->get('logger.factory')
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
      $data['offices'] = $this->loadLocations($geolocation);
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
    $loc = array_key_exists('latitude', $params) && array_key_exists('longitude', $params);
    $valid = $loc && preg_match($lat_regex, $params['latitude']);
    $valid = $valid && $loc && preg_match($lon_regex, $params['longitude']);
    if ($loc && !$valid) {
      $this->errors[] = 'Invalid parameters format: latitude/longitude.';
      return $valid;
    }
    $valid = $valid || array_key_exists('query', $params);
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
    if (array_key_exists('latitude', $params) && array_key_exists('longitude', $params)) {
      return [
        'lat' => $params['latitude'],
        'lon' => $params['longitude'],
      ];
    }
    elseif (array_key_exists('query', $params) && preg_match("/^\d{5}$/", $params['query'])) {
      $uszipcode = $this->dbReader->uszipcode($params['query']);
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
      $this->logger->get('geocode')->error($ge->getMessage());
      $this->errors[] = 'There was a network problem. Mind trying again?';
      return NULL;
    }
    $data = json_decode($res->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
    if ($data['status'] == 'REQUEST_DENIED') {
      $error = $data['error_message'];
      $this->logger->get('geocode')->error($error);
      $this->errors[] = $error;
      return NULL;
    }
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
   * Load and parse all location entities.
   */
  private function loadLocations($origin) {
    try {
      // Get location terms.
      $taxonomy_terms = $this->entityTypeManager
        ->getStorage('taxonomy_term')
        ->loadByProperties(['vid' => 'location_type']);
      // Get all location entity nodes.
      $entities = $this->nodeStorage->loadByProperties(['type' => 'location']);
      // Get all location telephone paragraphs.
      $phone_paragraphs = $this->entityTypeManager
        ->getStorage('paragraph')
        ->loadByProperties(['type' => 'location_telephone']);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      $this->errors[] = "Error while creating response: {$ipde->getMessage()}.";
      return NULL;
    }
    $taxonomies = [];
    foreach ($taxonomy_terms as $term) {
      $taxonomies[$term->id()] = $term->label();
    }
    $phones = [];
    foreach ($phone_paragraphs as $phone) {
      $phones[$phone->id()] = $phone;
    }
    $data = [];
    foreach ($entities as $entity) {
      $type_id = $entity
        ->get('field_location_type')
        ->getValue()[0]['target_id'];
      $type = $taxonomies[$type_id];
      // Shipping offices are not included in the search results.
      if ($type == 'Shipping Office') {
        continue;
      }
      $data[$entity->id()] = $this->parse($entity, $type, $phones);
      $shipping = $this->shippingOffice($entity);
      if ($shipping) {
        $data[$entity->id()]['shipping_office'] = $this->parse($shipping, 'Shipping Office', $phones);
      }
      else {
        $data[$entity->id()]['shipping_office'] = NULL;
      }
      $distance_km = $this->distance($origin, $data[$entity->id()]['location']);
      $data[$entity->id()]['distance_km'] = $distance_km;
      $data[$entity->id()]['distance_mi'] = 0.621371 * $distance_km;
    }
    return $data;
  }

  /**
   * Return km from the origin to the given location.
   *
   * @param array $origin
   *   Geolocation of the origin or search.
   * @param array $location
   *   Location of the transportation office or weight scale.
   *
   * @return float
   *   The distance in km.
   */
  private function distance(array $origin, array $location) {
    // Convert from degrees to radians.
    $latFrom = $origin['lat'];
    $lonFrom = $origin['lon'];
    $latTo = $location['geolocation']['lat'];
    $lonTo = $location['geolocation']['lng'];
    // Radius of the earth in KM.
    $earthRadius = 6371.0;
    $latDelta = deg2rad($latTo - $latFrom);
    $lonDelta = deg2rad($lonTo - $lonFrom);
    $a = sin($latDelta / 2) * sin($latDelta / 2) +
      cos(deg2rad($latFrom)) * cos(deg2rad($latTo)) *
      sin($lonDelta / 2) * sin($lonDelta / 2);
    $c = 2 * atan2(sqrt($a), sqrt(1 - $a));
    // Distance in KM.
    $distance = $earthRadius * $c;
    return $distance;
  }

  /**
   * Parse a drupal entity data to more human readable data.
   *
   * @param \Drupal\node\Entity\Node $entity
   *   Drupal Location entity.
   * @param string $type
   *   Location type.
   * @param array $phones
   *   All location telephone entities.
   *
   * @return array
   *   Flattened and filtered array.
   */
  private function parse(Node $entity = NULL, $type, array $phones) {
    if ($entity == NULL) {
      return NULL;
    }
    $data = [];
    $data['type'] = $type;
    $data['title'] = $entity->getTitle();
    $address = $entity->get('field_location_address')->getValue();
    $data['location'] = empty($address) ? NULL : $address[0];
    $geolocation = $entity->get('field_geolocation')->getValue();
    $data['location']['geolocation'] = empty($geolocation) ? NULL : $geolocation[0];
    $data['email_addresses'] = $entity
      ->get('field_location_email')
      ->getValue();
    $data['hours'] = $entity
      ->get('field_location_hours')
      ->getValue();
    $data['websites'] = $entity
      ->get('field_location_link')
      ->getValue();
    $data['notes'] = $entity
      ->get('field_location_notes')
      ->getValue();
    $data['services'] = $entity
      ->get('field_location_services')
      ->getValue();
    $phone_references = $entity
      ->get('field_location_telephone')
      ->getValue();

    foreach ($phone_references as $ref) {
      $id = $ref['target_id'];
      $phone = $phones[$id];
      $data['phones'][$id]['field_phonenumber'] = $phone
        ->get('field_phonenumber')
        ->getValue();
      $data['phones'][$id]['field_dsn'] = $phone
        ->get('field_dsn')
        ->getValue();
      $data['phones'][$id]['field_type'] = $phone
        ->get('field_type')
        ->getValue();
    }
    return $data;
  }

  /**
   * Look for a location entity, of type shipping office, by id.
   *
   * @param \Drupal\node\Entity\Node $entity
   *   Drupal Location entity.
   *
   * @return \Drupal\Core\Entity\EntityInterface
   *   Drupal entity for the shipping office or NULL.
   */
  private function shippingOffice(Node $entity) {
    $reference = $entity
      ->get('field_location_reference')
      ->getValue();
    if (empty($reference)) {
      // Not all PPPOs or scales have a shipping office, or data is missing.
      return NULL;
    }
    $id = $reference[0]['target_id'];
    $entity = $this->nodeStorage->load($id);
    return $entity;
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
