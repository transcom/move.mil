<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;
use Drupal\Console\Core\Style\DrupalStyle;

/**
 * Class ZipCodesWriter.
 *
 * Parse a given array and saves it in a custom table.
 */
class ZipCodesWriter implements WriterInterface {
  use DBWriter;

  private $file = NULL;

  /**
   * ZipCodesWriter constructor.
   *
   * @param string $file
   *   String containing the filename.
   */
  public function __construct($file) {
    $this->file = $file;
  }

  /**
   * Normalizes data then writes zipcodes table.
   */
  public function write(array $rawdata, $truncate, DrupalStyle $io) {
    $table = 'parser_zipcodes';
    if ($truncate) {
      $io->info("Truncating {$table} table.");
      $this->truncateTable($table);
    }
    $codes = $this->mapdata($rawdata);
    $io->info("Writing new records on {$table} table.");
    $this->insertToTable($codes, $table);
  }

  /**
   * Normalizes data mapping codes with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $codes = [];
    // Remove first headers row.
    array_shift($rawdata);
    $headers = $this->codeHeaders();
    while ($code = current($rawdata)) {
      $code_filtered = array_filter($code, function ($k) {
        // Skip county (3), and area_code(4) values.
        return $k != 3 && $k != 4;
      }, ARRAY_FILTER_USE_KEY);
      $code_with_headers = array_combine($headers, $code_filtered);
      if ($code_with_headers['lat'] == '') {
        $code_with_headers['lat'] = 0;
      }
      if ($code_with_headers['lon'] == '') {
        $code_with_headers['lon'] = 0;
      }
      $codes[] = $code_with_headers;
      next($rawdata);
    }
    return $codes;
  }

  /**
   * Returns an array containing the zipcodes headers.
   */
  private function codeHeaders() {
    return [
      'code',
      'city',
      'state',
      'lat',
      'lon',
    ];
  }

}
