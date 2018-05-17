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

    return array_map(array($this, 'fileDecryption'), $files);
  }

  /**
   * Reads and decrypts the given file.
   */
  protected function fileDecryption($file) {
    $encrypted_data = file_get_contents($file);

    $iv = getenv('SEEDS_ENC_IV');
    $key = getenv('SEEDS_ENC');

    $decrypted_data = openssl_decrypt($encrypted_data, 'AES-256-CBC', hex2bin($key), OPENSSL_RAW_DATA, hex2bin($iv));

    $unquoted_data = str_replace('"', "", $decrypted_data);

    return array_map(array($this, 'rowCreation'), explode("\n", $unquoted_data));
  }

  /**
   * Splices the rows into values and cast them to integer where necessary.
   */
  protected function rowCreation($row) {
    $row_array = explode(",", $row);
    $row_array[2] = intval($row_array[2]);
    $row_array[3] = intval($row_array[3]);
    return $row_array;
  }

}
