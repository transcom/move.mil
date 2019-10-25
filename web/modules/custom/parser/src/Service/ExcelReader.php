<?php

namespace Drupal\parser\Service;

use PhpOffice\PhpSpreadsheet\Reader\Xlsx;

/**
 * Class ExcelReader.
 *
 * Parses a given xlsx file and returns an array.
 */
class ExcelReader {

  /**
   * Parses excel file with PhpOffice\PhpSpreadsheet.
   */
  public function parse($read_info) {
    $type = $read_info[0];
    $date = $read_info[1];
    $xlsxFile = $read_info[2];
    $xlsx = [];
    $reader = new Xlsx();
    $reader->setReadDataOnly(TRUE);
    $reader->setReadEmptyCells(FALSE);

    if ($type == "discounts") {

      $spreadsheet = $reader->load($xlsxFile)->getActiveSheet();
      $lowestRow = 2;
      $highestRow = $spreadsheet->getHighestRow();
      for ($row_number = $lowestRow; $row_number <= $highestRow; $row_number++) {
        $row = [
          $spreadsheet->getCellByColumnAndRow(1, $row_number)->getValue(),
          $spreadsheet->getCellByColumnAndRow(2, $row_number)->getValue(),
          $spreadsheet->getCellByColumnAndRow(3, $row_number)->getValue(),
          $spreadsheet->getCellByColumnAndRow(4, $row_number)->getValue(),
          $date,
        ];
        $xlsx[] = $row;

      }
    }
    else {
      $xlsx['year'] = $date;

      $filterSubset = new ReadFilter(3, 999, range('A', 'H'));
      $schedulesReader = $reader->setLoadSheetsOnly('Geographical Schedule')->setReadFilter($filterSubset);
      $schedulesData = $schedulesReader->load($xlsxFile);
      $xlsx['schedules'] = $this->schedules($schedulesData);

      $conusparams = $this->conusparams();
      $filterSubset = new ReadFilter(1, $conusparams['highestRow'] + 1, range('A', $conusparams['highestColumn']));
      $linehaulsSheet = $reader->setLoadSheetsOnly('Linehaul')->setReadFilter($filterSubset);
      $linehaulsData = $linehaulsSheet->load($xlsxFile);
      $xlsx['linehauls'] = $this->linehauls($linehaulsData, $conusparams);

      $filterSubset = new ReadFilter(3, 100, range('A', 'F'));
      $additionalSheet = $reader->setLoadSheetsOnly('Additional Rates')->setReadFilter($filterSubset);
      $additionalData = $additionalSheet->load($xlsxFile);
      $additonalrates = $this->additionalrates($additionalData);
      $xlsx['shorthauls'] = $additonalrates['shorthauls'];
      $xlsx['packunpack'] = $additonalrates['packunpack'];
    }
    return $xlsx;
  }

