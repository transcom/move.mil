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
    $linehauls = $this->linehauls();
    foreach ($linehauls as $linehaul) {
      $this->insertToDb('parser_linehauls', $linehaul);
    }
    $packunpacks = $this->packunpacks();
    foreach ($packunpacks as $packunpack) {
      $this->insertToDb('parser_packunpacks', $packunpack);
    }
    $discounts = $this->discounts();
    foreach ($discounts as $discount) {
      $this->insertToDb('parser_discounts', $discount);
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
   * Wait for a HTML response.
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
  
  /**
   * Wait for for a form field with specified id|name|label|value to have a specified value
   * @When I wait :seconds seconds until I see :field field contains :value
   */
  public function iWaitSecondsForFieldContains($seconds, $field, $value) {
    $i = 0;
    while ($i < $seconds) {
      try {
        $this->assertSession()->fieldValueEquals($field, $value);
        return;
      }
      catch (ExpectationException $e) {
        ++$i;
        sleep(1);
      }
    }
    $message = "The value '$value' was not found after a $seconds seconds timeout";
    throw new ResponseTextException($message, $this->getSession());
  }
  
  /**
   * Clicks a form field with specified id|name|label|value to ensure it has the focus
   * @When the focus is in field :field
   */
  public function focusField($field) {
    $node = $this->assertSession()->fieldExists($field);
    $node->click();
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
        'state'=> 'VA',
        'service_area'=> '168',
        'rate_area'=> '25',
        'region'=> '10',
      ],
      [
        'zip3'=> '902',
        'basepoint_city'=> '',
        'state'=> 'CA',
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
  
  private function linehauls() {
    return [
      [
        'miles' => '2601',
        'weight' => '17000',
        'rate' => '33616',
        'year' => '2018',
      ],
    ];
  }
  
  private function packunpacks() {
    return [
      [
        'schedule' => '3',
        'cwt' => '16001',
        'pack' => '70.96',
        'unpack' => '0.00000',
        'year' => '2018',
      ],
      [
        'schedule' => '3',
        'cwt' => '0',
        'pack' => '67.14',
        'unpack' => '7.04970',
        'year' => '2018',
      ],
    ];
  }
  
  private function discounts() {
    return [
      [
        'origin' => 'US25',
        'destination' => 'REGION 2',
        'discounts' => '67',
        'site_rate' => '60',
        'tdl' => '1541044800',
      ],
    ];
  }

}
