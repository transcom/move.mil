<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection as Connection;

/**
 * Class EntitlementsController.
 */
class EntitlementsController extends ControllerBase {

  private $databaseConnection;

  /**
   * Constructs a EntitlementsController.
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
   * Get all entitlements.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return entitlements as a Json object
   */
  public function index() {
    $entries = $this->getAll();
    $data = $this->mapdata($entries);
    $response = JsonResponse::create($data, 200);
    $response->setEncodingOptions(
      $response->getEncodingOptions() |
      JSON_PRETTY_PRINT |
      JSON_FORCE_OBJECT
    );
    if (gettype($response) == 'object') {
      return $response;
    }
    else {
      return JsonResponse::create('Error while creating response.', 500);
    }
  }

  /**
   * Get all entitlements from the DB.
   */
  private function getAll() {
    return $this->databaseConnection
      ->select('parser_entitlements')
      ->fields('parser_entitlements')
      ->execute()
      ->fetchAll();
  }

  /**
   * Get all entitlements from the DB. Paginated.
   */
  private function getAllPaginated() {
    return $this->databaseConnection
      ->select('parser_entitlements')
      ->fields('parser_entitlements')
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10)
      ->execute()
      ->fetchAll();
  }

  /**
   * Normalizes data mapping entitlements code with the rest of the data.
   */
  private function mapdata(array $entries) {
    $entitlements = [];
    foreach ($entries as $entry) {
      $entitlement = (array) $entry;
      $key = $entitlement['slug'];
      $entitlements[$key] = $entitlement;
    }
    return $entitlements;
  }

  /**
   * Get all entitlements in a Drupal 8 table.
   */
  public function table() {
    $entries = $this->getAllPaginated();
    $header = [
      'id' => $this->t('id'),
      'rank' => $this->t('rank'),
      'total_weight_self' => $this->t('total_weight_self'),
      'total_weight_self_plus_dependents' => $this->t('total_weight_self_plus_dependents'),
      'pro_gear_weight' => $this->t('pro_gear_weight'),
      'pro_gear_weight_spouse' => $this->t('pro_gear_weight_spouse'),
      'slug' => $this->t('slug'),
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
          'total_weight_self_plus_dependents' => $entry->total_weight_self_plus_dependents,
          'pro_gear_weight' => $entry->pro_gear_weight,
          'pro_gear_weight_spouse' => $entry->pro_gear_weight_spouse,
          'slug' => $entry->slug,
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
