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
    // Get zip3s records.
    $start_zip3 = $this->zip3($params['locations']['origin']);
    $end_zip3 = $this->zip3($params['locations']['destination']);
    // Get year to use in 400NG queries.
    $year = substr($params['selectedMoveDate'], 0, 4);
    // Get service areas records.
    $start_service_area = $this->service_area($start_zip3['service_area'], $year);
    $end_service_area = $this->service_area($end_zip3['service_area'], $year);
    // Get full weight.
    list($household, $progear, $spouse_progear) = $this->weights($params);
    $weight = $household + $progear + $spouse_progear;
    // Weight divided by 100, AKA the hundredweight (centiweight?).
    $cwt = $weight / 100;
    // Calculate PPM incentive estimates.
    $linehaul_charges = $this->linehaul_charges($start_service_area, $end_service_area, $year, $weight, $cwt, $params);
    $other_charges = $this->other_charges($start_service_area, $end_service_area, $year, $weight, $cwt);
    $discounts = $this->discounts($start_zip3, $end_zip3, $params['locations']['origin'], $params['selectedMoveDate']);
    $total = $linehaul_charges + $other_charges;
    $incentives = $this->incentives($total, $discounts);
    // Build data response
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
  public function service_area($service_area, $year) {
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
    // Get pro gear weight.
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
    // Get spouse pro gear weight.
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
   * Get Google route distance between two zipcodes.
   */
  public function distance($start_zip, $end_zip) {
    return 2774;
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
    $uszipcode = $this->uszipcode($zip);
    $location['address'] = "{$uszipcode['city']}, {$uszipcode['state']} {$uszipcode['zipcode']}";
    $location['lat'] = floatval($uszipcode['lat']);
    $location['lon'] = floatval($uszipcode['lat']);
    return $location;
  }

  /**
   * Get uszipcode object according to the given zip code.
   */
  public function uszipcode($zipcode) {
    // $uszipcode = $this->databaseConnection
    //   ->select('parser_uszipcodes')
    //   ->fields('parser_uszipcodes')
    //   ->condition('code', $zipcode)
    //   ->execute()
    //   ->fetch();
    // return (array) $uszipcode;
    return [
      'code' => 90210,
      'city' => 'Beverly Hills',
      'state' => 'CA',
      'lat' => 33.786594,
      'lon' => -118.298662,
    ];
  }

  /**
   * Get linehaul object according to the given distance, weight, year.
   */
  public function linehaul($distance, $weight, $year) {
    // Get the linehaul object that is in between 2 distances.
    $lh = $this->databaseConnection
      ->select('parser_linehauls')
      ->fields('parser_linehauls')
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
      ->condition('year', $year)
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

  /**
   * Get shorthaul object according to the given distance, weight, year.
   */
  public function shorthaul($distance, $cwt, $year) {
    // Get the shorthaul object that is in between 2 cwt_miles.
    $s = $this->databaseConnection
      ->select('parser_shorthauls')
      ->fields('parser_shorthauls')
      ->condition('cwt_miles', $cwt * $distance, '>')
      ->range(0, 1)
      ->execute()
      ->fetch();
    $shorthaul = (array) $s;
    $s = $this->databaseConnection
      ->select('parser_shorthauls')
      ->fields('parser_shorthauls')
      ->condition('id', $shorthaul['id'] - 1)
      ->range(0, 1)
      ->execute()
      ->fetch();
    $shorthaul = (array) $s;
    $cwt_miles = $shorthaul['cwt_miles'];
    // Get the shorthaul object.
    $s = $this->databaseConnection
      ->select('parser_shorthauls')
      ->fields('parser_shorthauls')
      ->condition('cwt_miles', $cwt_miles)
      ->condition('year', $year)
      ->range(0, 1)
      ->execute()
      ->fetch();
    return (array) $s;
  }

  /**
   * Get packunpack object according to the given service_area, year, weight.
   */
  public function packunpack($service_area, $year, $weight = 0) {
    // Get the packunpack object that is in between 2 weights.
    $p = $this->databaseConnection
      ->select('parser_packunpacks')
      ->fields('parser_packunpacks')
      ->condition('cwt', $weight, '>')
      ->range(0, 1)
      ->execute()
      ->fetch();
    $packunpack = (array) $p;
    $p = $this->databaseConnection
      ->select('parser_packunpacks')
      ->fields('parser_packunpacks')
      ->condition('id', $packunpack['id'] - 1)
      ->range(0, 1)
      ->execute()
      ->fetch();
    $packunpack = (array) $p;
    // Get the shorthaul object.
    $p = $this->databaseConnection
      ->select('parser_packunpacks')
      ->fields('parser_packunpacks')
      ->condition('schedule', $service_area['services_schedule'])
      ->condition('cwt', $packunpack['cwt'])
      ->condition('year', $year)
      ->range(0, 1)
      ->execute()
      ->fetch();
    return (array) $p;
  }

  /**
   * Get zip5 object according to the given zip code.
   */
  public function zip5($zipcode) {
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
  public function discount($area, $region, $date) {
    // $tdls = $this->databaseConnection
    //   ->select('parser_discounts', 'd')
    //   ->addField('d', 'tdl')
    //   ->distinct()
    //   ->execute()
    //   ->fetchAll();
    // $tdldate = $this->lowest_value($tdls, $date, 'tdl');
    // $discount = $this->databaseConnection
    //   ->select('parser_discounts')
    //   ->fields('parser_discounts')
    //   ->condition('area', $area)
    //   ->condition('region', $region)
    //   ->condition('date', $tdldate)
    //   ->execute()
    //   ->fetch();
    // return (array) $discount;
    return [
      'origin' => 'US11',
      'region' => 'REGION 14',
      'discount' => 68,
      'sit_rate' => 60,
    ];
  }

  /**
   * Look for the range that a given number belongs to.
   * Then return the lowest value of that range.
   */
  public function lowest_value(array $entries, $rawvalue, $column) {
    $highest = 0;
    foreach ($entries as $key => $entry) {
      $e = (array) $entry;
      $value = $e[$column];
      if ($value > $rawvalue) {
        $highest = $key;
        break;
      }
    }
    $entry = (array) $entries[$highest - 1];
    return $entry[$column];
  }

  /**
   * Calculate linehaul charges.
   */
  public function linehaul_charges($start_service_area, $end_service_area, $year, $weight, $cwt, $params) {
    // Sum service areas linehaul factors.
    $linehaul_factor = $start_service_area['linehaul_factor'] + $end_service_area['linehaul_factor'];
    // Get distance.
    $distance = $this->distance($params['locations']['origin'], $params['locations']['destination']);
    // Get linehaul rate.   
    $linehaul = $this->linehaul($distance, $weight, $year);
    if ($weight < 1000) {
      // If weight is less than 1000lbs then use rate * (weight / 1000).
      $linehaul_rate = floatval($linehaul['rate']) * ($weight / 1000.0);
    }
    else {
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
  public function other_charges($start_service_area, $end_service_area, $year, $weight, $cwt) {
    $charges = $start_service_area['orig_dest_service_charge'] + $end_service_area['orig_dest_service_charge'];
    $pack = $this->packunpack($start_service_area, $year, $weight);
    $unpack = $this->packunpack($end_service_area, $year);
    $packunpack = $pack['pack'] + $unpack['unpack'];
    $charges += $packunpack;
    return $charges * $cwt;
  }

  /**
   * Get discounts from tsp discounts table.
   */
  public function discounts($start_zip3, $end_zip3, $start_zipcode, $move_date) {
    $area = $start_zip3['rate_area'];
    if ($area === 'ZIP') {
      $zip5 = $this->zip5($start_zipcode);
      $area = $zip5['rate_area'];
    }
    if ($start_zip3['state'] == $end_zip3['state']) {
      $region = 15;
    }
    else {
      $region = $end_zip3['region'];
    }
    $mysqldate = date('Y-m-d H:i:s', strtotime($move_date));
    $discount_entry = $this->discount("US#{$area}", "REGION #{$region}", $mysqldate);
    $discount_pct = $discount_entry['discount'];
    $discount = 1 - ($discount_pct / 100);
    // Don't go below 0% or above 100% before applying PPM incentive.
    $discounts['min'] = max($discount - 0.02, 0) * 0.95;
    $discounts['max'] = min($discount + 0.02, 1) * 0.95;
    return $discounts;
  }

  /**
   * Get the nearest int multiple of 100 less than or equal to the input.
   */
  public function floor_hundred($input) {
    return intval($input - $input % 100);
  }

  /**
   * Get the nearest int multiple of 100 greater than or equal to the input.
   */
  public function ceil_hundred($input) {
    $remainder = $input % 100;
    if ($remainder == 0) {
      return intval($input);
    }
    return intval($input + (100 - $remainder));
  }

  /**
   * Round PPM incentives with total cost and dscounts.
   */
  private function incentives($total, array $discounts) {
    $incentives['min'] = $this->floor_hundred($total * $discounts['min']);
    $incentives['max'] = $this->ceil_hundred($total * $discounts['max']);
    return $incentives;
  }

}
