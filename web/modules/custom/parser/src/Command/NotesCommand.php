<?php

namespace Drupal\parser\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Console\Core\Command\ContainerAwareCommand;
use Drupal\Console\Core\Style\DrupalStyle;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;

/**
 * Class NotesCommand.
 *
 * @Drupal\Console\Annotations\DrupalCommand (
 *     extension="parser",
 *     extensionType="module"
 * )
 */
class NotesCommand extends ContainerAwareCommand {

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
  protected $entityTypeManager;

  /**
   * Constructs a new NotesCommand object.
   */
  public function __construct(Connection $database, EntityTypeManager $entityTypeManager) {
    $this->database = $database;
    $this->entityTypeManager = $entityTypeManager;
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('notes')
      ->setDescription($this->trans('commands.notes.description'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $io = new DrupalStyle($input, $output);
    $io->text("Copying CKeditor notes to PlainText notes.");
    try {
      $locations = $this->entityTypeManager
        ->getStorage('node')
        ->loadByProperties([
          'type' => 'location',
        ]);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      $io->error('Exception on location node, ' . $ipde->getMessage());
      exit(-1);
    }
    $locationsCount = 0;
    foreach ($locations as $location) {
      if (!empty($location)) {
        $oldNoteField = $location
          ->get('field_location_note')
          ->getValue();
        if (empty($oldNoteField)) {
          continue;
        }
        $htmlNote = $oldNoteField[0]['value'];
        $notes = strip_tags($htmlNote);
        $location->set('field_location_notes', $notes);
        try {
          $location->save();
        }
        catch (EntityStorageException $e) {
          $io->error('An error occurred while trying to update the location entity, ' . $e->getMessage());
          exit(-1);
        }
        $io->text($location->label() . ' notes ' . $notes);
        $locationsCount++;
      }
    }
    $io->success($locationsCount . ' locations changed successful');
  }

}
