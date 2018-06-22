<?php
namespace Drupal\parser\Service;

use Drupal\node\Entity\Node;
use Drupal\Core\Database\Connection;


/**
 * Class XMLReader.
 *
 * Parse an XML file.
 */
class XMLReader {

  /**
   * Parses csv file with php function str_getcsv.
   */
  public function parse($xmlFile) {
    if (!is_file($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" does not exist.', $xmlFile));
    }
    if (!is_readable($xmlFile)) {
      throw new \RuntimeException(sprintf('File "%s" cannot be read.', $xmlFile));
    }
    dump(simplexml_load_file($xmlFile));
    die;
    return simplexml_load_file(file($xmlFile));
  }

}