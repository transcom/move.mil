<?php

namespace Drupal\parser\Command;

use Drupal\Console\Core\Style\DrupalStyle;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\parser\Service\LocationReader;
use Drupal\parser\Service\LocationWriter;
use Drupal\Console\Core\Command\ContainerAwareCommand;

/**
 * Class ParserCommand.
 *
 * @Drupal\Console\Annotations\DrupalCommand (
 *     extension="parser",
 *     extensionType="module"
 * )
 */
class ParserCommand extends ContainerAwareCommand {

  /**
   * Drupal\parser\Service\LocationReader definition.
   *
   * @var \Drupal\parser\Service\LocationReader
   */
  protected $reader;

  /**
   * Drupal\parser\Service\LocationWriter definition.
   *
   * @var \Drupal\parser\Service\LocationWriter
   */
  protected $writer;

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('parser')
      ->setDescription($this->trans('commands.parser.description'));
  }

  /**
   * LocationWriter constructor.
   *
   * Needed for the Paragraph dependency injection.
   */
  public function __construct(LocationReader $reader, LocationWriter $writer) {
    $this->reader = $reader;
    $this->writer = $writer;
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('parser.location_reader'),
      $container->get('parser.location_writer')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new DrupalStyle($input, $output);
    $path = DRUPAL_ROOT . '/../lib/data';

    $filename = [
      "{$path}/shipping_offices.json",
      "{$path}/transportation_offices.json",
      "{$path}/weight_scales.json",
    ];

    $io->text('Reading files.');
    $rawdata = $this->reader->parse($filename);
    $io->text('Writing to database.');
    $this->writer->write($rawdata);
    $io->success('Done!');
  }

}
