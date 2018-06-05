<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection as Connection;

/**
 * Class DiscountsController.
 */
class DiscountsController extends ControllerBase {

  private $databaseConnection;

  /**
   * Constructs a DiscountsController.
   *
   * @param \Drupal\Core\Database\Connection $databaseConnection
   *   A Database Connection object.
   */
  public function __construct(Connection $databaseConnection) {
    $this->databaseConnection = $databaseConnection;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * Get all discounts from the DB.
   */
  public function fetchDiscounts() {
    $discounts = $this->databaseConnection
      ->select('parser_discounts')
      ->fields('parser_discounts')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10)
      ->execute()
      ->fetchAll();
    return (array) $discounts;
  }
  
  /**
   * Get all discounts in a Drupal 8 table.
   */
  public function table() {
    $entries = $this->fetchDiscounts();
    $header = [
      'id' => t('id'),
      'origin' => t('origin'),
      'destination' => t('destination'),
      'discounts' => t('discounts'),
      'site_rate' => t('site_rate'),
      'tdl' => t('tdl'),
    ];
    // Initialize an empty array.
    $output = [];
    // Next, loop through the $entries array.
    foreach ($entries as $entry) {
      if ($entry->id != 0) {
        $output[$entry->id] = [
          'id' => $entry->id,
          'origin' => $entry->origin,
          'destination' => $entry->destination,
          'discounts' => $entry->discounts,
          'site_rate' => $entry->site_rate,
          'tdl' => $entry->tdl,
        ];
      }
    }
    $table['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $output,
      '#empty' => t('Nothing here'),
    ];
    $table['pager'] = ['#type' => 'pager'];
    return $table;
  }

}
