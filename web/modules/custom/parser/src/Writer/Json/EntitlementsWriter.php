<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\WriterInterface;

/**
 * Class EntitlementsWriter.
 *
 * Parses a given array and returns a JSON structure.
 */
class EntitlementsWriter implements WriterInterface {

  /**
   * Writes entitlements.json.
   */
  public function write(array $rawdata) {
    $json = "'finished':'success'";
    return $json;
  }

}
