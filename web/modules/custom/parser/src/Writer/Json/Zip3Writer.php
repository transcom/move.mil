<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\Writer;
use Drupal\parser\Writer\Json\JsonWriter;

/**
 * Class Zip3Writer
 *
 * Parse a given array and returns a JSON structure
 */

class Zip3Writer implements Writer {
  use JsonWriter;

  public function write(array $rawdata) {
    $zip3s = $this->mapdata($rawdata);
    $this->writeJson($zip3s, 'zip3.json'); 
  }

  private function mapdata(array $rawdata) {
    $zip3s = array();
    while ($zip3 = current($rawdata)) {
      $zip3s[$zip3[0]] = array_combine($this->zip3_headers(),$zip3);
      next($rawdata);
    }
    return $zip3s;
  }

  private function zip3_headers() {
    return [
      'zip3',
      'basepoint_city',
      'state',
      'service_area',
      'rate_area',
      'region'
    ];
  }
}
