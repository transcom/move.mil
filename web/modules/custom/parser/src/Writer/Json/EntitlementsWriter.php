<?php

namespace Drupal\parser\Writer\Json;

use Drupal\parser\Writer\WriterInterface;

/**
 * Class EntitlementsWriter.
 *
 * Parses a given array and returns a JSON structure.
 */
class EntitlementsWriter implements WriterInterface {
  use JsonWriter;

  /**
   * Normalizes data then writes entitlements.json.
   */
  public function write(array $rawdata) {
    $entitlements = $this->mapdata($rawdata);
    $this->writeJson($entitlements, 'entitlements.json');
  }

  /**
   * Normalizes data mapping entitlements code with the rest of the data.
   */
  private function mapdata(array $rawdata) {
    $entitlements = [];
    while ($entitlement = current($rawdata)) {
      $key = $this->entitlementslug($entitlement['rank']);
      $entitlement['slug'] = $key;
      $entitlements[$key] = $entitlement;
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
