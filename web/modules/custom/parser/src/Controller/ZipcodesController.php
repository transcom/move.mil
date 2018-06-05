<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
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
      'id' => t('id'),
      'code' => t('code'),
      'city' => t('city'),
      'state' => t('state'),
      'lat' => t('lat'),
      'lon' => t('lon'),

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
      '#empty' => t('Nothing here'),
    ];
    $table['pager'] = ['#type' => 'pager'];
    return $table;
  }

}
