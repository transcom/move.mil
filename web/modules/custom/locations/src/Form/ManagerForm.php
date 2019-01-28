<?php

namespace Drupal\locations\Form;

use Drupal\Core\Config\ConfigFactoryInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\Core\Database\Connection;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Entity\EntityTypeManager;
use Drupal\locations\Service\Reader;
use Drupal\locations\Service\Writer;
use Drupal\Core\StreamWrapper\StreamWrapperManager;

/**
 * Class FilesAndTablesManagerForm.
 */
class ManagerForm extends ConfigFormBase {

  /**
   * Variable containing the database connection.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $db;

  /**
   * Variables containing the entitytypemanager service.
   *
   * @var \Drupal\Core\Entity\EntityTypeManager
   */
  protected $entity;

  /**
   * Variables containing the databaseWriter service.
   *
   * @var \Drupal\locations\Service\Writer
   */
  protected $writer;

  /**
   * Variables containing the Reader service.
   *
   * @var \Drupal\locations\Service\Reader
   */
  protected $reader;

  /**
   * FilesAndTablesManagerForm constructor.
   *
   * Needed for dependency injection.
   */
  public function __construct(ConfigFactoryInterface $config_factory,
                              Connection $db,
                              EntityTypeManager $entity,
                              Writer $writer,
                              Reader $reader,
                              StreamWrapperManager $swm) {
    parent::__construct($config_factory);
    $this->db = $db;
    $this->entity = $entity;
    $this->writer = $writer;
    $this->reader = $reader;
    $this->swm = $swm;
  }

  /**
   * {@inheritdoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('config.factory'),
      $container->get('database'),
      $container->get('entity_type.manager'),
      $container->get('locations.writer'),
      $container->get('locations.reader'),
      $container->get('stream_wrapper_manager')
    );
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'locations.manager',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'manager_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['#tree'] = TRUE;

    $form['description'] = [
      '#markup' => '<p>' . $this->t('Manage the data used by the Locator Maps tool') . '</p>',
    ];

    $form['update'] = [
      '#type' => 'details',
      '#title' => $this->t('Update Locations'),
    ];

    $form['update']['file'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Locations'),
      '#upload_validators' => [
        'file_validate_extensions' => ['txt'],
        'file_validate_size' => [20000000],
      ],
    ];

    $form['update']['link'] = [
      '#title' => $this->t('What location entities do we have?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('view.content.page_1'),
    ];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $groups = $form_state->getValues();
    $action = NULL;

    foreach ($groups as $key => $group) {
      $continue = FALSE;
      $read_info = '';

      if ($continue == TRUE) {
        continue;
      }
      $fid = array_key_exists(0, $group['file']) ? intval($group['file'][0]) : 0;
      $this->execute($fid, $key);
    }
  }

  /**
   * Checks FID, reads the content, executes actions on Drupal locations.
   */
  protected function execute($fid, $key) {
    if ($fid != 0) {
      try {
        $file = $this->entity->getStorage('file')->load($fid);
        $uri = $file->getFileUri();
        $stream_wrapper_manager = $this->swm->getViaUri($uri);
        $file_path = $stream_wrapper_manager->realpath();
        $rawdata = $this->reader->parse($file_path);
        $this->writer->write($rawdata);

        $this->messenger()->addMessage("Locations " . $key . "d");
      }
      catch (\Exception $e) {
        $this->messenger()
          ->addError('Exception on file, ' . $e->getMessage());
      }
      catch (\TypeError $e) {
        $this->messenger()
          ->addError('Error on file, ' . $e->getMessage());
      }
    }
  }

}
