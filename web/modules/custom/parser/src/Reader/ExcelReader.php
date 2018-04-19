<?php

namespace Drupal\parser\Reader;

use Drupal\parser\Reader\ReaderInterface;

/**
 * Class ExcelReader.
 *
 * Parses a given xlsx file and returns an array.
 */
class ExcelReader implements ReaderInterface {

  /**
   * Parses csv file with TBD.
   */
  public function parse($xlsxFile) {
    echo "getting array for xlsx \n";
    $xlsx = [];
    return $xlsx;
  }

}
