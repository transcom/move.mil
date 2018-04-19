<?php

namespace Drupal\parser\Reader;

use Drupal\parser\Reader\Reader;

/**
 * Class CsvReader
 *
 * Parse a given csv file and returns an array
 */

class CsvReader implements Reader {

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
