<?php

namespace Drupal\parser\Reader;

use Drupal\parser\Reader\ReaderInterface;
use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * Class ExcelReader.
 *
 * Parses a given xlsx file and returns an array.
 */
class ExcelReader implements ReaderInterface {

  /**
   * Parses excel file with PhpOffice\PhpSpreadsheet.
   */
  public function parse($xlsxFile) {
    $xlsx = [];
    $reader = new Xlsx();
    $reader->setReadDataOnly(true);
    $reader->setLoadSheetsOnly([
      'Geographical Schedule',
      'Linehaul',
      'Additional Rates',
      ]);
    $spreadsheet = $reader->load($xlsxFile);
    $xlsx['date'] = substr($xlsxFile, -15, 5); // Get year from filename
    $xlsx['schedules'] = $this->schedules($spreadsheet);
    $xlsx['linehauls'] = $this->linehauls($spreadsheet, $this->conusparams());
    $additonalrates = $this->additionalrates($spreadsheet);
    $xlsx['shorthauls'] = $additonalrates['shorthauls'];
    $xlsx['packunpack'] = $additonalrates['packunpack'];
    return $xlsx;
  }

  /**
   * Return schedules data.
   */
  private function schedules($spreadsheet) {
    $schedules = [];
    $worksheet = $spreadsheet->getSheetByName('Geographical Schedule');
    $lowestRow = 3;
    $highestRow = $worksheet->getHighestRow();
    // Get service area data from the first 5 columns of each row
    for ($row = $lowestRow; $row <= $highestRow; $row++) {
      $schedule = [];
      $servicearea = $worksheet->getCellByColumnAndRow(1, $row)->getValue();
      $schedule['service_area'] = $servicearea;
      $schedule['name'] = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
      $schedule['services_schedule'] = $worksheet->getCellByColumnAndRow(3, $row)->getValue();
      $schedule['linehaul_factor'] = $worksheet->getCellByColumnAndRow(4, $row)->getValue();
      $schedule['orig_dest_service_charge'] = $worksheet->getCellByColumnAndRow(5, $row)->getValue();
      $schedules[$servicearea] = $schedule;
    }
    return $schedules;
  }

  /**
   * Return linehauls data.
   */
  private function linehauls($spreadsheet, array $params) {
    $linehauls = [];
    $worksheet = $spreadsheet->getSheetByName('Linehaul');
    $lowestRow = $params['lowestRow'];
    $highestRow = $params['highestRow'];
    $lowestColumn = $params['lowestColumn'];
    $highestColumn = $params['highestColumn'];
    $maxDistance = $params['maxDistance'];
    // Get miles from column 2, weight from row 2, and rate from col, row
    for ($row = $lowestRow; $row <= $highestRow; $row++) {
      $linehaul = [];
      $miles = $rate = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
      for ($col = $lowestColumn; $col <= $highestColumn; $col++) {
        $weight = $worksheet->getCellByColumnAndRow($col, 2)->getValue();
        $rate = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
        $linehaul['miles'] = $miles;
        $linehaul['weight'] = $weight;
        $linehaul['rate'] = $rate;
        $linehauls[] = $linehaul;
      }
    }
    return $linehauls;
  }

  /**
   * Return conus linehauls params.
   */
  private function conusparams() {
    return [
      'lowestRow' => 4,
      'highestRow' => 57,
      'lowestColumn' => 5,
      'highestColumn' => 98,
      'maxDistance' => 6000,
    ];
  }

  /**
   * Return additionalrates data.
   */
  private function additionalrates($spreadsheet) {
    $additonalrates = [];
    $shorthauls = [];
    $packunpack = [];
    $worksheet = $spreadsheet->getSheetByName('Additional Rates');
    foreach ($worksheet->getRowIterator() as $row) {
      if($worksheet->getCellByColumnAndRow(1, $row->getRowIndex())->getValue() == 999) {
        $shorthauls[] = $this->shorthaul($worksheet, $row);
      }
      if($worksheet->getCellByColumnAndRow(2, $row->getRowIndex())->getValue() == '105A') {
        $packunpack[] = $this->packunpack($worksheet, $row);
      }
    }
    $additonalrates['shorthauls'] = $shorthauls;
    $additonalrates['packunpack'] = $packunpack;
    return $additonalrates;
  }

  /**
   * Return shorthaul data.
   */
  private function shorthaul($worksheet, $row) {
    $shorthaul = [];
    $rawcwtm = $worksheet->getCellByColumnAndRow(4, $row->getRowIndex())->getValue();
    if (preg_match("/less than or equal to/", $rawcwtm)) {
      $cwtm = "0";
    } else if (preg_match("/between (?<cwtm>[\d,]+)/", $rawcwtm, $groups)) {
      $cwtm = str_replace(',', '', $groups['cwtm']); // remove coma
    } else if (preg_match("/greater than (?<cwtm>[\d,]+)/", $rawcwtm, $groups)) {
      $cwtm = strval(intval(str_replace(',', '', $groups['cwtm'])) + 1); // add 1 and return value to string
    } else {
      throw new \RuntimeException('Excel file cannot be read.');
    }
    $rate = $worksheet->getCellByColumnAndRow(5, $row->getRowIndex())->getValue();
    $shorthaul['cwt_miles'] = $cwtm;
    $shorthaul['rate'] = $rate;
    return $shorthaul;
  }

  /**
   * Return packunpack data.
   */
  private function packunpack($worksheet, $row) {
    $packunpack = [];
    // Get pack and unpack schedule
    $schedule = $worksheet->getCellByColumnAndRow(3, $row->getRowIndex())->getValue();
    // Get pack weights on cwt
    $rawcwt = $worksheet->getCellByColumnAndRow(4, $row->getRowIndex())->getValue();
    if (preg_match("/lbs and under/", $rawcwt)) {
      $cwt = 0;
    } else if (preg_match("/(?<cwt>\d+) lbs to/", $rawcwt, $groups)) {
      $cwt = $groups['cwt'];
    } else if (preg_match("/over (?<cwt>\d+) lbs/", $rawcwt, $groups)) {
      $cwt = strval(intval($groups['cwt']) + 1); // add 1 and return value to string
    } else {
      throw new \RuntimeException('Excel file cannot be read.');
    }
    // Get pack charge for this schedule and cwt
    $rate = $worksheet->getCellByColumnAndRow(5, $row->getRowIndex())->getValue();
    // Get unpack charge for this schedule
    $rawunpack = $worksheet->getCellByColumnAndRow(6, $row->getRowIndex())->getValue();
    preg_match("/Unpack is (?<unpack>[\d\.]+) /", $rawunpack, $groups);
    $unpack = $groups['unpack'];
    // Add parsed values to pack and unpack array
    $packunpack['schedule'] = $schedule;
    $packunpack['cwt'] = $cwt;
    $packunpack['rate'] = $rate;
    $packunpack['unpack'] = $unpack;
    return $packunpack;
  }

}
