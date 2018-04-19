<?php

namespace Drupal\parser\Reader;

use Drupal\parser\Reader\ReaderInterface;

use Symfony\Component\Yaml\Yaml;

/**
 * Class YamlReader.
 *
 * Parses a given yaml file and returns an array.
 */
class YamlReader implements ReaderInterface {

  /**
   * Parses yaml file with Symfony Yaml library.
   */
  public function parse($yamlFile) {
    $yaml = Yaml::parseFile($yamlFile);
    return $yaml;
  }

}
