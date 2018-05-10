<?php

namespace Drupal\parser\Reader;

/**
 * Class LocationReader.
 *
 *
 */
class LocationReader implements ReaderInterface {

  /**
   * Parses location files.
   */
  public function parse($input) {
    return array_map(function ($file) {
      return $rawdata = file_get_contents("{$file}");
    },$input);
  }

}
