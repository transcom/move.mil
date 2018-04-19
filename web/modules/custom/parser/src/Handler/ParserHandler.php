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
 * returns a JSON structure
 */

class ParserHandler {
  
  protected $filename;
  protected $reader;
  protected $writer;
  protected $io;

  public function __construct($path, $input, $io){
    list(
      $this->filename, 
      $this->reader, 
      $this->writer
      ) = $this->filename($path, $input);
    $this->io = $io;
  }

  public function execute() {
    $this->io->info("Parsing {$this->filename}...");
    $rawdata = $this->reader->parse($this->filename);
    $this->writer->write($rawdata);
  }

  private function filename($path, $input) {
    $filename = $input;
    $reader = null;
    $writer = null;
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
        $writer = new _400NGParser();
        break;
      case '2018-400NG': 
        $filename = "${path}/${input}.xlsx";
        $reader = new ExcelReader();
        $writer = new _400NGWriter();
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
