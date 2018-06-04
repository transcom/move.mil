<?php

namespace Drupal\parser;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YmlReader.
 *
 * Parses a given yaml file and returns an array.
 */
class YmlReader {

  /**
   * Parses yaml file with Symfony Yaml library.
   */
  public function parse($yamlFile) {
    $yaml = Yaml::parseFile($yamlFile);
    return $yaml;
  }

}
