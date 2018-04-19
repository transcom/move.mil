<?php

namespace Drupal\parser\Writer\Json;

trait JsonWriter {
  public function writeJson(array $data, $filename) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents(
      "/var/www/html/web/sites/default/files/tools/data/${filename}",
      $json
    );
  }
}