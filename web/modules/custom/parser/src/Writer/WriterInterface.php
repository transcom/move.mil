<?php

namespace Drupal\parser\Writer;

use Drupal\Console\Core\Style\DrupalStyle;

/**
 * Interface WriterInterface.
 *
 * Parser writes must implement write method.
 *
 * @param array $rawdata
 *  array with not normalized data.
 * @param bool $truncate
 *   Truncate the db table before writing.
 * @param \Drupal\Console\Core\Style\DrupalStyle $io
 *   The DrupalStyle io.
 */
interface WriterInterface {

  /**
   * Writes json file.
   */
  public function write(array $rawdata, $truncate);

}
