<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;
use Drupal\Console\Core\Style\DrupalStyle;

/**
 * Class Zip5Writer.
 *
 * Parse a given array and saves it in a custom table.
 */
class Zip5Writer implements WriterInterface {
  use DBWriter;

  /**
   * Normalizes data then writes zip5s table.
   */
  public function write(array $rawdata, $truncate, DrupalStyle $io) {
    $table = 'parser_zip5s';
    if ($truncate) {
      $io->info("Truncating {$table} table.");
      $this->truncateTable($table);
    }
    $zip5s = $this->mapdata($rawdata);
    $io->info("Writing new records on {$table} table.");
    $this->insertToTable($zip5s, $table);
  }

  /**
   * Normalizes data mapping zip5 code with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $zip5s = [];
    while ($zip5 = current($rawdata)) {
      $zip5s[] = array_combine($this->zip5headers(), $zip5);
      next($rawdata);
    }
    return $zip5s;
  }

  /**
   * Returns the zip5 headers.
   */
  private function zip5headers() {
    return [
      'zip5',
      'service_area',
    ];
  }

}
