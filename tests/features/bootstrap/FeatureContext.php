<?php

use Drupal\DrupalExtension\Context\RawDrupalContext;
use Behat\Mink\Exception\ExpectationException;
use Behat\Mink\Exception\ResponseTextException;

/**
 * Defines general application features used by other feature files.
 *
 * @codingStandardsIgnoreStart
 */
class FeatureContext extends RawDrupalContext {
  
  private $databaseConnection;

  /**
   * @Given there is an entitlement :entitlement
   */
  public function thereIsAnEntitlement($entitlement) {
    $record = $this->entitlements()[$entitlement];
    $this->insertToDb('parser_entitlements', $record);
  }

  /**
   * @Given there is PPM data
   */
  public function thereIsPpmData() {
    $zip3s = $this->zip3s();
    foreach ($zip3s as $zip3) {
      $this->insertToDb('parser_zip3s', $zip3);
    }
    $zipCodes = $this->zipCodes();
    foreach ($zipCodes as $zipCode) {
      $this->insertToDb('parser_zipcodes', $zipCode);
    }
    $serviceAreas = $this->serviceAreas();
    foreach ($serviceAreas as $serviceArea) {
      $this->insertToDb('parser_service_areas', $serviceArea);
    }
  }
  
  private function insertToDb($table, $record) {
    if (empty($this->databaseConnection)) {
      $this->databaseConnection = \Drupal::service('database');
    }
    $this->databaseConnection
      ->insert($table)
      ->fields($record)
      ->execute();
  }

  /**
   * Wait for a HTTP response.
   *
   * @When I wait :seconds seconds until I get a response with text :text
   */
  public function iWaitSecondsForResponse($seconds, $text) {
    $i = 0;
    while ($i < $seconds) {
      try {
        $this->assertSession()->responseContains($text);
        return;
      }
      catch (ExpectationException $e) {
        ++$i;
        sleep(1);
      }
    }
    $message = "The text '$text' was not found after a $seconds seconds timeout";
    throw new ResponseTextException($message, $this->getSession());
  }

  /**
   * Wait for a element.
   *
   * @When I wait :seconds seconds until I see text :text
   */
  public function iWaitSecondsForElement($seconds, $text) {
    $i = 0;
    while ($i < $seconds) {
      try {
        $this->assertSession()->pageTextContains($text);
        return;
      }
      catch (ResponseTextException $e) {
        ++$i;
        sleep(1);
      }
    }
    $message = "The text '$text' was not found after a $seconds seconds timeout";
    throw new ResponseTextException($message, $this->getSession());
  }

  private function entitlements() {
    return [
      'rank-1'=> [
        'rank'=> 'Rank 1',
        'total_weight_self'=> '5000',
        'total_weight_self_plus_dependents'=> '8000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'rank-1',
      ],
      'rank-2'=> [
        'rank'=> 'Rank 2',
        'total_weight_self'=> '13000',
        'total_weight_self_plus_dependents'=> '14500',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'rank-2',
      ],
      'rank-3'=> [
        'rank'=> 'Rank 3',
        'total_weight_self'=> '18000',
        'total_weight_self_plus_dependents'=> '18000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'rank-3',
      ],
    ];
  }

  private function zip3s() {
    return [
      [
        'zip3'=> '220',
        'basepoint_city'=> '',
        'state'=> '',
        'service_area'=> '168',
        'rate_area'=> '25',
        'region'=> '10',
      ],
      [
        'zip3'=> '902',
        'basepoint_city'=> '',
        'state'=> '',
        'service_area'=> '56',
        'rate_area'=> '88',
        'region'=> '2',
      ],
    ];
  }

  private function zipCodes() {
    return [
      [
        'code' => '22030',
        'city' => 'Fairfax',
        'state' => 'VA',
        'lat' => '38.853231',
        'lon' => '-77.305097',
      ],
      [
        'code' => '90210',
        'city' => 'Beverly Hills',
        'state' => 'CA',
        'lat' => '33.7865940000',
        'lon' => '-118.2986620000',
      ],
    ];
  }
  
  private function serviceAreas() {
    return [
      [
        'service_area' => '168',
        'name' => '',
        'services_schedule' => '3',
        'linehaul_factor' => '2.18',
        'orig_dest_service_charge' => '6.34',
        'year' => '2018',
      ],
      [
        'service_area' => '56',
        'name' => '',
        'services_schedule' => '3',
        'linehaul_factor' => '2.68',
        'orig_dest_service_charge' => '7.75',
        'year' => '2018',
      ],
    ];
  }

}
