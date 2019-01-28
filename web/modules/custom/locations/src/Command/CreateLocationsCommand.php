<?php

namespace Drupal\locations\Command;

use Drupal\Console\Core\Style\DrupalStyle;
use Drupal\Console\Core\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\locations\Service\Reader;
use Drupal\locations\Service\Writer;

/**
 * Class CreateLocationsCommand.
 *
 * @DrupalCommand (
 *     extension="locations",
 *     extensionType="module"
 * )
 */
class CreateLocationsCommand extends ContainerAwareCommand {

  protected $reader;
  protected $writer;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('CreateLocations')
      ->setDescription($this->trans('commands.CreateLocations.description'));
  }

  /**
   * Locations constructor.
   */
  public function __construct(Reader $reader, Writer $writer) {
    $this->reader = $reader;
    $this->writer = $writer;
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('locations.reader'),
      $container->get('locations.writer')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new DrupalStyle($input, $output);
    $path = DRUPAL_ROOT . '/../lib/data';

    $filename = [
      "{$path}/locations.xml",
    ];

    $io->text('Reading files.');
    $rawdata = $this->reader->parse($filename);
    $io->text('Storing in Drupal...');
    $this->writer->write($rawdata);
    $io->success('Done!');
  }

}
