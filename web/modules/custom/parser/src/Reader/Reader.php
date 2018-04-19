<?php

namespace Drupal\parser\Reader;

/**
 * Interface ReaderInterface.
 *
 * Parser readers must implement parse method.
 * @param $filename - The name of the file name to parse.
 */
interface ReaderInterface {
  
  /**
   * Parses a file to an array.
   */
  public function parse($filename);

}
