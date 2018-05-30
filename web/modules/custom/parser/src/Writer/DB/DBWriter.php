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
   * Returns a database connection object.
   *
   * @return object
   *   Returns a connection object.
   */
  public function getDatabaseConnection() {
    $connection = \Drupal::database();
    return $connection;
  }

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
  public function insertToTable(array $data, $table) {
    foreach ($data as $record) {
      $this->getDatabaseConnection()->insert($table)
        ->fields($record)
        ->execute();
    }
  }

}
