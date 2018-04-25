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
    $this->writeJson($rawdata['schedules'], 'service_areas.json');
  }

}
