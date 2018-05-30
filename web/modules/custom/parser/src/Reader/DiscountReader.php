<?php

namespace Drupal\parser\Reader;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * Class DiscountReader.
 *
 * Parses a given xlsx file and returns an array.
 */
class DiscountReader implements ReaderInterface {

  /**
   * Parses excel file with PhpOffice\PhpSpreadsheet.
   */
  public function parse($xlsxFile) {
    $xlsx = [];
    $reader = new Xlsx();
    $reader->setReadDataOnly(TRUE);
    $spreadsheet = $reader->load($xlsxFile)->getActiveSheet();
    $lowestRow = 2;
    $highestRow = $spreadsheet->getHighestRow();
    for ($row_nr = $lowestRow; $row_nr <= $highestRow; $row_nr++) {
      $row = [
        $spreadsheet->getCellByColumnAndRow(1, $row_nr)->getValue(),
        $spreadsheet->getCellByColumnAndRow(2, $row_nr)->getValue(),
        $spreadsheet->getCellByColumnAndRow(3, $row_nr)->getValue(),
        $spreadsheet->getCellByColumnAndRow(4, $row_nr)->getValue(),
      ];
      array_push($xlsx, $row);
    }

    return $xlsx;
  }

}
