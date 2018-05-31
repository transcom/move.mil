<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection as Connection;

/**
 * Class Rates400NGController.
 */
class Rates400NGController extends ControllerBase {

  private $databaseConnection;

  /**
   * Constructs a Rates400NGController.
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
   * Get all service areas from the DB.
   */
  public function fetchServiceAreas() {
    $sas = $this->databaseConnection
      ->select('parser_service_areas')
      ->fields('parser_service_areas')
      ->execute()
      ->fetchAll();
    return (array) $sas;
  }
  
  /**
   * Get all sas in a Drupal 8 table.
   */
  public function saTable() {
    $entries = $this->getAll();
    $header = [
      'id' => t('id'),
      'rank' => t('rank'),
      'total_weight_self' => t('total_weight_self'),
    ];
    // Initialize an empty array.
    $output = [];
    // Next, loop through the $entries array.
    foreach ($entries as $entry) {
      if ($entry->id != 0) {
        $output[$entry->id] = [
          'id' => $entry->id,
          'rank' => $entry->rank,
          'total_weight_self' => $entry->total_weight_self,
        ];
      }
    }
    $form['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#options' => $output,
      '#empty' => t('No users found'),
    ];
    return $form;
  }

}
