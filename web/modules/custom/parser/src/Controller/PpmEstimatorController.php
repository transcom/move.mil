<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection as Connection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Class PpmEstimatorController.
 */
class PpmEstimatorController extends ControllerBase {

  private $databaseConnection;
  private $EntitlementsRepository;

  /**
   * Constructs a PpmEstimatorController.
   *
   * @param \Drupal\Core\Database\Connection $databaseConnection
   *   A Database Connection object.
   */
  public function __construct(Connection $databaseConnection) {
    $this->databaseConnection = $databaseConnection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Get PPM Incentive Estimate response.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return entitlements as a Json object
   */
  public function ppm_estimate(Request $request) {
    $content = $request->getContent();
    if (!empty($content)) {
      $params = json_decode($content, TRUE);
    }
    if (!$this->validate($params)) {
      return JsonResponse::create('Invalid parameters.', 500);
    }
    $start_zip3 = $this->zip3($params['locations']['origin']);
    $end_zip3 = $this->zip3($params['locations']['destination']);
    $start_service_area = $this->service_area($start_zip3['service_area']);
    $end_service_area = $this->service_area($end_zip3['service_area']);
    // Sum service areas linehaul factors.
    $linehaulFactor = $start_service_area['linehaul_factor'] + $end_service_area['linehaul_factor'];
    // Get full weight.
    list($household, $progear, $spouse_progear) = $this->weights($params);
    $weight = $household + $progear + $spouse_progear;
    // Get distance.
    $distance = 2774;
    // Get linehaul rate.
    // If weight is less than 1000lbs, then use linehaul rate * (weight / 1000.0) .
    $year = 2018;
    $linehaul = $this->linehaul($distance, $weight, $year);
    if ($weight < 1000) {
      $linehaul_rate = $linehaul['rate'];
    }
    else {
      $linehaul_rate = $linehaul['rate'] * ($weight / 1000.0);
    }
    $min_incentive = ($linehaulFactor + $linehaul_rate) * 0.98;
    $max_incentive = ($linehaulFactor + $linehaul_rate) * 1.02;
    $min_payment = ($linehaulFactor + $linehaul_rate) * 0.98;
    $min_payment = ($linehaulFactor + $linehaul_rate) * 1.02;
    $discount = 60;
    $data = [
      'locations' => [
        'origin' => [
          'address' => "Fairfax, VA {$params['locations']['origin']}",
          'lat' => 38.853231,
          'lon' => -77.305097,
        ],
        'destination' => [
          'address' => "Beverly Hills, CA {$params['locations']['destination']}",
          'lat' => 38.025472,
          'lon' => -121.291628,
        ],
      ],
      'weightOptions' => [
        'houseHold' => $household,
        'proGear' => $progear,
        'dependent' => $spouse_progear,
        'total' => $weight,
      ],
      'selectedMoveDate' => $params['selectedMoveDate'],
      'incentive' => [
        'min' => $min_incentive,
        'max' => $max_incentive,
      ],
      'advancePayment' => [
        'min' => $min_payment,
        'max' => $min_payment,
        'discount' => $discount,
      ],
    ];
    $response = JsonResponse::create($data, 200);
    $response->setEncodingOptions(
      $response->getEncodingOptions() |
      JSON_PRETTY_PRINT |
      JSON_FORCE_OBJECT
    );
    if (gettype($response) == 'object') {
      return $response;
    }
    else {
      return JsonResponse::create('Error while creating response.', 500);
    }
  }

  /**
   * Validate request params.
   */
  public function validate(array $params) {
    $valid = $params['locations'] && $params['locations']['origin'] && $params['locations']['destination'];
    $valid = $valid  && strlen($params['locations']['origin']) == 5;
    $valid = $valid  && strlen($params['locations']['destination']) == 5;
    $valid = $valid  && $this->validWeights($params);
    $valid = $valid  && $params['selectedMoveDate'];
    return $valid;
  }

  /**
   * Validate entitlement param.
   */
  public function validWeights(array $params) {
    $valid = $params['isDependencies'] && $params['selectedEntitlement'];
    $valid = $valid && $params['weightOptions']['houseHold'];
    return $valid;
  }

  /**
   * Get zip3 object according to the given zip code.
   */
  public function zip3($zipcode) {
    $zip3str = substr($zipcode, 0, 3);
    $zip3 = $this->databaseConnection
      ->select('parser_zip3s')
      ->fields('parser_zip3s')
      ->condition('zip3', $zip3str)
      ->execute()
      ->fetch();
    return (array) $zip3;
  }

  /**
   * Get service area object according to the given service area number.
   */
  public function service_area($service_area) {
    $sa = $this->databaseConnection
      ->select('parser_service_areas')
      ->fields('parser_service_areas')
      ->condition('service_area', $service_area)
      ->execute()
      ->fetch();
    return (array) $sa;
  }

  /**
   * Get weights to use.
   */
  public function weights(array $params) {
    $household = 0;
    $progear = 0;
    $spouse_progear = 0;
    // Get entitlement.
    $entitlement = $this->entitlement($params['selectedEntitlement']);
    if (!$entitlement) return [$household, $progear, $spouse_progear];
    // Get household goods weight.
    $household = $params['weightOptions']['houseHold'];
    if ($dependencies) {
      $max_household = intval($entitlement['total_weight_self_plus_dependents']);
    }
    else {
      $max_household = intval($entitlement['total_weight_self']);
    }
    if ($household > $max_household) {
      $household = $max_household;
    }
    // Get pro gear weight
    if (!$params['weightOptions']['proGear']) {
      $progear = 0;
    }
    else {
      $progear = $params['weightOptions']['proGear'];
    }
    $max_progear = intval($entitlement['pro_gear_weight']);
    if ($progear > $max_progear) {
      $progear = $max_progear;
    }
    if (!$params['isDependencies']) return [$household, $progear, $spouse_progear];
    // Get spouse pro gear weight
    if (!$params['weightOptions']['dependent']) {
      $spouse_progear = 0;
    }
    else {
      $spouse_progear = $params['weightOptions']['dependent'];
    }
    $max_spouse_progear = intval($entitlement['pro_gear_weight_spouse']);
    if ($spouse_progear > $max_spouse_progear) {
      $spouse_progear = $max_spouse_progear;
    }
    return [$household, $progear, $spouse_progear];
  }

  /**
   * Get entitlement object according to the given entitlement slug.
   */
  public function entitlement($slug) {
    $e = $this->databaseConnection
      ->select('parser_entitlements')
      ->fields('parser_entitlements')
      ->condition('slug', $slug)
      ->execute()
      ->fetch();
    return (array) $e;
  }

  /**
   * Get linehaul object according to the given distance, weight, year.
   */
  public function linehaul($distance, $weight, $year) {
    // Get the linehaul object that is in between 2 distances.
    $lh = $this->databaseConnection
      ->select('parser_linehauls')
      ->fields('parser_linehauls')
      ->condition('year', $year)
      ->condition('miles', $distance, '>')
      ->range(0, 1)
      ->execute()
      ->fetch();
    $linehaul = (array) $lh;
    $lh = $this->databaseConnection
      ->select('parser_linehauls')
      ->fields('parser_linehauls')
      ->condition('id', $linehaul['id'] - 1)
      ->range(0, 1)
      ->execute()
      ->fetch();
    $linehaul = (array) $lh;
    $miles = $linehaul['miles'];
    // Get the linehaul object that is in between 2 weights.
    $lh = $this->databaseConnection
      ->select('parser_linehauls')
      ->fields('parser_linehauls')
      ->condition('miles', $miles)
      ->condition('weight', $weight, '>')
      ->range(0, 1)
      ->execute()
      ->fetch();
    $linehaul = (array) $lh;
    $lh = $this->databaseConnection
      ->select('parser_linehauls')
      ->fields('parser_linehauls')
      ->condition('id', $linehaul['id'] - 1)
      ->range(0, 1)
      ->execute()
      ->fetch();
    return (array) $lh;
  }

}
