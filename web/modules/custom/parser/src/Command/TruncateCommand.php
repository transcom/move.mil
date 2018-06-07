<?php

namespace Drupal\parser\Command;

use Drupal\Driver\Exception\Exception;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Console\Core\Command\ContainerAwareCommand;
use Drupal\Core\Entity\EntityTypeManager;

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
      ->setDescription($this->trans('commands.truncate.description'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new SymfonyStyle($input, $output);
    $io->text("Deleting all locations");
    $db_objs = $this->database
      ->select('node', 'n')
      ->fields('n', ['nid'])
      ->condition('n.type', 'location', '=')
      ->execute()
      ->fetchCol();
    $io->text("Found : " . count($db_objs) . " locations");

    $storageHandler = $this->etm->getStorage("node");
    $nodeEntities = $storageHandler->loadMultiple($db_objs);
    $storageHandler->delete($nodeEntities);

    $io->success("Deletion successful");
  }

}

