<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\WriterInterface;
use Drupal\parser\Writer\Json\JsonWriter;

/**
 * Class Rates400NGWriter.
 *
 * Parses a given array and returns a JSON structure.
 */
class Rates400NGWriter implements WriterInterface {
  use JsonWriter;

  /**
   * Normalizes data then writes service_areas, linehauls, shorthauls, and packunpack files.
   */
  public function write(array $rawdata) {
    // Write service_areas.json
    $this->writeJson($rawdata['schedules'], $rawdata['date'].'service_areas.json');
    // Write linehauls.json
    $linehauls = $this->maplinehauldata($rawdata['linehauls']);
    $this->writeJson($linehauls, $rawdata['date'].'linehauls.json');
    // Write shorthauls.json
    $shorthauls = $this->mapshorthauldata($rawdata['shorthauls']);
    $this->writeJson($shorthauls, $rawdata['date'].'shorthauls.json');
  }

  /**
   * Normalizes data mapping linehauls.
   */
  private function maplinehauldata(array $rawdata) {
    $linehauls = [];
    while ($linehaul = current($rawdata)) {
      $key = $linehaul['miles'];
      $linehauls[$key][$linehaul['weight']] = $linehaul['rate'];
      next($rawdata);
    }
    return $linehauls;
  }

  /**
   * Normalizes data mapping shorthauls.
   */
  private function mapshorthauldata(array $rawdata) {
    $shorthauls = [];
    while ($shorthaul = current($rawdata)) {
      $key = $shorthaul['cwt_miles'];
      $value = $shorthaul['rate'];
      $shorthauls[$key] = $value;
      next($rawdata);
    }
    return $shorthauls;
  }

}