  /**
   * Return schedules data.
   */
  private function schedules($spreadsheet) {
    $schedules = [];
    $lowestRow = 3;
    $worksheet = $spreadsheet->getActiveSheet();
    $highestRow = $worksheet->getHighestRow();
    // Get service area data from the first 5 columns of each row.
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
    $worksheet = $spreadsheet->getActiveSheet();

    $lowestRow = $params['lowestRow'];
    $highestRow = $params['highestRow'];
    $lowestColumn = $params['lowestColumn'];
    $highestColumn = $params['highestColumn'];
    $maxDistance = $params['maxDistance'];
    $latestRates = [];
    // Get miles from column 2, weight from row 2, and rate from col, row.
    for ($row = $lowestRow; $row <= $highestRow; $row++) {
      $linehaul = [];
      $miles = $worksheet->getCellByColumnAndRow(2, $row)->getValue();
      for ($col = $lowestColumn; $col <= $highestColumn; $col++) {
        $weight = $worksheet->getCellByColumnAndRow($col, 2)->getValue();
        $rate = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
        $linehaul['miles'] = $miles;
        $linehaul['weight'] = $weight;
        $linehaul['rate'] = $rate;
        $linehauls[] = $linehaul;
        if ($row == $highestRow) {
          $latestRates[$col]['weight'] = $weight;
          $latestRates[$col]['rate'] = $rate;
        }
      }
    }
    // Increment rates for each addl 100 miles.
    // Get increment for each weight from yellow (additonal rates) row.
    $incrementRow = $highestRow + 1;
    $rateIncrements = [];
    for ($col = $lowestColumn; $col <= $highestColumn; $col++) {
      $rateIncrements[$col] = intval($worksheet->getCellByColumnAndRow($col, $incrementRow)->getValue());
    }
    // Calculate additional rates up to maxDistance in 100 miles increments.
    $lastCount = count($linehauls);
    $incrementInMiles = 100;
    $startAddlMiles = $linehauls[$lastCount - 1]['miles'] + $incrementInMiles;
    $incrementCount = 1;
    foreach (range($startAddlMiles, $maxDistance, $incrementInMiles) as $miles) {
      for ($col = $lowestColumn; $col <= $highestColumn; $col++) {
        $linehaul['miles'] = strval($miles);
        $linehaul['weight'] = $latestRates[$col]['weight'];
        // Get the rate for the current miles and weight.
        $linehaul['rate'] = $latestRates[$col]['rate'] + ($rateIncrements[$col] * $incrementCount);
        $linehauls[] = $linehaul;
      }
      $incrementCount++;
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
    $worksheet = $spreadsheet->getActiveSheet();
    foreach ($worksheet->getRowIterator() as $row) {
      if ($worksheet->getCellByColumnAndRow(1, $row->getRowIndex())->getValue() == 999) {
        $shorthauls[] = $this->shorthaul($worksheet, $row);
      }
      if ($worksheet->getCellByColumnAndRow(2, $row->getRowIndex())->getValue() == '105A') {
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
    }
    elseif (preg_match("/between (?<cwtm>[\d,]+)/", $rawcwtm, $groups)) {
      // Remove coma.
      $cwtm = str_replace(',', '', $groups['cwtm']);
    }
    elseif (preg_match("/greater than (?<cwtm>[\d,]+)/", $rawcwtm, $groups)) {
      // Add 1 and return value to string.
      $cwtm = strval(intval(str_replace(',', '', $groups['cwtm'])) + 1);
    }
    else {
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
    // Get pack and unpack schedule.
    $schedule = $worksheet->getCellByColumnAndRow(3, $row->getRowIndex())->getValue();
    // Get pack weights on cwt.
    $rawcwt = $worksheet->getCellByColumnAndRow(4, $row->getRowIndex())->getValue();
    if (preg_match("/lbs and under/", $rawcwt)) {
      $cwt = 0;
    }
    elseif (preg_match("/(?<cwt>\d+) lbs to/", $rawcwt, $groups)) {
      $cwt = $groups['cwt'];
    }
    elseif (preg_match("/over (?<cwt>\d+) lbs/", $rawcwt, $groups)) {
      // Add 1 and return value to string.
      $cwt = strval(intval($groups['cwt']) + 1);
    }
    else {
      throw new \RuntimeException('Excel file cannot be read.');
    }
    // Get pack charge for this schedule and cwt.
    $rate = $worksheet->getCellByColumnAndRow(5, $row->getRowIndex())->getValue();
    // Get unpack charge for this schedule.
    $rawunpack = $worksheet->getCellByColumnAndRow(6, $row->getRowIndex())->getValue();
    preg_match("/Unpack is (?<unpack>[\d\.]+) /", $rawunpack, $groups);
    $unpack = array_key_exists('unpack', $groups) ? $groups['unpack'] : 0;
    // Add parsed values to pack and unpack array.
    $packunpack['schedule'] = $schedule;
    $packunpack['cwt'] = $cwt;
    $packunpack['pack'] = $rate;
    $packunpack['unpack'] = $unpack;
    return $packunpack;
  }

}

use PhpOffice\PhpSpreadsheet\Reader\IReadFilter;

/**
 * Define the Read Filter class.
 */
class ReadFilter implements IReadFilter {
  private $startRow = 0;
  private $endRow   = 0;
  private $columns  = [];

  /**
   * Get the list of rows and columns to read.
   */
  public function __construct($startRow, $endRow, $columns) {
    $this->startRow = $startRow;
    $this->endRow   = $endRow;
    $this->columns  = $columns;
  }

  /**
   * {@inheritdoc}
   */
  public function readCell($column, $row, $worksheetName = '') {
    // Only read the rows and columns that were configured.
    if ($row >= $this->startRow && $row <= $this->endRow) {
      if (in_array($column, $this->columns)) {
        return TRUE;
      }
    }
    return FALSE;
  }

}
