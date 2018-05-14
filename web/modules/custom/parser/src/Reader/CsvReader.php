<?php

namespace Drupal\parser\Reader;

/**
 * Class CsvReader.
 *
 * Parses a given csv file and returns an array.
 */
class CsvReader implements ReaderInterface {

  /**
   * Parses csv file with php function str_getcsv.
   */
  public function parse($csvFile) {
    return array_map(function ($file) {

      if (!is_file($file)) {
        throw new \RuntimeException(sprintf('File "%s" does not exist.', $file));
      }
      if (!is_readable($file)) {
        throw new \RuntimeException(sprintf('File "%s" cannot be read.', $file));
      }
      $csv = array_map('str_getcsv', file($file));
      return $csv;
    }, $csvFile);
  }

}
