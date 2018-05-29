<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;
use Drupal\Console\Core\Style\DrupalStyle;

/**
 * Class CsvWriter.
 *
 * Parse a given array and saves it in a custom table.
 */
class CsvWriter implements WriterInterface {
  use DBWriter;

  private $file = NULL;

  /**
   * CsvWriter constructor.
   *
   * @param string $file
   *   String containing the filename.
   */
  public function __construct($file) {
    $this->file = $file;
  }

  /**
   * Normalizes data then writes it into db tables.
   */
  public function write(array $rawdata) {
    $table = ($this->file == 'zip_3' ? 'parser_zip3s' : 'parser_zip5s');
    $zips = $this->mapdata($rawdata);
    return $this->insertToTable($zips, $table);
  }

  /**
   * Normalizes data mapping with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $zips = [];
    $headers = $this->zipHeaders();
    while ($zip = current($rawdata)) {
      $zips[] = array_combine($headers, $zip);
      next($rawdata);
    }
    return $zips;
  }

  /**
   * Returns the corresponding headers of the chosen file.
   */
  private function zipHeaders() {
    if ($this->file == 'zip_3') {
      return [
        'zip3',
        'basepoint_city',
        'state',
        'service_area',
        'rate_area',
        'region',
      ];
    }
    return [
      'zip5',
      'service_area',
    ];
  }

}
