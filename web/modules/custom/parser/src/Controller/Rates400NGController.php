<?php

namespace Drupal\parser\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Url;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Database\Connection as Connection;

/**
 * Class Rates400NGController.
 */
class Rates400NGController extends ControllerBase {

  /**
   * Drupal\Core\Database\Connection definition.
   *
   * @var \Drupal\Core\Database\Connection
   */
  private $databaseConnection;

  /**
   * Constructs a Rates400NGController.
   *
   * @param \Drupal\Core\Database\Connection $databaseConnection
   *   A Database Connection object.
   */
  public function __construct(Connection $databaseConnection) {
    $this->databaseConnection = $databaseConnection;
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
   * Get all sas in a Drupal 8 table.
   */
  public function tableLinks() {

    $build['linehauls_link'] = [
      '#title' => $this
        ->t('Linehauls rates'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.rates400NG_controller_linehauls'),
    ];
    $build['linehauls_nl']['#markup'] = '<br>';
    $build['packunpacks_link'] = [
      '#title' => $this
        ->t('Pack and Unpack rates'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.rates400NG_controller_packunpack'),
    ];
    $build['packunpacks_nl']['#markup'] = '<br>';
    $build['service_areas_link'] = [
      '#title' => $this
        ->t('Service areas'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.rates400NG_controller_service_areas'),
    ];
    $build['service_areas_nl']['#markup'] = '<br>';
    $build['shorthauls_link'] = [
      '#title' => $this
        ->t('Shorthauls rates'),
      '#type' => 'link',
      '#url' => Url::fromRoute('parser.rates400NG_controller_shorthaulsTable'),
    ];
    return $build;
  }

  /**
   * {@inheritdoc}
   */
  private function getAll($table) {
    return $this->databaseConnection
      ->select($table)
      ->fields($table)
      ->extend('Drupal\Core\Database\Query\PagerSelectExtender')->limit(10)
      ->execute()
      ->fetchAll();
  }

  /**
   * Get all sas in a Drupal 8 table.
   */
  public function linehaulsTable() {
    $entries = $this->getAll('parser_linehauls');
    $header = [
      'id' => $this->t('id'),
      'miles' => $this->t('miles'),
      'weight' => $this->t('weight'),
      'rate' => $this->t('rate'),
      'year' => $this->t('year'),

    ];
    // Initialize an empty array.
    $output = [];
    // Next, loop through the $entries array.
    foreach ($entries as $entry) {
      if ($entry->id != 0) {
        $output[$entry->id] = [
          'id' => $entry->id,
          'miles' => $entry->miles,
          'weight' => $entry->weight,
          'rate' => $entry->rate,
          'year' => $entry->year,
        ];
      }
    }
    $table['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $output,
      '#empty' => $this->t('Nothing here'),
    ];
    $table['pager'] = ['#type' => 'pager'];
    return $table;
  }

  /**
   * Get all sas in a Drupal 8 table.
   */
  public function packunpacksTable() {
    $entries = $this->getAll('parser_packunpacks');
    $header = [
      'id' => $this->t('id'),
      'schedule' => $this->t('schedule'),
      'cwt' => $this->t('cwt'),
      'pack' => $this->t('pack'),
      'unpack' => $this->t('unpack'),
      'year' => $this->t('year'),
    ];
    // Initialize an empty array.
    $output = [];
    // Next, loop through the $entries array.
    foreach ($entries as $entry) {
      if ($entry->id != 0) {
        $output[$entry->id] = [
          'id' => $entry->id,
          'schedule' => $entry->schedule,
          'cwt' => $entry->cwt,
          'pack' => $entry->pack,
          'unpack' => $entry->unpack,
          'year' => $entry->year,
        ];
      }
    }
    $table['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $output,
      '#empty' => $this->t('Nothing here'),
    ];
    $table['pager'] = ['#type' => 'pager'];
    return $table;
  }

  /**
   * Get all sas in a Drupal 8 table.
   */
  public function serviceAreasTable() {
    $entries = $this->getAll('parser_service_areas');
    $header = [
      'id' => $this->t('id'),
      'service_area' => $this->t('service_area'),
      'services_schedule' => $this->t('services_schedule'),
      'linehaul_factor' => $this->t('linehaul_factor'),
      'orig_dest_service_charge' => $this->t('orig_dest_service_charge'),
      'year' => $this->t('year'),
    ];
    // Initialize an empty array.
    $output = [];
    // Next, loop through the $entries array.
    foreach ($entries as $entry) {
      if ($entry->id != 0) {
        $output[$entry->id] = [
          'id' => $entry->id,
          'service_area' => $entry->service_area,
          'services_schedule' => $entry->services_schedule,
          'linehaul_factor' => $entry->linehaul_factor,
          'orig_dest_service_charge' => $entry->orig_dest_service_charge,
          'year' => $entry->year,
        ];
      }
    }
    $table['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $output,
      '#empty' => $this->t('Nothing here'),
    ];
    $table['pager'] = ['#type' => 'pager'];
    return $table;
  }

  /**
   * Get all sas in a Drupal 8 table.
   */
  public function shorthaulsTable() {
    $entries = $this->getAll('parser_shorthauls');
    $header = [
      'id' => $this->t('id'),
      'cwt_miles' => $this->t('cwt_miles'),
      'rate' => $this->t('rate'),
      'year' => $this->t('year'),
    ];
    // Initialize an empty array.
    $output = [];
    // Next, loop through the $entries array.
    foreach ($entries as $entry) {
      if ($entry->id != 0) {
        $output[$entry->id] = [
          'id' => $entry->id,
          'cwt_miles' => $entry->cwt_miles,
          'rate' => $entry->rate,
          'year' => $entry->year,
        ];
      }
    }
    $table['table'] = [
      '#type' => 'table',
      '#header' => $header,
      '#rows' => $output,
      '#empty' => $this->t('Nothing here'),
    ];
    $table['pager'] = ['#type' => 'pager'];
    return $table;
  }

}
