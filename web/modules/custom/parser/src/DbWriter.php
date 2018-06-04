<?php

namespace Drupal\parser;

use Drupal\Core\Database\Connection;

/**
 * Class DBWriter.
 *
 * Handles data insertion into custom tables for parser module.
 */
class DbWriter {

  protected $db;

  /**
   * DbWriter constructor.
   *
   * Needed for dependency injection.
   */
  public function __construct(Connection $db) {
    $this->db = $db;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('database')
    );
  }

  /**
   * {@inheritdoc}
   */
  public function write($rawdata, $file, $tables) {
    switch ($file) {
      case 'zip_3':
        $headers = [
          'zip3',
          'basepoint_city',
          'state',
          'service_area',
          'rate_area',
          'region',
        ];
        $data = $this->zipMapdata($rawdata, $headers);
        break;

      case 'zip_5':
        $headers = ['zip5', 'service_area'];
        $data = $this->zipMapdata($rawdata, $headers);
        break;

      case 'discounts':
        $headers = [
          'origin',
          'destination',
          'discounts',
          'site_rate',
          'tdl',
        ];
        $data = $this->discountsMapdata($rawdata, $headers);
        break;

      case 'zipcodes':
        $data = $this->zipcodesMapdata($rawdata);
        break;

      case '400NG':
        $dataname = ['schedules', 'linehauls', 'shorthauls', 'packunpack'];

        $data = [];
        foreach ($tables as $key => $table) {
          array_push($data, $this->prepare400NgData($rawdata, $table, $dataname[$key]));
          if ($table == 'packunpack') {
            $data = $this->mapPackUnpackData($data);
          }
        }
        break;

      case 'entitlements':
        $data = $this->entitlementsMapdata($rawdata);
        break;
    }

    if (is_array($tables)) {
      foreach ($tables as $key => $table) {
        $this->insertToTable($data[$key], $table);
      }
    }
    else {
      $this->insertToTable($data, $tables);
    }
  }

  /**
   * Normalizes data mapping for zip3 and zip5 files with the rest of the data.
   */
  private function zipMapdata(array $rawdata, $headers) {
    while ($row = current($rawdata)) {
      $rows[] = array_combine($headers, $row);
      next($rawdata);
    }
    return $rows;
  }

  /**
   * Normalizes datamapping for the entitlements file with the rest of the data.
   */
  private function entitlementsMapdata(array $rawdata) {
    $rows = [];
    while ($row = current($rawdata)) {
      $row['slug'] = strtolower(preg_replace('/[^A-Za-z0-9]+/', '-', $row['rank']));
      $rows[] = $row;
      next($rawdata);
    }
    return $rows;
  }

  /**
   * Normalizes data mapping for the zipcodes file with the rest of the data.
   */
  private function zipcodesMapdata(array $rawdata) {
    $codes = [];
    // Remove first headers row.
    array_shift($rawdata);
    $headers = [
      'code',
      'city',
      'state',
      'lat',
      'lon',
    ];
    while ($code = current($rawdata)) {
      $code_filtered = array_filter($code, function ($k) {
        // Skip county (3), and area_code(4) values.
        return $k != 3 && $k != 4;
      }, ARRAY_FILTER_USE_KEY);
      $code_with_headers = array_combine($headers, $code_filtered);
      if ($code_with_headers != FALSE) {
        if ($code_with_headers['lat'] == '') {
          $code_with_headers['lat'] = 0;
        }
        if ($code_with_headers['lon'] == '') {
          $code_with_headers['lon'] = 0;
        }
        $codes[] = $code_with_headers;
        next($rawdata);
      }
      else {
        $codes[] = $code_with_headers;
        next($rawdata);
      }
    }
    return $codes;
  }

  /**
   * Normalizes data mapping for the discounts file with the rest of the data.
   */
  private function discountsMapdata(array $rawdata, $headers) {
    foreach ($rawdata as $row) {
      $discount[] = array_combine($headers, $row);
    }
    return $discount;
  }

  /**
   * Prepare the 400NG data.
   */
  private function prepare400NgData(array $rawdata, $table, $dataname) {
    $data = $this->addyear($rawdata[$dataname], $rawdata['year']);
    return $data;
  }

  /**
   * Add year to current array data.
   */
  private function addyear(array $rawdata, $year) {
    $data = [];
    while ($record = current($rawdata)) {
      $record['year'] = $year;
      $data[] = $record;
      next($rawdata);
    }
    return $data;
  }

  /**
   * Normalizes data mapping packunpacks.
   */
  private function mapPackUnpackData(array $rawdata) {
    $packunpacks = [];
    $unpack = 0;
    while ($packunpack = current($rawdata)) {
      if ($packunpack['unpack'] != NULL) {
        $unpack = $packunpack['unpack'];
      }
      else {
        $packunpack['unpack'] = $unpack;
      }
      $packunpacks[] = $packunpack;
      next($rawdata);
    }
    return $packunpacks;
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
      $this->db->insert($table)
        ->fields($record)
        ->execute();
    }
  }

}
