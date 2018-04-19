<?php

namespace Drupal\parser\Reader;

use Symfony\Component\Yaml\Yaml;
use Symfony\Component\Yaml\Exception\ParseException;
use Drupal\parser\Reader\Reader;

/**
 * Class YamlReader
 *
 * Parse a given yaml file and returns an array
 */

class YamlReader implements Reader {

  public function parse($yamlFile) {
    $yaml = Yaml::parseFile($yamlFile);
    return $yaml;
  }
}
