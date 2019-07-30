<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection as Connection;

/**
 * Class Zip3Controller.
 */
class Zip3Controller extends ControllerBase {

  /**
   * Drupal\Core\Database\Connection definition.
   *
   * @var \Drupal\Core\Database\Connection
   */
  private $databaseConnection;

  /**
   * Constructs a Zip3Controller.
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
   * Get all zip3s from the DB.
   */
  public function getAll() {
    $zip3 = $this->databaseConnection
      ->select('parser_zip3s')
      ->fields('parser_zip3s')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10)
      ->execute()
      ->fetchAll();
    return (array) $zip3;
  }

  /**
   * Get all zip3s in a Drupal 8 table.
   */
  public function table() {
    $entries = $this->getAll();
    $header = [
      'id' => $this->t('id'),
      'zip3' => $this->t('zip3'),
      'basepoint_city' => $this->t('basepoint_city'),
      'state' => $this->t('state'),
      'service_area' => $this->t('service_area'),
      'rate_area' => $this->t('rate_area'),
      'region' => $this->t('region'),

    ];
    // Initialize an empty array.
    $output = [];
    // Next, loop through the $entries array.
    foreach ($entries as $entry) {
      if ($entry->id != 0) {
        $output[$entry->id] = [
          'id' => $entry->id,
          'zip3' => $entry->zip3,
          'basepoint_city' => $entry->basepoint_city,
          'state' => $entry->state,
          'service_area' => $entry->service_area,
          'rate_area' => $entry->rate_area,
          'region' => $entry->region,
        ];
      }
    }
    $table['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $output,
      '#empty' => $this->t('Nothing here'),
    ];
    $table['pager'] = ['#type' => 'pager'];
    return $table;
  }

}
