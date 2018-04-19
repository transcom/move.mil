<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\Writer;

/**
 * Class _400NGWriter
 *
 * Parse a given array and returns a JSON structure
 */

class _400NGWriter implements Writer {

  public function generateJson(array $rawdata) {
    $json = "'finished':'success'";
    return $json;
  }
}
