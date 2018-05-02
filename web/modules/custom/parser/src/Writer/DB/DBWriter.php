<?php

namespace Drupal\parser\Writer\DB;

/**
 * Trait DBWriter.
 *
 * Handles db custom tables for parser module.
 *
 * @param array $data
 *  The array ready to be encoded.
 * @param string $table
 *  The table to write the array.
 */
trait DBWriter {

  /**
   * Save entries in the database.
   *
   * @param array $data
   *   An array of arrays containing all the fields of the database record.
   * @param string $table
   *   The table for inserting the data.
   *
   * @see db_insert()
   */
  public function writetable(array $data, $table) {
    foreach ($data as $record) {
      db_insert($table)
        ->fields($record)
        ->execute();
    }
  }

  /**
   * Truncates a database table.
   *
   * @param string $table
   *   The table to truncate.
   *
   * @see db_truncate()
   */
  public function truncate($table) {
    db_truncate($table)
        ->execute();
  }

}
