<?php

use Drupal\DrupalExtension\Context\RawDrupalContext;

/**
 * Defines general application features used by other feature files.
 *
 * @codingStandardsIgnoreStart
 */
class FeatureContext extends RawDrupalContext {
  
  
  /**
   * @Given there is an entitlement :entitlement
   */
  public function thereIsAnEntitlement($entitlement) {
    $connection = \Drupal::service('database');
    $record = $this->entitlements()[$entitlement];
    $connection
      ->insert('parser_entitlements')
      ->fields($record)
      ->execute();
  }
  
  private function entitlements() {
    return [
      'service-academy-cadet-midshipma' => [
        'rank'=> 'Service Academy Cadet/Midshipman',
        'total_weight_self'=> '350',
        'total_weight_self_plus_dependents'=> '350',
        'pro_gear_weight'=> '0',
        'pro_gear_weight_spouse'=> '0',
        'slug'=> 'service-academy-cadet-midshipman',
        ],
      'aviation-cadet'=> [
        'rank'=> 'Aviation Cadet',
        'total_weight_self'=> '7000',
        'total_weight_self_plus_dependents'=> '8000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'aviation-cadet',
        ],
      'e-1'=> [
        'rank'=> 'E-1',
        'total_weight_self'=> '5000',
        'total_weight_self_plus_dependents'=> '8000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'e-1',
        ],
      'e-2'=> [
        'rank'=> 'E-2',
        'total_weight_self'=> '5000',
        'total_weight_self_plus_dependents'=> '8000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'e-2',
        ],
      'e-3'=> [
        'rank'=> 'E-3',
        'total_weight_self'=> '5000',
        'total_weight_self_plus_dependents'=> '8000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'e-3',
        ],
      'e-4'=> [
        'rank'=> 'E-4',
        'total_weight_self'=> '7000',
        'total_weight_self_plus_dependents'=> '8000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'e-4',
        ],
      'e-5'=> [
        'rank'=> 'E-5',
        'total_weight_self'=> '7000',
        'total_weight_self_plus_dependents'=> '9000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'e-5',
        ],
      'e-6'=> [
        'rank'=> 'E-6',
        'total_weight_self'=> '8000',
        'total_weight_self_plus_dependents'=> '11000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'e-6',
        ],
      'e-7'=> [
        'rank'=> 'E-7',
        'total_weight_self'=> '11000',
        'total_weight_self_plus_dependents'=> '13000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'e-7',
        ],
      'e-8'=> [
        'rank'=> 'E-8',
        'total_weight_self'=> '12000',
        'total_weight_self_plus_dependents'=> '14000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'e-8',
        ],
      'e-9'=> [
        'rank'=> 'E-9',
        'total_weight_self'=> '13000',
        'total_weight_self_plus_dependents'=> '15000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'e-9',
        ],
      'o-1-w-1-service-academy-graduate'=> [
        'rank'=> 'O-1/W-1/Service Academy Graduate',
        'total_weight_self'=> '10000',
        'total_weight_self_plus_dependents'=> '12000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-1-w-1-service-academy-graduate',
        ],
      'o-2-w-2'=> [
        'rank'=> 'O-2/W-2',
        'total_weight_self'=> '12500',
        'total_weight_self_plus_dependents'=> '13500',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-2-w-2',
        ],
      'o-3-w-3'=> [
        'rank'=> 'O-3/W-3',
        'total_weight_self'=> '13000',
        'total_weight_self_plus_dependents'=> '14500',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-3-w-3',
        ],
      'o-4-w-4'=> [
        'rank'=> 'O-4/W-4',
        'total_weight_self'=> '14000',
        'total_weight_self_plus_dependents'=> '17000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-4-w-4',
        ],
      'o-5-w-5'=> [
        'rank'=> 'O-5/W-5',
        'total_weight_self'=> '16000',
        'total_weight_self_plus_dependents'=> '17500',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-5-w-5',
        ],
      'o-6'=> [
        'rank'=> 'O-6',
        'total_weight_self'=> '18000',
        'total_weight_self_plus_dependents'=> '18000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-6',
        ],
      'o-7'=> [
        'rank'=> 'O-7',
        'total_weight_self'=> '18000',
        'total_weight_self_plus_dependents'=> '18000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-7',
        ],
      'o-8'=> [
        'rank'=> 'O-8',
        'total_weight_self'=> '18000',
        'total_weight_self_plus_dependents'=> '18000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-8',
        ],
      'o-9'=> [
        'rank'=> 'O-9',
        'total_weight_self'=> '18000',
        'total_weight_self_plus_dependents'=> '18000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-9',
        ],
      'o-10'=> [
        'rank'=> 'O-10',
        'total_weight_self'=> '18000',
        'total_weight_self_plus_dependents'=> '18000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'o-10',
        ],
      'civilian-employee'=> [
        'rank'=> 'Civilian Employee',
        'total_weight_self'=> '18000',
        'total_weight_self_plus_dependents'=> '18000',
        'pro_gear_weight'=> '2000',
        'pro_gear_weight_spouse'=> '500',
        'slug'=> 'civilian-employee',
        ],
      ];
  }

}
