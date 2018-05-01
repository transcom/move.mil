<?php

namespace Drupal\parser\Writer\DB;

use Drupal\parser\Writer\WriterInterface;

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
    // Write service_areas.
    $service_areas = $this->addyear($rawdata['schedules'], $rawdata['year']);
    $this->writetable($service_areas, 'parser_service_areas');
    // Write linehauls.
    $linehauls = $this->addyear($rawdata['linehauls'], $rawdata['year']);
    $this->writetable($linehauls, 'parser_linehauls');
    // Write shorthauls.
    $shorthauls = $this->addyear($rawdata['shorthauls'], $rawdata['year']);
    $this->writetable($shorthauls, 'parser_shorthauls');
    // Write packunpacks.
    $packunpacks = $this->addyear($rawdata['packunpack'], $rawdata['year']);
    $packunpacks = $this->mappackunpackdata($packunpacks);
    $this->writetable($packunpacks, 'parser_packunpacks');
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
