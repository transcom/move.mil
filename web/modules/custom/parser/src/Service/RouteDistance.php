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
  protected $googleApiKey;
  protected $config;
  protected $logger;

  /**
   * Constructs a new RouteDistance object.
   */
  public function __construct(ClientInterface $http_client, ConfigFactoryInterface $configFactory, LoggerChannelFactory $logger) {
    $this->httpClient = $http_client;
    $this->config = $configFactory;
    $this->logger = $logger;
    $this->googleApiKey = $_SERVER['GOOGLE_MAPS_API_KEY'];
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
      $this->logger->get('distancematrix')->debug('Response code: ' . $res->getStatusCode());
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
