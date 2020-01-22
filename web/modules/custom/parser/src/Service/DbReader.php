<?php

namespace Drupal\parser\Service;

use Drupal\Core\Database\Connection;

/**
 * Class DBReader.
 *
 * Handles data insertion into custom tables for parser module.
 */
class DbReader {

  /**
   * Drupal\Core\Database\Connection definition.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $databaseConnection;

  /**
   * DbReader constructor.
   *
   * Needed for dependency injection.
   */
  public function __construct(Connection $db) {
    $this->databaseConnection = $db;
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
   * Get zip3 object according to the given zip code.
   */
  public function zip3($zipcode) {
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
  public function serviceArea($service_area, $year) {
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
   * Get uszipcode object according to the given zip code.
   */
  public function uszipcode($zipcode) {
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
  public function linehaul($distance, $weight, $year) {
    // Get the linehaul object that is in between 2 distances.
    $lhs = $this->databaseConnection
      ->select('parser_linehauls')
      ->fields('parser_linehauls')
      ->condition('year', $year)
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
  public function shorthaul($distance, $cwt, $year) {
    // Get the shorthaul object that is in between 2 cwt_miles.
    $ss = $this->databaseConnection
      ->select('parser_shorthauls')
      ->fields('parser_shorthauls')
      ->condition('year', $year)
      ->execute()
      ->fetchAll();
    $cwt_miles = $cwt * $distance;
    $closestCwtMiles = $this->closestValue($ss, $cwt_miles, 'cwt_miles');
    // Get the shorthaul object.
    $s = $this->databaseConnection
      ->select('parser_shorthauls')
      ->fields('parser_shorthauls')
      ->condition('cwt_miles', $closestCwtMiles)
      ->range(0, 1)
      ->execute()
      ->fetch();
    return (array) $s;
  }

  /**
   * Get closest year of the 400NG data.
   *
   * If the user enters a date that's after all 400NG rates in the database,
   * then use the 400NG rates with an effective date closest to the date the
   * user gave the parser_packunpacks table is the shortest,
   * so should be the quickest to query.
   *
   * @param int $year
   *   The year the user gave.
   *
   * @return int
   *   The year closest to the one given in the database.
   */
  public function closest400NgYear($year) {
    $ps = $this->databaseConnection
      ->select('parser_packunpacks')
      ->fields('parser_packunpacks')
      ->execute()
      ->fetchAll();
    return $this->closestValue($ps, $year, 'year');
  }

  /**
   * Get packunpack object according to the given service_area, year, weight.
   */
  public function packunpack($service_area, $year, $weight = 0) {
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
  public function discount($origin, $destination, $date) {
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
    $firstEntry = (array) $entries[0];
    $lowest = intval($firstEntry[$column]);
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
      if ($value < $lowest) {
        $lowest = $value;
      }
    }
    // If value higher than the values in the db, just return the highest.
    if ($rawvalue > $highest) {
      $closest = $highest;
    }
    // If value lower than the values in the db, just return the lowest.
    if ($rawvalue < $lowest) {
      $closest = $lowest;
    }
    return $closest;
  }

}
