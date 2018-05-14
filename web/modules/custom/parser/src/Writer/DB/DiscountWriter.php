<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;
use Drupal\Console\Core\Style\DrupalStyle;

/**
 * Class DiscountWriter.
 *
 * Parse a given array and saves it in a custom table.
 */
class DiscountWriter implements WriterInterface {
  use DBWriter;

  private $file = NULL;

  /**
   * DiscountWriter constructor.
   *
   * Takes the filename, extracts the date from it and stres it as and
   * attribute.
   *
   * @param string $file
   *   String containing the filename.
   */
  public function __construct($file) {
    $this->file = $file;
  }

  /**
   * Normalizes data then writes zip5s table.
   */
  public function write(array $rawdata, $truncate, DrupalStyle $io) {
    $table = 'parser_discounts';
    if ($truncate) {
      $io->info("Truncating {$table} table.");
      $this->truncateTable($table);
    }
    $discounts = $this->mapdata($rawdata);
    $io->info("Writing new records on {$table} table.");
    $this->insertToTable($discounts, $table);
  }

  /**
   * Normalizes data mapping discounts with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $headers = $this->discountHeaders();
    $discounts = [];

    foreach ($rawdata as $key => $data) {
      $phpDate = substr($this->file[$key], 10, 9);
      array_shift($data);

      foreach ($data as $row) {
        $row[] = strtotime($phpDate);
        array_push($discounts, array_combine($headers, $row));
      }
    }
    return $discounts;
  }

  /**
   * Returns an array containg the discounts headers.
   */
  private function discountHeaders() {
    return [
      'origin',
      'destination',
      'discounts',
      'site_rate',
      'tdl',
    ];
  }

}
