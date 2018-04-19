<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\Writer;

/**
 * Class Zip3Writer
 *
 * Parse a given array and returns a JSON structure
 */

class Zip3Writer implements Writer {

  public function generateJson(array $rawdata) {
    $zip3s = $this->hashjson($rawdata);
    $json = "'finished':'success'";
    return $json;
  }

  private function hashjson(array $rawdata) {
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
