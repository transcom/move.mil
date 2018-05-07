<?php

namespace Drupal\parser\Handler;

use Drupal\parser\Reader\CsvReader;
use Drupal\parser\Reader\ExcelReader;
use Drupal\parser\Reader\YamlReader;
use Drupal\parser\Writer\DB\EntitlementsWriter;
use Drupal\parser\Writer\DB\Rates400NGWriter;
use Drupal\parser\Writer\DB\CsvWriter;
use Drupal\parser\Writer\DB\DiscountWriter;
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
    list(
      $this->filename,
      $this->reader,
      $this->writer
      ) = $this->filename($path, $input);
    $this->truncate = $truncate;
    $this->io = $io;
  }

  /**
   * Parses data and then write file.
   */
  public function execute() {
    $this->io->info("Parsing {$this->filename}...");
    $rawdata = $this->reader->parse($this->filename);
    $this->io->info("Finished parsing {$this->filename}...");

    $this->writer->write($rawdata, $this->truncate, $this->io);
  }

  /**
   * Initilazes filename, reader, and writer according to the user input.
   */
  private function filename($path, $input) {
    $filename = $input;
    $reader = NULL;
    $writer = NULL;
    switch ($input) {
      case (preg_match('/zip.*/', $input) ? TRUE : FALSE):
        $filename = "${path}/${input}.csv";
        $reader = new CsvReader();
        $writer = new CsvWriter($input);
        break;

      case (preg_match('/\d+-400NG/', $input) ? TRUE : FALSE):
        $filename = "${path}/${input}.xlsx";
        $reader = new ExcelReader();
        $writer = new Rates400NGWriter();
        break;

      case 'entitlements':
        $filename = "${path}/${input}.yml";
        $reader = new YamlReader();
        $writer = new EntitlementsWriter();
        break;

      case (preg_match('/No 1 BVS Dom Discounts - Eff .*/', $input) ? TRUE : FALSE):
        $filename = "${path}/${input}.csv";
        $reader = new CsvReader();
        $writer = new DiscountWriter($input);
        break;

    }
    return [$filename, $reader, $writer];
  }

}
