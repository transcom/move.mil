<?php

namespace Drupal\json_responses\Controller;

use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\Finder\Finder;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class WeightCalcJsonController.
 */
class WeightCalcJsonController extends ControllerBase {

  /**
   * Reads a Json file and converts it to a desired layout.
   *
   * @return \Symfony\Component\HttpFoundation\JsonResponse
   *   Return desired Json object
   */
  public function response() {
    $finder = new Finder();
    $finder->files()->in(__DIR__)->name('test.json');
    $file = NULL;

    foreach ($finder as $f) {
      $file = $f->getContents();
    }

    $file_as_string = str_replace("\n", "", $file);
    $json_obj = json_decode($file_as_string);

    $array_obj = array_map(function ($obj) {
      $items = array_map(function ($item) {
        return [
          preg_replace('/[^\p{L}\p{N}\s]/u', '', str_replace(' ', '-', $item->name)) => [
            "displayName" => $item->name,
            "weight" => $item->weight,
          ],
        ];
      }, $obj->household_goods);

      return [
        preg_replace('/[^\p{L}\p{N}\s]/u', '', str_replace(' ', '-', $obj->name)) => [
          'displayName' => $obj->name,
          "icon" => $obj->icon,
          "items" => $items,
        ],
      ];
    }, $json_obj);

    $response = JsonResponse::create($array_obj, 200);
    $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT);

    return $response;
  }

}
