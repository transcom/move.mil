<?php

namespace Drupal\parser\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use \Drupal\Core\Config\ConfigFactoryInterface;

/**
 * Class RouteDistance.
 */
class RouteDistance {

  /**
   * GuzzleHttp\ClientInterface definition.
   *
   * @var \GuzzleHttp\ClientInterface
   */
  protected $httpClient;
  protected $googleApiKey;
  protected $config;

  /**
   * Constructs a new RouteDistance object.
   */
  public function __construct(ClientInterface $http_client, ConfigFactoryInterface $configFactory) {
    $this->httpClient = $http_client;
    $this->config = $configFactory;
    $this->googleApiKey = $_SERVER['GOOGLE_MAPS_API_KEY'];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('config.factory')
    );
  }

  /**
   * Get the distances matrix.
   */
  public function distances($origins, $destinations) {
    // Build Google Distance Matrix Request.
    $googleUrl = $this->config->get('parser.settings')->get('distancesurl');
    $request = "{$googleUrl}?origins={$origins}&destinations={$destinations}&key={$this->googleApiKey}";
    try {
      $res = $this->httpClient->request('GET', $request);
    }
    catch (GuzzleException $e) {
      return NULL;
    }
    $data = json_decode($res->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
    if ($res->getStatusCode() == 200) {
      return $data;
    }
    return NULL;
  }

}
