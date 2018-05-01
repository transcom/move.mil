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
    $service_areas = $this->adddate($rawdata['schedules'], $rawdata['date']);
    $this->writetable($service_areas, 'service_areas');
    // Write linehauls.
    $linehauls = $this->adddate($rawdata['linehauls'], $rawdata['date']);
    $this->writetable($linehauls, 'linehauls');
    // Write shorthauls.
    $shorthauls = $this->adddate($rawdata['shorthauls'], $rawdata['date']);
    $this->writetable($shorthauls, 'shorthauls');
    // Write packunpacks.
    $packunpacks = $this->adddate($rawdata['packunpack'], $rawdata['date']);
    $this->writetable($packunpacks, 'packunpacks');
  }

  /**
   * Add date to current array data.
   */
  private function adddate(array $rawdata, $date) {
    $data = [];
    while ($record = current($rawdata)) {
      $record['date'] = $date;
      $data[] = $record;
      next($rawdata);
    }
    return $data;
  }

}
