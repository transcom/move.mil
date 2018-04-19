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
    if (!is_file($filename)) {
      throw new \RuntimeException(sprintf('File "%s" does not exist.', $csvFile));
    }
    if (!is_readable($filename)) {
      throw new \RuntimeException(sprintf('File "%s" cannot be read.', $csvFile));
    }
    $csv = array_map('str_getcsv', file($csvFile));
    return $csv;
  }

}
