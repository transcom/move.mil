<?php

namespace Drupal\parser\Writer\DB;

/**
 * Trait DBWriter.
 *
 * Saves data into a db custom table.
 *
 * @param array $data
 *  The array ready to be encoded.
 * @param string $table
 *  The table to write the array.
 */
trait DBWriter {

  /**
   * Writes a db table.
   */
  public function writetable(array $data, $table) {
    echo 'writing on ' . $table;
  }

}
