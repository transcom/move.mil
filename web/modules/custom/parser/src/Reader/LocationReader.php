<?php

namespace Drupal\parser\Reader;

/**
 * Class LocationReader.
 *
 * Parses the given json files and returns an array of arrays.
 */
class LocationReader {

  /**
   * Parses location files.
   */
  public function parse($input) {
    return array_map(function ($file) {
      if (!is_file($file)) {
        throw new \RuntimeException(sprintf('File "%s" does not exist.', $file));
      }
      if (!is_readable($file)) {
        throw new \RuntimeException(sprintf('File "%s" cannot be read.', $file));
      }
      return file_get_contents("{$file}");
    }, $input);
  }

}
