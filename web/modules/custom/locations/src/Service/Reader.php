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
  public function parse($xmlFile) {
    if (!is_file($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" does not exist.', $xmlFile));
    }
    if (!is_readable($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" cannot be read.', $xmlFile));
    }
    // Get XML offices to update on Drupal.
    return simplexml_load_file($xmlFile)->LIST_G_CNSL_ORG_ID->G_CNSL_ORG_ID;
  }

}
