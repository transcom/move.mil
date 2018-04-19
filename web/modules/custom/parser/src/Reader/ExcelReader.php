<?php

namespace Drupal\parser\Reader;

use Drupal\parser\Reader\Reader;

/**
 * Class ExcelReader
 *
 * Parse a given xlsx file and returns an array
 */

class CsvReader implements Reader{

  public function parse($xlsxFile) {
    echo "getting array for xlsx \n";
    $xlsx = array();
    return $xlsx;
  }
}
