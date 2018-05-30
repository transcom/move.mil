<?php

namespace Drupal\parser\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Console\Core\Command\Command;
use Symfony\Component\Console\Style\SymfonyStyle;
use Drupal\parser\Reader\LocationReader;
use Drupal\parser\Writer\DB\LocationWriter;


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
      ->setDescription($this->trans('commands.parser.description'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new SymfonyStyle($input, $output);
    $path = DRUPAL_ROOT . '/../lib/data';
    $this->getIo()->success($this->trans('commands.parser.messages.success'));

    $filename = [
      "{$path}/shipping_offices.json",
      "{$path}/transportation_offices.json",
      "{$path}/weight_scales.json",
    ];
    $reader = new LocationReader();
    $writer = new LocationWriter();

    $io->text('Reading files.');
    $rawdata = $reader->parse($filename);
    $io->text('Writing to database.');
    $writer->write($rawdata);
    $io->succces('Done!');
  }

}
