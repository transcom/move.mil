<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\Writer;

/**
 * Class EntitlementsWriter
 *
 * Parse a given array and returns a JSON structure
 */

class EntitlementsWriter implements Writer {

  public function write(array $rawdata) {
    $json = "'finished':'success'";
    return $json;
  }
}
