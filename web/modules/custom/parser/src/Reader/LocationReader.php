<?php

namespace Drupal\parser\Reader;

/**
 * Class LocationReader.
 *
 * Parses the given json files and returns an array of arrays.
 */
class LocationReader implements ReaderInterface {

  /**
   * Parses location files.
   */
  public function parse($input) {
    return array_map(function ($file) {
      return file_get_contents("{$file}");
    }, $input);
  }

}
