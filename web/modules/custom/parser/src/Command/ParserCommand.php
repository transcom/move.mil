<?php

namespace Drupal\parser\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Core\Command\Command;
use Drupal\parser\Handler\ParserHandler;

/**
 * Class ParserCommand.
 *
 * @Drupal\Console\Annotations\DrupalCommand (
 *     extension="parser",
 *     extensionType="module"
 * )
 */
class ParserCommand extends Command {

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('parser')
      ->setDescription($this->trans('commands.parser.description'))
      ->addOption(
        'file',
        NULL,
        InputOption::VALUE_REQUIRED,
        $this->trans('commands.parser.options.file')
        )
      ->addOption(
        'truncate',
        NULL,
        InputOption::VALUE_OPTIONAL,
        $this->trans('commands.parser.options.truncate')
        );
  }

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    $all = FALSE;
    $file_options = $this->files();

    // array_push to add options to the autocomplete that wont be used in the
    // parseAll()
    array_push($file_options, 'locations', 'all');
    if (!$input->getOption('file')) {
      $file = $this->getIo()->choiceNoList(
        $this->trans('commands.parser.questions.file'),
        $file_options
      );
      $input->setOption('file', $file);
      if ($file == 'all') {
        $all = TRUE;
      }
    }
    if (!$all && !$input->getOption('truncate')) {
      $truncate = $this->getIo()->confirm(
        $this->trans('commands.parser.questions.truncate'),
        FALSE
      );
      $input->setOption('truncate', $truncate);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $file = $input->getOption('file');
    $path = DRUPAL_ROOT . '/../lib/data';
    if ($file == 'all') {
      $this->parseAll($path);
    }
    else {
      $parser = new ParserHandler($path, $file, $input->getOption('truncate'), $this->getIo());
      $parser->execute();
    }
    $this->getIo()->success($this->trans('commands.parser.messages.success'));
  }

  /**
   * Returns supported files to be parsed.
   */
  private function files() {
    return [
      'zip3',
      'zip5',
      '2017-400NG',
      '2018-400NG',
      'entitlements',
      'discounts',
      'all_us_zipcodes',
    ];
  }

  /**
   * Execute all parsers.
   */
  protected function parseAll($path) {
    $all_files = $this->files();
    foreach ($all_files as $file) {
      $parser = new ParserHandler($path, $file, $this->truncate($file), $this->getIo());
      $parser->execute();
    }
  }

  /**
   * Evaluate if the table should truncate or not.
   */
  protected function truncate($file) {
    $appendRecords = [
      '2018-400NG',
      'discounts-1Jan2018',
    ];
    if (in_array($file, $appendRecords)) {
      // Append these file records instead of truncate table.
      return FALSE;
    }
    return TRUE;
  }

}
