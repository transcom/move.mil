<?php

namespace Drupal\test\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class PpmDistancesController defines the PPM distances controller.
 */
class PpmDistancesController extends ControllerBase {

  /**
   * Distances Matrix.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return entitlements as a Json object
   */
  public function distances() {
    $data = [
      'rows' => [
        0 => [
          'elements' => [
            0 => [
              'distance' => [
                'value' => 4298801,
              ],
            ],
          ],
        ],
      ],
    ];
    // Create JSON response.
    $response = JsonResponse::create($data, 200);
    if (gettype($response) == 'object') {
      return $response;
    }
    else {
      return JsonResponse::create('Error while creating response.', 500);
    }
  }

}
