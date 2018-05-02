<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\parser\Repositories\EntitlementsRepository;

/**
 * Class PpmEstimatorController.
 */
class PpmEstimatorController extends ControllerBase {

  private $EntitlementsRepository;

  /**
   * Constructs a PpmEstimatorController.
   *
   * @param \Drupal\parser\Repositories\EntitlementsRepository $er
   *   A EntitlementsRepository object.
   */
  public function __construct(EntitlementsRepository $er) {
    $this->EntitlementsRepository = $er;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(new EntitlementsRepository());
  }

  /**
   * Get PPM Incentive Estimate response.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return entitlements as a Json object
   */
  public function ppm_estimate(Request $request) {
    $content = $request->getContent();
    if (!empty($content)) {
      $params = json_decode($content, TRUE);
    }
    $data = [
      'locations' => [
        'origin' => 'Fairfax, VA 22030',
        'destination' => 'Beverly Hills, CA 90210',
      ],
      'weightOptions' => [
        'houseHold' => 1111,
        'proGear' => 2222,
        'dependent' => FALSE,
        'total' => 3333,
      ],
      'selectedMoveDate' => '2018-05-24T18:22:33.000Z',
      'incentive' => [
        'min' => 4500,
        'max' => 5200,
      ],
      'advancePayment' => [
        'min' => 2700,
        'max' => 3120,
        'discount' => 60
      ],
    ];
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

}
