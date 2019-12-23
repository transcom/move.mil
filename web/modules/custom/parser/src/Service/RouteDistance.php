<?php

namespace Drupal\parser\Service;

use GuzzleHttp\ClientInterface;
use GuzzleHttp\Exception\GuzzleException;
use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Logger\LoggerChannelFactory;

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

  /**
   * Google API key.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $googleApiKey;

  /**
   * Drupal\Core\Config\ConfigFactoryInterface definition.
   *
   * @var \Drupal\Core\Config\ConfigFactoryInterface
   */
  protected $config;

  /**
   * Drupal\Core\Logger\LoggerChannelFactory definition.
   *
   * @var \Drupal\Core\Logger\LoggerChannelFactory
   */
  protected $logger;

  /**
   * Constructs a new RouteDistance object.
   */
  public function __construct(ClientInterface $http_client, ConfigFactoryInterface $configFactory, LoggerChannelFactory $logger) {
    $this->httpClient = $http_client;
    $this->config = $configFactory;
    $this->logger = $logger;
    $this->googleApiKey = $_ENV['GOOGLE_GEO_DIST_API_KEY'];
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('http_client'),
      $container->get('config.factory'),
      $container->get('logger.factory')
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
      $this->logger->get('distancematrix')->error($e->getMessage());
      return NULL;
    }
    $data = json_decode($res->getBody()->getContents(), JSON_OBJECT_AS_ARRAY);
    if ($res->getStatusCode() == 200) {
      return $data;
    }
    return NULL;
  }

}
