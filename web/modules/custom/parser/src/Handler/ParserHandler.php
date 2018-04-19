<?php

namespace Drupal\parser\Handler;

use Drupal\parser\Reader\CsvReader;
use Drupal\parser\Reader\ExcelReader;
use Drupal\parser\Reader\YamlReader;
use Drupal\parser\Writer\Json\EntitlementsWriter;
use Drupal\parser\Writer\Json\_400NGWriter;
use Drupal\parser\Writer\Json\Zip5Writer;
use Drupal\parser\Writer\Json\Zip3Writer;

/**
 * Class ParserController.
 *
 * Takes an input file, and according to the
 * extension, calls the proper parser.
 * Then, calls the proper output generator and
 * returns a JSON structure.
 */
class ParserHandler {
  
  protected $filename;
  protected $reader;
  protected $writer;
  protected $io;

  /**
   * Constructs a ParserHandler.
   * @param $path Where the file input is located.
   * @param $input The user input.
   * @param $io DrupalStyle io.
   */
  public function __construct($path, $input, $io) {
    list(
      $this->filename, 
      $this->reader, 
      $this->writer
      ) = $this->filename($path, $input);
    $this->io = $io;
  }

  /**
   * Calls the reader parse method and gets an array with
   * not normalized data.
   * Calls the writer write method with the rawdata array.
   */
  public function execute() {
    $this->io->info("Parsing {$this->filename}...");
    $rawdata = $this->reader->parse($this->filename);
    $this->writer->write($rawdata);
  }

  /**
   * Initilazes filename, reader, and writer according to the user input.
   */
  private function filename($path, $input) {
    $filename = $input;
    $reader = NULL;
    $writer = NULL;
    switch ($input) {
      case 'zip3':
        $filename = "${path}/${input}.csv";
        $reader = new CsvReader();
        $writer = new Zip3Writer();
        break;

      case 'zip5':
        $filename = "${path}/${input}_rate_areas.csv";
        $reader = new CsvReader();
        $writer = new Zip5Writer();
        break;

      case '2017-400NG': 
        $filename = "${path}/${input}.xlsx";
        $reader = new ExcelReader();
        $writer = new Rates400NGWriter();
        break;

      case '2018-400NG': 
        $filename = "${path}/${input}.xlsx";
        $reader = new ExcelReader();
        $writer = new Rates400NGWriter();
        break;

      case 'entitlements':
        $filename = "${path}/${input}.yml";
        $reader = new YamlReader();
        $writer = new EntitlementsWriter();
        break;
    }
    return [$filename, $reader, $writer];
  }

}
