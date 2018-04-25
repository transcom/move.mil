<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\WriterInterface;

/**
 * Class Zip3Writer.
 *
 * Parses a given array and returns a JSON structure.
 */
class Zip3Writer implements WriterInterface {
  use JsonWriter;

  /**
   * Normalizes data then writes zip3.json.
   */
  public function write(array $rawdata) {
    $zip3s = $this->mapdata($rawdata);
    $this->writeJson($zip3s, 'zip3.json');
  }

  /**
   * Normalizes data mapping zip3 code with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $zip3s = [];
    while ($zip3 = current($rawdata)) {
      $zip3s[$zip3[0]] = array_combine($this->zip3headers(), $zip3);
      next($rawdata);
    }
    return $zip3s;
  }

  /**
   * Returns the zip3 headers.
   */
  private function zip3headers() {
    return [
      'zip3',
      'basepoint_city',
      'state',
      'service_area',
      'rate_area',
      'region',
    ];
  }

}
