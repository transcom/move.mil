<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\WriterInterface;

/**
 * Class Zip5Writer.
 *
 * Parse a given array and returns a JSON structure.
 */
class Zip5Writer implements WriterInterface {

  /**
   * Writes zip5.json.
   */
  public function write(array $rawdata) {
    $json = "'finished':'success'";
    return $json;
  }

}
