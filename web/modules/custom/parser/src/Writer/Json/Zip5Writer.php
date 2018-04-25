<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\WriterInterface;

/**
 * Class Zip5Writer.
 *
 * Parse a given array and returns a JSON structure.
 */
class Zip5Writer implements WriterInterface {
  use JsonWriter;

  /**
   * Normalizes data then writes zip5.json.
   */
  public function write(array $rawdata) {
    $zip5s = $this->mapdata($rawdata);
    $this->writeJson($zip5s, 'zip5.json');
  }

  /**
   * Normalizes data mapping zip5 code with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $zip5s = [];
    while ($zip5 = current($rawdata)) {
      $zip5s[$zip5[0]] = array_combine($this->zip5headers(), $zip5);
      next($rawdata);
    }
    return $zip5s;
  }

  /**
   * Returns the zip5 headers.
   */
  private function zip5headers() {
    return [
      'zip5',
      'service_area',
    ];
  }

}
