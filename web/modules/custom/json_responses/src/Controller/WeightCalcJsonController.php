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
    $finder->files()->in(DRUPAL_ROOT . '/../lib/data/json-data')->name('household_goods_weights.json');
    $file = NULL;

    foreach ($finder as $f) {
      $file = $f->getContents();
    }

    if ($file == NULL) {
      return JsonResponse::create('File not Found', 404);
    }

    $file_as_string = str_replace("\n", "", $file);
    $json_obj = json_decode($file_as_string);

    $array_obj = [];

    foreach ($json_obj as $obj) {
      $items = [];
      foreach ($obj->household_goods as $item) {
        $items[preg_replace('/[^\p{L}\p{N}\s]/u', '', str_replace(' ', '-', $item->name))] = [
          'displayName' => $item->name,
          'weight' => $item->weight,
        ];
      }

      $array_obj[preg_replace('/[^\p{L}\p{N}\s]/u', '', str_replace(' ', '-', $obj->name))] = [
        "displayName" => $obj->name,
        "icon" => $obj->icon,
        "items" => $items,
      ];
    }

    $response = JsonResponse::create($array_obj, 200);
    $response->setEncodingOptions($response->getEncodingOptions() | JSON_PRETTY_PRINT | JSON_FORCE_OBJECT);
    if (gettype($response) == 'object') {
      return $response;
    }
    else {
      return JsonResponse::create('error while creating response', 500);
    }
  }

}
