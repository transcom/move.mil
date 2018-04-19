<?php

namespace Drupal\parser\Writer\Json;

/**
 * Trait JsonWriter.
 *
 * Encodes an array to JSON, and prettifies the JSON with 4 spaces indentation.
 *
 * @param array $data
 *  The array ready to be encoded.
 * @param String $filename
 *  The file to write the encoded JSON.
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
