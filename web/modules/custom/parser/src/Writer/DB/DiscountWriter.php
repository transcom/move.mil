<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;
use Drupal\Console\Core\Style\DrupalStyle;

/**
 * Class DiscountWriter.
 *
 * Parse a given array and saves it in a custom table.
 */
class DiscountWriter implements WriterInterface{
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
    $this->file = str_replace("No 1 BVS Dom Discounts - Eff ", "", $file);
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
   * Normalizes data mapping zip5 code with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $discounts = [];
    array_shift($rawdata);
    $formatteddata = array_map(function ($record) {
      array_push($record, strtotime($this->file));
      return $record;
    }, $rawdata);

    var_dump($formatteddata);

    while ($discount = current($formatteddata)) {
      $discounts[] = array_combine($this->discountHeaders(), $discount);
      next($formatteddata);
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
      'tlb'
    ];
  }

}

