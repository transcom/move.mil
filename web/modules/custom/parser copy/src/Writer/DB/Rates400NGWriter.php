<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;
use Drupal\Console\Core\Style\DrupalStyle;

/**
 * Class Rates400NGWriter.
 *
 * Parses a given array and saves it in a custom table.
 */
class Rates400NGWriter implements WriterInterface {
  use DBWriter;

  /**
   * Normalizes data then writes it into db tables.
   */
  public function write(array $rawdata) {
    $status = [];

    // Write service_areas.
    $table = 'parser_service_areas';
    $service_areas = $this->data($rawdata, $table, 'schedules');
    array_push($status, $this->insertToTable($service_areas, $table));

    // Write linehauls.
    $table = 'parser_linehauls';
    $linehauls = $this->data($rawdata, $table, 'linehauls');
    array_push($status, $this->insertToTable($linehauls, $table));

    // Write shorthauls.
    $table = 'parser_shorthauls';
    $shorthauls = $this->data($rawdata, $table, 'shorthauls');
    array_push($status, $this->insertToTable($shorthauls, $table));

    // Write packunpacks.
    $table = 'parser_packunpacks';
    $packunpacks = $this->data($rawdata, $table, 'packunpack');
    $packunpacks = $this->mappackunpackdata($packunpacks);
    array_push($status, $this->insertToTable($packunpacks, $table));

    return $status;

  }

  /**
   * Prepare data.
   */
  private function data(array $rawdata, $table, $dataname) {
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
  private function mappackunpackdata(array $rawdata) {
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

}
