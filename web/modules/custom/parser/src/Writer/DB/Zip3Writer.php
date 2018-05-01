<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;

/**
 * Class Zip3Writer.
 *
 * Parses a given array and saves it in a custom table.
 */
class Zip3Writer implements WriterInterface {
  use DBWriter;

  /**
   * Normalizes data then writes zip3s table.
   */
  public function write(array $rawdata) {
    $zip3s = $this->mapdata($rawdata);
    $this->writetable($zip3s, 'parser_zip3s');
  }

  /**
   * Normalizes data mapping zip3 code with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $zip3s = [];
    while ($zip3 = current($rawdata)) {
      $zip3s[] = array_combine($this->zip3headers(), $zip3);
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
