<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Database\Connection as Connection;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use GuzzleHttp\Client;

/**
 * Class PpmEstimatorController.
 */
class PpmEstimatorController extends ControllerBase {

  private $databaseConnection;
  private $googleApi;

  /**
   * Constructs a PpmEstimatorController.
   *
   * @param \Drupal\Core\Database\Connection $databaseConnection
   *   A Database Connection object.
   */
  public function __construct(Connection $databaseConnection) {
    $this->databaseConnection = $databaseConnection;
    $this->googleApi = $_SERVER['GOOGLE_MAPS_API_KEY'];
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
    $start_zip3 = $this->zip3($params['locations']['origin']);
    $end_zip3 = $this->zip3($params['locations']['destination']);
    // Get year to use in 400NG queries.
    $year = substr($params['selectedMoveDate'], 0, 4);
    // Get service areas records.
    $start_service_area = $this->serviceArea($start_zip3['service_area'], $year);
    $end_service_area = $this->serviceArea($end_zip3['service_area'], $year);
    // Get full weight.
    list($household, $progear, $spouse_progear) = $this->weights($params);
    $weight = floatval($household + $progear + $spouse_progear);
    // Weight divided by 100, AKA the hundredweight (centiweight?).
    $cwt = floatval($weight / 100.0);
    // Calculate PPM incentive estimates.
    $linehaul_charges = $this->linehaulCharges($start_service_area, $end_service_area, $year, $weight, $cwt, $params);
    $other_charges = $this->otherCharges($start_service_area, $end_service_area, $year, $weight, $cwt);
    $discounts = $this->discounts($start_zip3, $end_zip3, $params['locations']['origin'], $params['selectedMoveDate']);
    $total = $linehaul_charges + $other_charges;
    $incentives = $this->incentives($total, $discounts);
    // Build data response.
    $data['locations'] = $this->locations($params['locations']['origin'], $params['locations']['destination']);
    $data['weightOptions'] = [
      'houseHold' => $household,
      'proGear' => $progear,
      'dependent' => $spouse_progear,
      'total' => $weight,
    ];
    $data['selectedMoveDate'] = $params['selectedMoveDate'];
    $data['incentive'] = $incentives;
    // Calculate maximum advance payment.
    $pct = 0.60;
    $data['advancePayment'] = [
      'min' => $incentives['min'] * $pct,
      'max' => $incentives['max'] * $pct,
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
   * Get zip3 object according to the given zip code.
   */
  private function zip3($zipcode) {
    $zip3str = substr($zipcode, 0, 3);
    $zip3 = $this->databaseConnection
      ->select('parser_zip3s')
      ->fields('parser_zip3s')
      ->condition('zip3', intval($zip3str))
      ->execute()
      ->fetch();
    return (array) $zip3;
  }

  /**
   * Get service area object according to the given service area number.
   */
  private function serviceArea($service_area, $year) {
    $sa = $this->databaseConnection
      ->select('parser_service_areas')
      ->fields('parser_service_areas')
      ->condition('service_area', $service_area)
      ->condition('year', $year)
      ->execute()
      ->fetch();
    return (array) $sa;
  }

  /**
   * Get weights to use.
   */
  private function weights(array $params) {
    $household = 0;
    $progear = 0;
    $spouse_progear = 0;
    // Get entitlement.
    $entitlement = $this->entitlement($params['selectedEntitlement']);
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
   * Get entitlement object according to the given entitlement slug.
   */
  private function entitlement($slug) {
    $e = $this->databaseConnection
      ->select('parser_entitlements')
      ->fields('parser_entitlements')
      ->condition('slug', $slug)
      ->execute()
      ->fetch();
    return (array) $e;
  }

  /**
   * Get Google route distance between two zipcodes.
   */
  private function distance($start_zip, $end_zip) {
    $client = new Client();
    $locations = $this->locations($start_zip, $end_zip);
    // Build Google Distance Matrix Request.
    $googleUrl = 'https://maps.googleapis.com/maps/api/distancematrix/json';
    $origins = "{$locations['origin']['lat']},{$locations['origin']['lon']}";
    $destinations = "{$locations['destination']['lat']},{$locations['destination']['lon']}";
    $key = $this->googleApi;
    $request = "{$googleUrl}?origins={$origins}&destinations={$destinations}&key={$key}";
    $res = $client->request('GET', $request);
    $data = json_decode($res->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
    if ($res->getStatusCode() == 200) {
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
    $uszipcode = $this->uszipcode($zipcode);
    $location['address'] = "{$uszipcode['city']}, {$uszipcode['state']} {$uszipcode['code']}";
    $location['lat'] = floatval($uszipcode['lat']);
    $location['lon'] = floatval($uszipcode['lon']);
    return $location;
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
   * Get linehaul object according to the given distance, weight, year.
   */
  private function linehaul($distance, $weight, $year) {
    // Get the linehaul object that is in between 2 distances.
    $lhs = $this->databaseConnection
      ->select('parser_linehauls')
      ->fields('parser_linehauls')
      ->execute()
      ->fetchAll();
    $closestMiles = $this->closestValue($lhs, $distance, 'miles');
    // Get the linehaul object that is in between 2 weights.
    $lhs = $this->databaseConnection
      ->select('parser_linehauls')
      ->fields('parser_linehauls')
      ->condition('miles', $closestMiles)
      ->condition('year', $year)
      ->execute()
      ->fetchAll();
    $closestWeight = $this->closestValue($lhs, $weight, 'weight');
    $lh = $this->databaseConnection
      ->select('parser_linehauls')
      ->fields('parser_linehauls')
      ->condition('miles', $closestMiles)
      ->condition('year', $year)
      ->condition('weight', $closestWeight)
      ->range(0, 1)
      ->execute()
      ->fetch();
    return (array) $lh;
  }

  /**
   * Get shorthaul object according to the given distance, weight, year.
   */
  private function shorthaul($distance, $cwt, $year) {
    // Get the shorthaul object that is in between 2 cwt_miles.
    $ss = $this->databaseConnection
      ->select('parser_shorthauls')
      ->fields('parser_shorthauls')
      ->execute()
      ->fetchAll();
    $closestCwtMiles = $this->closestValue($ss, $cwt * $distance, 'cwt_miles');
    // Get the shorthaul object.
    $s = $this->databaseConnection
      ->select('parser_shorthauls')
      ->fields('parser_shorthauls')
      ->condition('cwt_miles', $closestCwtMiles)
      ->condition('year', $year)
      ->range(0, 1)
      ->execute()
      ->fetch();
    return (array) $s;
  }

  /**
   * Get packunpack object according to the given service_area, year, weight.
   */
  private function packunpack($service_area, $year, $weight = 0) {
    // Get the packunpack object that is in between 2 weights.
    $ps = $this->databaseConnection
      ->select('parser_packunpacks')
      ->fields('parser_packunpacks')
      ->execute()
      ->fetchAll();
    $closestCwt = $this->closestValue($ps, $weight, 'cwt');
    // Get the shorthaul object.
    $p = $this->databaseConnection
      ->select('parser_packunpacks')
      ->fields('parser_packunpacks')
      ->condition('schedule', $service_area['services_schedule'])
      ->condition('cwt', $closestCwt)
      ->condition('year', $year)
      ->range(0, 1)
      ->execute()
      ->fetch();
    return (array) $p;
  }

  /**
   * Get zip5 object according to the given zip code.
   */
  private function zip5($zipcode) {
    $zip5 = $this->databaseConnection
      ->select('parser_zip5s')
      ->fields('parser_zip5s')
      ->condition('zip5', $zipcode)
      ->execute()
      ->fetch();
    return (array) $zip5;
  }

  /**
   * Get discount object according to the given area, region and date.
   */
  private function discount($origin, $destination, $date) {
    $ds = $this->databaseConnection
      ->select('parser_discounts')
      ->fields('parser_discounts')
      ->execute()
      ->fetchAll();
    $closestTdl = $this->closestValue($ds, $date, 'tdl');
    $discount = $this->databaseConnection
      ->select('parser_discounts')
      ->fields('parser_discounts')
      ->condition('origin', $origin)
      ->condition('destination', $destination)
      ->condition('tdl', $closestTdl)
      ->execute()
      ->fetch();
    return (array) $discount;
  }

  /**
   * Look for the range that a given number belongs to.
   *
   * Then return the lowest value of that range.
   */
  private function closestValue(array $entries, $rawvalue, $column) {
    $highest = 0;
    $closest = 0;
    foreach ($entries as $entry) {
      $e = (array) $entry;
      $value = intval($e[$column]);
      if ($rawvalue >= $value) {
        $closest = $value;
      }
      if ($value > $highest) {
        $highest = $value;
      }
    }
    // If value higher than the values in the db, just return the highest.
    if ($rawvalue > $highest) {
      $closest = $highest;
    }
    return $closest;
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
      $linehaul = $this->linehaul($distance, 1000, $year);
      $linehaul_rate = floatval($linehaul['rate']) * ($weight / 1000.0);
    }
    else {
      $linehaul = $this->linehaul($distance, $weight, $year);
      $linehaul_rate = floatval($linehaul['rate']);
    }
    // Get shorthaul rate.
    if ($distance > 800) {
      $shorthaul_rate = 0.0;
    }
    else {
      $shorthaul = $this->shorthaul($distance, $cwt, $year);
      $shorthaul_rate = floatval($shorthaul['rate']);
    }
    return $linehaul_rate + ($linehaul_factor * $cwt) + $shorthaul_rate;
  }

  /**
   * Calculate non related linehaul charges.
   */
  private function otherCharges($start_service_area, $end_service_area, $year, $weight, $cwt) {
    $charges = $start_service_area['orig_dest_service_charge'] + $end_service_area['orig_dest_service_charge'];
    $pack = $this->packunpack($start_service_area, $year, $weight);
    $unpack = $this->packunpack($end_service_area, $year);
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
      $zip5 = $this->zip5($start_zipcode);
      $area = $zip5['service_area'];
    }
    if ($start_zip3['state'] == $end_zip3['state']) {
      $region = 15;
    }
    else {
      $region = $end_zip3['region'];
    }
    $discount_entry = $this->discount("US{$area}", "REGION {$region}", strtotime($move_date));
    $discount_pct = $discount_entry['discounts'];
    $discount = 1 - ($discount_pct / 100);
    // Don't go below 0% or above 100% before applying PPM incentive.
    $discounts['min'] = max($discount - 0.02, 0.0) * 0.95;
    $discounts['max'] = min($discount + 0.02, 1.0) * 0.95;
    return $discounts;
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

}
