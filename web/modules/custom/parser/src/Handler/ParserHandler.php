<?php

namespace Drupal\parser\Handler;

use Drupal\parser\Reader\CsvReader;
use Drupal\parser\Reader\ExcelReader;
use Drupal\parser\Reader\YamlReader;
use Drupal\parser\Reader\LocationReader;
use Drupal\parser\Writer\DB\EntitlementsWriter;
use Drupal\parser\Writer\DB\Rates400NGWriter;
use Drupal\parser\Writer\DB\CsvWriter;
use Drupal\parser\Writer\DB\LocationWriter;
use Drupal\parser\Writer\DB\DiscountWriter;
use Drupal\parser\Writer\DB\ZipCodesWriter;
use Drupal\Console\Core\Style\DrupalStyle;

/**
 * Class ParserController.
 *
 * Takes an input file, and according to the
 * extension, calls the proper parser.
 * Then, calls the proper output generator and
 * store data in the DB.
 */
class ParserHandler {

  protected $filename;
  protected $reader;
  protected $writer;
  protected $truncate;
  protected $io;

  /**
   * Constructs a ParserHandler.
   *
   * @param string $path
   *   Where the file input is located.
   * @param string $input
   *   The user input.
   * @param bool $truncate
   *   Truncate the db table before writing.
   * @param \Drupal\Console\Core\Style\DrupalStyle $io
   *   The DrupalStyle io.
   */
  public function __construct($path, $input, $truncate, DrupalStyle $io) {
    $this->io = $io;
    list(
      $this->filename,
      $this->reader,
      $this->writer
      ) = $this->filename($path, $input);
    $this->truncate = $truncate;
  }

  /**
   * Parses data and then write file.
   */
  public function execute() {
    $filename_to_string = is_array($this->filename) ? implode(",\n", $this->filename) : $this->filename;
    $this->io->info("Parsing {$filename_to_string}...");
    $rawdata = $this->reader->parse($this->filename);
    $this->io->info("File read and pre-processed [{$filename_to_string}].");
    $this->writer->write($rawdata, $this->truncate, $this->io);
  }

  /**
   * Initializes filename, reader, and writer according to the user input.
   */
  private function filename($path, $input) {
    $reader = NULL;
    $writer = NULL;
    switch ($input) {
      case (preg_match('/^zip[\d]+/', $input) ? TRUE : FALSE):
        $filename = "${path}/${input}.csv";
        $reader = new CsvReader();
        $writer = new CsvWriter($input);
        break;

      case (preg_match('/[\d]{4}-400NG/', $input) ? TRUE : FALSE):
        $filename = "${path}/${input}.xlsx";
        $reader = new ExcelReader();
        $writer = new Rates400NGWriter();
        break;

      case 'entitlements':
        $filename = "${path}/${input}.yml";
        $reader = new YamlReader();
        $writer = new EntitlementsWriter();
        break;

      case (preg_match('/discounts-[\d]+[\w]{3}[\d]{4}/', $input) ? TRUE : FALSE):
        $filename = "${path}/${input}.csv";
        $reader = new CsvReader();
        $writer = new DiscountWriter($input);
        break;

      case 'all_us_zipcodes':
        $filename = "${path}/${input}.csv";
        $reader = new CsvReader();
        $writer = new ZipCodesWriter($input);
        break;

      case 'locations':
        $filename = [
          "{$path}/shipping_offices.json",
          "{$path}/transportation_offices.json",
          "{$path}/weight_scales.json",
        ];
        $reader = new LocationReader();
        $writer = new LocationWriter();
        break;

      default:
        $this->io->error("Filename not found: [{$input}]");
        exit(-1);
    }
    return [$filename, $reader, $writer];
  }

}
