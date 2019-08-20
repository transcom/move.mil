<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\parser\Service\DbReader;
use Drupal\parser\Service\RouteDistance;

/**
 * Class PpmEstimatorController.
 */
class PpmEstimatorController extends ControllerBase {

  /**
   * Drupal\parser\Service\DbReader definition.
   *
   * @var \Drupal\parser\Service\DbReader
   */
  private $dbReader;

  /**
   * Drupal\parser\Service\RouteDistance definition.
   *
   * @var \Drupal\parser\Service\RouteDistance
   */
  private $routeDistancesClient;

  /**
   * Constructs a PpmEstimatorController.
   *
   * @param \Drupal\parser\Service\DbReader $dbReader
   *   A Database Connection object.
   * @param \Drupal\parser\Service\RouteDistance $client
   *   A RouteDistance client.
   */
  public function __construct(DbReader $dbReader, RouteDistance $client) {
    $this->dbReader = $dbReader;
    $this->routeDistancesClient = $client;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('parser.reader'),
      $container->get('parser.route_distance')
    );
  }

  /**
   * Get PPM Incentive Estimate response.
   *
   * @param \Symfony\Component\HttpFoundation\Request $request
   *   The http request object.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return entitlements as a Json object
   */
  public function estimate(Request $request) {
    $params = NULL;
    $content = $request->getContent();
    if (!empty($content)) {
      $params = json_decode($content, TRUE);
    }
    if (!$params || !$this->validate($params)) {
      return JsonResponse::create('Invalid parameters.', 500);
    }
    // Get zip3s records.
    $start_zip3 = $this->dbReader->zip3($params['locations']['origin']);
    $end_zip3 = $this->dbReader->zip3($params['locations']['destination']);
    // Get year to use in 400NG queries.
    $year = substr($params['selectedMoveDate'], 0, 4);
    $month = substr($params['selectedMoveDate'], 5, 2);
    $day = substr($params['selectedMoveDate'], 8, 2);
    // 400NG rates are effective until May 15th.
    if ($month <= 05 || ($month == 05 && $day < 15)) {
      $year = $year - 1;
    }
    $year = $this->dbReader->closest400NgYear($year);
    // Get service areas records.
    $start_service_area = $this->dbReader->serviceArea($start_zip3['service_area'], $year);
    $end_service_area = $this->dbReader->serviceArea($end_zip3['service_area'], $year);
    // Get full weight.
    list($household, $progear, $spouse_progear) = $this->weights($params);
    $weight = floatval($household + $progear + $spouse_progear);
    // Weight divided by 100, AKA the hundredweight (centiweight?).
    $cwt = floatval($weight / 100.0);
    // Calculate PPM incentive estimates.
    $linehaul_charges = $this->linehaulCharges($start_service_area, $end_service_area, $year, $weight, $cwt, $params);
    $other_charges = $this->otherCharges($start_service_area, $end_service_area, $year, $weight, $cwt);
    $discount = $this->discount($start_zip3, $end_zip3, $params['locations']['origin'], $params['selectedMoveDate']);
    $total = $linehaul_charges + $other_charges;
    $incentive = $this->incentive($total, $discount);
    // Build data response.
    $data['locations'] = $this->locations($params['locations']['origin'], $params['locations']['destination']);
    $data['weightOptions'] = [
      'houseHold' => $household,
      'proGear' => $progear,
      'dependent' => $spouse_progear,
      'total' => $weight,
    ];
    $data['selectedMoveDate'] = $params['selectedMoveDate'];
    $data['incentive'] = $incentive;
    // Calculate maximum advance payment.
    $pct = 0.60;
    $data['advancePayment'] = [
      'cost' => $incentive * $pct,
      'percentage' => $pct * 100,
    ];
    // Create JSON response.
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
  private function validate(array $params) {
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
  private function validWeights(array $params) {
    $valid = isset($params['isDependencies']) && $params['selectedEntitlement'];
    $valid = $valid && $params['weightOptions']['houseHold'];
    return $valid;
  }

  /**
   * Get weights to use.
   */
  private function weights(array $params) {
    $household = 0;
    $progear = 0;
    $spouse_progear = 0;
    // Get entitlement.
    $entitlement = $this->dbReader->entitlement($params['selectedEntitlement']);
    if (!$entitlement) {
      return [$household, $progear, $spouse_progear];
    }
    // Get household goods weight.
    $household = intval($params['weightOptions']['houseHold']);
    $dependencies = isset($params['isDependencies']) && $params['isDependencies'];
    if ($dependencies) {
      $max_household = intval($entitlement['total_weight_self_plus_dependents']);
    }
    else {
      $max_household = intval($entitlement['total_weight_self']);
    }
    if ($household > $max_household) {
      $household = $max_household;
    }
    // Get pro gear weight.
    if (!$params['weightOptions']['proGear']) {
      $progear = 0;
    }
    else {
      $progear = intval($params['weightOptions']['proGear']);
    }
    $max_progear = intval($entitlement['pro_gear_weight']);
    if ($progear > $max_progear) {
      $progear = $max_progear;
    }
    if (!$dependencies) {
      return [$household, $progear, $spouse_progear];
    }
    // Get spouse pro gear weight.
    if (!$params['weightOptions']['dependent']) {
      $spouse_progear = 0;
    }
    else {
      $spouse_progear = intval($params['weightOptions']['dependent']);
    }
    $max_spouse_progear = intval($entitlement['pro_gear_weight_spouse']);
    if ($spouse_progear > $max_spouse_progear) {
      $spouse_progear = $max_spouse_progear;
    }
    return [$household, $progear, $spouse_progear];
  }

  /**
   * Get Google route distance between two zipcodes.
   */
  private function distance($start_zip, $end_zip) {
    $locations = $this->locations($start_zip, $end_zip);
    $origins = "{$locations['origin']['lat']},{$locations['origin']['lon']}";
    $destinations = "{$locations['destination']['lat']},{$locations['destination']['lon']}";
    $data = $this->routeDistancesClient->distances($origins, $destinations);
    if (!empty($data)) {
      // Get distance value and convert it from meters to miles.
      return round($data['rows'][0]['elements'][0]['distance']['value'] / 1609.344, 2);
    }
    return 0;
  }

  /**
   * Build locations response with the given zipcodes.
   */
  private function locations($start_zip, $end_zip) {
    $locations['origin'] = $this->location($start_zip);
    $locations['destination'] = $this->location($end_zip);
    return $locations;
  }

  /**
   * Build location response with the given zipcode.
   */
  private function location($zipcode) {
    $uszipcode = $this->dbReader->uszipcode($zipcode);
    $location['address'] = "{$uszipcode['city']}, {$uszipcode['state']} {$uszipcode['code']}";
    $location['lat'] = floatval($uszipcode['lat']);
    $location['lon'] = floatval($uszipcode['lon']);
    return $location;
  }

  /**
   * Calculate linehaul charges.
   */
  private function linehaulCharges($start_service_area, $end_service_area, $year, $weight, $cwt, $params) {
    // Sum service areas linehaul factors.
    $linehaul_factor = $start_service_area['linehaul_factor'] + $end_service_area['linehaul_factor'];
    // Get distance.
    $distance = $this->distance($params['locations']['origin'], $params['locations']['destination']);
    // Get linehaul rate.
    if ($weight < 1000) {
      // If weight is less than 1000lbs then use rate * (weight / 1000).
      $linehaul = $this->dbReader->linehaul($distance, 1000, $year);
      $linehaul_rate = floatval($linehaul['rate']) * ($weight / 1000.0);
    }
    else {
      $linehaul = $this->dbReader->linehaul($distance, $weight, $year);
      $linehaul_rate = floatval($linehaul['rate']);
    }
    // Get shorthaul rate.
    if ($distance > 800) {
      $shorthaul_rate = 0.0;
    }
    else {
      $shorthaul = $this->dbReader->shorthaul($distance, $cwt, $year);
      $shorthaul_rate = floatval($shorthaul['rate']);
    }
    return $linehaul_rate + ($linehaul_factor * $cwt) + $shorthaul_rate;
  }

  /**
   * Calculate non related linehaul charges.
   */
  private function otherCharges($start_service_area, $end_service_area, $year, $weight, $cwt) {
    $charges = $start_service_area['orig_dest_service_charge'] + $end_service_area['orig_dest_service_charge'];
    $pack = $this->dbReader->packunpack($start_service_area, $year, $weight);
    $unpack = $this->dbReader->packunpack($end_service_area, $year);
    $packunpack = floatval($pack['pack']) + floatval($unpack['unpack']);
    $charges += $packunpack;
    return $charges * $cwt;
  }

  /**
   * Get discounts from tsp discounts table.
   */
  private function discounts($start_zip3, $end_zip3, $start_zipcode, $move_date) {
    $area = $start_zip3['rate_area'];
    if ($area === 'ZIP') {
      $zip5 = $this->dbReader->zip5($start_zipcode);
      $area = $zip5['service_area'];
    }
    if ($start_zip3['state'] == $end_zip3['state']) {
      $region = 15;
    }
    else {
      $region = $end_zip3['region'];
    }
    $discount_entry = $this->dbReader->discount("US{$area}", "REGION {$region}", strtotime($move_date));
    $discount_pct = $discount_entry['discounts'];
    $discount = 1 - ($discount_pct / 100);
    // Don't go below 0% or above 100% before applying PPM incentive.
    $discounts['min'] = max($discount - 0.02, 0.0) * 0.95;
    $discounts['max'] = min($discount + 0.02, 1.0) * 0.95;
    return $discounts;
  }

  /**
   * Get discounts from tsp discounts table.
   */
  private function discount($start_zip3, $end_zip3, $start_zipcode, $move_date) {
    $area = $start_zip3['rate_area'];
    if ($area === 'ZIP') {
      $zip5 = $this->dbReader->zip5($start_zipcode);
      $area = $zip5['service_area'];
    }
    if ($start_zip3['state'] == $end_zip3['state']) {
      $region = 15;
    }
    else {
      $region = $end_zip3['region'];
    }
    $discount_entry = $this->dbReader->discount("US{$area}", "REGION {$region}", strtotime($move_date));
    $discount_pct = $discount_entry['discounts'];
    $discount = 1 - ($discount_pct / 100);
    return $discount;
  }

  /**
   * Get the nearest int multiple of 100 less than or equal to the input.
   */
  private function floorHundred($input) {
    return intval($input - $input % 100.0);
  }

  /**
   * Get the nearest int multiple of 100 greater than or equal to the input.
   */
  private function ceilHundred($input) {
    $remainder = $input % 100.0;
    if ($remainder == 0) {
      return intval($input);
    }
    return intval($input + (100.0 - $remainder));
  }

  /**
   * Round PPM incentives with total cost and dscounts.
   */
  private function incentives($total, array $discounts) {
    $mincost = floatval($total * $discounts['min']);
    $maxcost = floatval($total * $discounts['max']);
    $incentives['min'] = $this->floorHundred($mincost);
    $incentives['max'] = $this->ceilHundred($maxcost);
    return $incentives;
  }

  /**
   * Round PPM incentives with total cost and discounts.
   */
  private function incentive($total, $discount) {
    $cost = floatval($total * $discount);
    $incentive = $this->floorHundred($cost);
    return $incentive;
  }

}
