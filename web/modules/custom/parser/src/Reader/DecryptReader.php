<?php

namespace Drupal\parser\Reader;

use Dotenv\Dotenv;

/**
 * Class DecryptReader.
 *
 * Parse and decrypts a given file and returns an array.
 */
class DecryptReader implements ReaderInterface {

  /**
   * Parses and decrypt the given file(s).
   */
  public function parse($files) {
    $dotenv = new Dotenv(DRUPAL_ROOT . '/../');
    $dotenv->load();

    return array_map(function ($file) {
      $encrypted_data = file_get_contents($file);

      $iv = getenv('SEEDS_ENC_IV');
      $key = getenv('SEEDS_ENC');

      $decrypt = openssl_decrypt($encrypted_data, 'AES-256-CBC', hex2bin($key), OPENSSL_RAW_DATA, hex2bin($iv));

      $unquoted_string = str_replace('"', "", $decrypt);

      return array_map(function ($row) {
        $row_array = explode(",", $row);
        $row_array[2] = intval($row_array[2]);
        $row_array[3] = intval($row_array[3]);
        var_dump($row_array);
        return $row_array;
      }, explode("\n", $unquoted_string));

    }, $files);
  }

}
