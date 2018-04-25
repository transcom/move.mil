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
    $this->writeJson($rawdata['schedules'], 'service_areas.json');
    // Write linehauls.json
    $linehauls = $this->maplinehauldata($rawdata['linehauls']);
    $this->writeJson($linehauls, 'linehauls.json');
  }

  /**
   * Normalizes data mapping linehauls.
   */
  private function maplinehauldata(array $rawdata) {
    $linehauls = [];
    while ($linehaul = current($rawdata)) {
      $key = $linehaul['miles'];
      $value = [$linehaul['weight'] => $linehaul['rate']];
      $linehauls[$key][] = $value;
      next($rawdata);
    }
    return $linehauls;
  }

}
