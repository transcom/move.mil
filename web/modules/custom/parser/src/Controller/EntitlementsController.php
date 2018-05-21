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
   * Get all entitlements table.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return entitlements as a Json object
   */
  public function index() {
    $entries = $this->databaseConnection
      ->select('parser_entitlements')
      ->fields('parser_entitlements')
      ->execute()
      ->fetchAll();
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

}
