<?php

namespace Drupal\parser\Writer;

/**
 * Interface WriterInterface.
 *
 * Parser writes must implement write method.
 *
 * @param array $rawdata
 *  array with not normalized data.
 */
interface WriterInterface {

  /**
   * Writes json file.
   */
  public function write(array $rawdata);

}
