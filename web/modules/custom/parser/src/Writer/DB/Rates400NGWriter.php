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
  public function write(array $rawdata, $truncate, DrupalStyle $io) {
    // Write service_areas.
    $table = 'parser_service_areas';
    $service_areas = $this->data($rawdata, $table, 'schedules', $truncate, $io);
    $this->insertToTable($service_areas, $table);
    // Write linehauls.
    $table = 'parser_linehauls';
    $linehauls = $this->data($rawdata, $table, 'linehauls', $truncate, $io);
    $this->insertToTable($linehauls, $table);
    // Write shorthauls.
    $table = 'parser_shorthauls';
    $shorthauls = $this->data($rawdata, $table, 'shorthauls', $truncate, $io);
    $this->insertToTable($shorthauls, $table);
    // Write packunpacks.
    $table = 'parser_packunpacks';
    $packunpacks = $this->data($rawdata, $table, 'packunpack', $truncate, $io);
    $packunpacks = $this->mappackunpackdata($packunpacks);
    $this->insertToTable($packunpacks, $table);
  }

  /**
   * Prepare data.
   */
  private function data(array $rawdata, $table, $dataname, $truncate, DrupalStyle $io) {
    if ($truncate) {
      $io->info("Truncating {$table} table.");
      $this->truncateTable($table);
    }
    $io->info("Writing new records on {$table} table.");
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
