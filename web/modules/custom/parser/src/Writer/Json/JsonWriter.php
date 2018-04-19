<?php

namespace Drupal\parser\Writer\Json;

/**
 * trait JsonWriter.
 *
 * Encodes an array to JSON, and prettifies the JSON with 4 spaces indentation.
 * @param $data - The array ready to be encoded.
 * @param $filename - The file to write the encoded JSON.
 */
trait JsonWriter {
  
  /**
   * Writes a json file.
   */
  public function writeJson(array $data, $filename) {
    $json = json_encode($data, JSON_PRETTY_PRINT);
    file_put_contents(
      "/var/www/html/web/sites/default/files/tools/data/${filename}",
      $json
    );
  }

}
