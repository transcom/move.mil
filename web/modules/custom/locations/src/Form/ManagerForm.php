<?php

namespace Drupal\locations\Form;

use Drupal\Component\Plugin\Exception\InvalidPluginDefinitionException;
use Drupal\Component\Plugin\Exception\PluginNotFoundException;
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
   * The Stream wrapper manager.
   *
   * @var \Drupal\Core\StreamWrapper\StreamWrapperManager
   */
  protected $swm;

  /**
   * ManagerForm constructor.
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

    $form['upload'] = [
      '#type' => 'details',
      '#title' => $this->t('Upload Locations File'),
    ];

    $form['upload']['file'] = [
      '#type' => 'managed_file',
      '#upload_location' => 'public://',
      '#title' => $this->t('Locations file to upload'),
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

    $form['actions']['#type'] = 'actions';

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Update Locations'),
      '#button_type' => 'primary',
    ];

    // By default, render the form using system-config-form.html.twig.
    $form['#theme'] = 'system_config_form';
    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    if (stripos($form_state->getValue('op'), 'geolocation')) {
      return;
    }
    // If the actions is 'update' or 'delete', verify we have a file uploaded.
    $locations = $form_state->getValue('upload');
    if (empty($locations['file'])) {
      $form_state->setErrorByName(
        'locations',
        $this->t('Please add the file to upload.')
      );
    }
  }

  /**
   * Get file id from the uploaded file.
   */
  private function getFileId(FormStateInterface $form_state) {
    $locations = $form_state->getValue('upload');
    if (array_key_exists(0, $locations['file'])) {
      return intval($locations['file'][0]);
    }
    return 0;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $fid = $this->getFileId($form_state);
    if (empty($fid)) {
      $this->messenger()->addError('Error: Empty file.');
      return;
    }
    try {
      $file = $this->entity->getStorage('file')->load($fid);
      $stream_wrapper_manager = $this->swm->getViaUri($file->getFileUri());
      $filePath = $stream_wrapper_manager->realpath();
      $this->updateLocations($filePath);
    }
    catch (\Exception $e) {
      $this->messenger()->addError('Error: ' . $e->getMessage());
    }
  }

  /**
   * Reads the content, executes actions on Drupal locations.
   *
   * @throws \Exception
   */
  protected function updateLocations($filePath) {
    $batchSize = 50;
    $batch = array(
      'title' => t('Updating Drupal Locations...'),
      'operations' => [
        ['\Drupal\locations\Service\Reader::parse', [$filePath]],
        ['\Drupal\locations\Service\Writer::update', [$batchSize]],
        ['\Drupal\locations\Service\Writer::deleteLocations', [$batchSize]],
      ],
      'progress_message' => t('Processed @current out of @total operations.'),
      'error_message'    => t('An error occurred during processing'),
      'finished' => '\Drupal\locations\Service\Writer::finishedCallback',
    );
    batch_set($batch);
  }

}
