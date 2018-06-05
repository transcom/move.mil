<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection as Connection;

/**
 * Class ZipcodesController.
 */
class ZipcodesController extends ControllerBase {

  private $databaseConnection;

  /**
   * Constructs a ZipcodesController.
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
   * Get all zipcodes from the DB.
   */
  public function fetchZipcodes() {
    $zipcodes = $this->databaseConnection
      ->select('parser_zipcodes')
      ->fields('parser_zipcodes')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10)
      ->execute()
      ->fetchAll();
    return (array) $zipcodes;
  }

  /**
   * Get all zipcodes in a Drupal 8 table.
   */
  public function table() {
    $entries = $this->fetchZipcodes();
    $header = [
      'id' => $this->t('id'),
      'code' => $this->t('code'),
      'city' => $this->t('city'),
      'state' => $this->t('state'),
      'lat' => $this->t('lat'),
      'lon' => $this->t('lon'),

    ];
    // Initialize an empty array.
    $output = [];
    // Next, loop through the $entries array.
    foreach ($entries as $entry) {
      if ($entry->id != 0) {
        $output[$entry->id] = [
          'id' => $entry->id,
          'code' => $entry->code,
          'city' => $entry->city,
          'state' => $entry->state,
          'lat' => $entry->lat,
          'lon' => $entry->lon,

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
