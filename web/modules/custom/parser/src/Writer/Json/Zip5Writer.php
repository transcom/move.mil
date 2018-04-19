<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\Writer;

/**
 * Class Zip5Writer
 *
 * Parse a given array and returns a JSON structure
 */

class Zip5Writer implements Writer {

  public function write(array $rawdata) {
    $json = "'finished':'success'";
    return $json;
  }
}
