<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;
use Drupal\Console\Core\Style\DrupalStyle;

/**
 * Class EntitlementsWriter.
 *
 * Parses a given array and saves it in a custom table.
 */
class EntitlementsWriter implements WriterInterface {
  use DBWriter;

  /**
   * Normalizes data then writes it into db tables.
   */
  public function write(array $rawdata, $truncate) {
    $table = 'parser_entitlements';
    if ($truncate) {
      $this->truncateTable($table);
    }
    $entitlements = $this->mapdata($rawdata);
    $this->insertToTable($entitlements, $table);
  }

  /**
   * Normalizes data mapping entitlements code with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $entitlements = [];
    while ($entitlement = current($rawdata)) {
      $entitlement['slug'] = $this->entitlementslug($entitlement['rank']);
      $entitlements[] = $entitlement;
      next($rawdata);
    }
    return $entitlements;
  }

  /**
   * Returns the entitlement slug.
   */
  private function entitlementslug($entitlement) {
    $slug = preg_replace('/[^A-Za-z0-9]+/', '-', $entitlement);
    return strtolower($slug);
  }

}
