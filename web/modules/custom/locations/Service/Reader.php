<?php

namespace Drupal\locations\Service;

/**
 * Class Reader.
 *
 * Parses the given XML file and returns an array of arrays.
 */
class Reader {

  /**
   * Reads and parses XML location file provided by DoD.
   */
  public function parse($input) {
    return array_map(function ($file) {
      if (!is_file($file)) {
        throw new \RuntimeException(sprintf('File "%s" does not exist.', $file));
      }
      if (!is_readable($file)) {
        throw new \RuntimeException(sprintf('File "%s" cannot be read.', $file));
      }
      return file_get_contents("{$file}");
    }, $input);
  }

}
