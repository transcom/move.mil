<?php

namespace Drupal\parser\Writer;

interface Writer {
  function generateJson(array $rawdata);
}