<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\WriterInterface;

/**
 * Class Rates400NGWriter.
 *
 * Parses a given array and returns a JSON structure.
 */
class Rates400NGWriter implements WriterInterface {

  /**
   * Writes json files.
   */
  public function write(array $rawdata) {
    $json = "'finished':'success'";
    return $json;
  }

}
