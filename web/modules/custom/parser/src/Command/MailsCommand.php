<?php

namespace Drupal\parser\Command;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Drupal\Core\Database\Driver\mysql\Connection;
use Drupal\Core\Entity\EntityStorageException;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\Console\Core\Command\ContainerAwareCommand;
use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;

/**
 * Class NotesCommand.
 *
 * @Drupal\Console\Annotations\DrupalCommand (
 *     extension="parser",
 *     extensionType="module"
 * )
 */
class MailsCommand extends ContainerAwareCommand {

  /**
   * Drupal\Core\Entity\EntityTypeManager definition.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entityTypeManager;

  /**
   * Constructs a new NotesCommand object.
   */
  public function __construct(EntityTypeManager $entityTypeManager) {
    $this->entityTypeManager = $entityTypeManager;
    parent::__construct();
  }

  /**
   * {@inheritdoc}
   */
  protected function configure() {
    $this
      ->setName('mails')
      ->setDescription($this->trans('commands.mails.description'));
  }

  /**
   * {@inheritdoc}
   */
  protected function execute(InputInterface $input, OutputInterface $output) {
    $this->getIo()->text("Copying e-mail field to PlainText.");
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => 'Weight Scale']);
    $term = current($terms);
    $location_type = $term->id();
    try {
      $locations = $this->entityTypeManager
        ->getStorage('node')
        ->loadByProperties([
          'type' => 'location',
          'field_location_type' => [
            'target_id' => $location_type,
            'target_type' => "taxonomy_term",
          ],
        ]);
    }
    catch (InvalidPluginDefinitionException $ipde) {
      $this->getIo()->error('Exception on location node, ' . $ipde->getMessage());
      exit(-1);
    }
    $locationsCount = 0;
    foreach ($locations as $location) {
      if (!empty($location)) {
        $oldEmailField = $location
          ->get('field_location_email')
          ->getValue();
        if (!empty($oldEmailField)) {
          $oldEmailValue = $oldEmailField[0]['value'];
          $location->set('field_location_email_address', ['Email%' . $oldEmailValue]);
          try {
            $location->save();
          }
          catch (EntityStorageException $e) {
            $this->getIo()->error('An error occurred while trying to update the location entity, ' . $e->getMessage());
            exit(-1);
          }
          $this->getIo()->text($location->label() . ' e-mail ' . $oldEmailValue);
          $locationsCount++;
        }

        $phone_references = $location
          ->get('field_location_telephone')
          ->getValue();
        foreach ($phone_references as $ref) {
          $id = $ref['target_id'];
          $phone = \Drupal::entityTypeManager()
            ->getStorage('paragraph')
            ->load($id);
          $oldTypeField = $phone
            ->get('field_type')
            ->getValue();
          if (!empty($oldTypeField)) {
            $oldTypeValue = $oldTypeField[0]['value'];
            $voice = $oldTypeValue == 'voice';
            $phone->set('field_voice', $voice);
            $phone->set('field_type', 'Customer Service');
            $phone->save();
          }
        }
      }
    }
    $this->getIo()->success($locationsCount . ' locations changed successful');
  }

}
