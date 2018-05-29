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

  private $date = NULL;

  /**
   * DiscountWriter constructor.
   *
   * @param string $date
   *   String containing the effective date.
   */
  public function __construct($date) {
    $this->date = $date;
  }

  /**
   * Normalizes data then writes zip5s table.
   */
  public function write(array $rawdata) {
    $table = 'parser_discounts';
    $discounts = $this->mapdata($rawdata);
    $this->insertToTable($discounts, $table);
  }

  /**
   * Normalizes data mapping discounts with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $headers = $this->discountHeaders();
    $discount = [];
    $timestamp = strtotime($this->date);

    foreach ($rawdata as $row) {
      array_push($row, $timestamp);
      array_push($discount, array_combine($headers, $row));
    }
    return $discount;
  }

  /**
   * Returns an array containing the discounts headers.
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
