<?php

namespace Drupal\parser\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputOption;
use Drupal\Console\Core\Command\ContainerAwareCommand;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;

/**
 * Class TruncateCommand.
 *
 * @Drupal\Console\Annotations\DrupalCommand (
 *     extension="parser",
 *     extensionType="module"
 * )
 */
class TruncateCommand extends ContainerAwareCommand {

  /**
   * Drupal\Core\Database\Driver\mysql\Connection definition.
   *
   * @var \Drupal\Core\Database\Driver\mysql\Connection
   */
  protected $database;

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $etm;

  /**
   * Constructs a new TruncateCommand object.
   */
  public function __construct(Connection $database, EntityTypeManager $etm) {
    $this->database = $database;
    $this->etm = $etm;
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('truncate')
      ->setDescription($this->trans('commands.truncate.description'))
      ->addOption(
        'type',
        NULL,
        InputOption::VALUE_REQUIRED,
        $this->trans('commands.truncate.options.type')
      );
  }

  /**
   * {@inheritdoc}
   */
  protected function interact(InputInterface $input, OutputInterface $output) {
    if (!$input->getOption('type')) {
      $type = $this->getIo()->choiceNoList(
        'Which location type?',
        ['Shipping Office', 'Transportation Office', 'Weight Scale']
      );
      $input->setOption('type', $type);
    }
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $type = $input->getOption('type');
    $this->getIo()->text('Deleting all ' . $type . 's');
    $nodeStorage = NULL;
    try {
      $nodeStorage = $this->etm->getStorage('node');
    }
    catch (PluginNotFoundException $exception) {
      $this->getIo()->error('Exception on location node, ' . $exception->getMessage());
      exit(-1);
    }
    catch (InvalidPluginDefinitionException $exception) {
      $this->getIo()->error('Exception on location node, ' . $exception->getMessage());
      exit(-1);
    }
    $location_type = $this->database
      ->select('taxonomy_term_field_data', 't')
      ->fields('t', ['tid'])
      ->condition('name', $type, '=')
      ->execute()
      ->fetchField();
    $locations = $nodeStorage
      ->loadByProperties([
        'type' => 'location',
        'field_location_type' => [
          'target_id' => $location_type,
          'target_type' => "taxonomy_term",
        ],
      ]);
    $this->getIo()->text('Found: ' . count($locations) . ' ' . $type . 's');
    try {
      $nodeStorage->delete($locations);
    }
    catch (EntityStorageException $exception) {
      $this->getIo()->error('Error when deleting node, ' . $exception->getMessage());
      exit(-1);
    }
    $this->getIo()->success("Deletion successful");
  }

}
