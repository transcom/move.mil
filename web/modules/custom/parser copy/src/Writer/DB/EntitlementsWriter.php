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
  public function write(array $rawdata) {
    $table = 'parser_entitlements';
    $entitlements = $this->mapdata($rawdata);
    return $this->insertToTable($entitlements, $table);
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
