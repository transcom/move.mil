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
   * Global configuration settings var.
   *
   * @var string Config settings
   */
  const SETTINGS = 'locations.settings';

  /**
   * ManagerForm constructor.
   *
   * Needed for dependency injection.
   */
  public function __construct(ConfigFactoryInterface $config_factory,
                              Connection $db,
                              EntityTypeManager $entity,
                              Reader $reader,
                              StreamWrapperManager $swm) {
    parent::__construct($config_factory);
    $this->db = $db;
    $this->entity = $entity;
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
      static::SETTINGS,
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
    $config = $this->config(static::SETTINGS);
    $configExclusions = $config->get('exclusions');

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

    $exclusionsCount = $form_state->get('exclusions_count');
    if ($exclusionsCount === NULL) {
      $count = count($configExclusions) + 1;
      $form_state->set('exclusions_count', $count);
      $exclusionsCount = $count;
    }

    $form['exclusions_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Exclusion List'),
      '#prefix' => '<div id="exclusions-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    for ($i = 0; $i < $exclusionsCount; $i++) {
      $form['exclusions_fieldset']['exclusions'][$i] = [
        '#type' => 'number',
        '#title' => $this->t('Office ID'),
        '#default_value' => $configExclusions[$i],
      ];
    }

    $form['exclusions_fieldset']['actions'] = [
      '#type' => 'actions',
    ];
    $form['exclusions_fieldset']['actions']['add_exclusion'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add another office'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addMoreCallback',
        'wrapper' => 'exclusions-fieldset-wrapper',
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
  public function addMoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['exclusions_fieldset'];
  }

  /**
   * {@inheritdoc}
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $exclusion_count = $form_state->get('exclusions_count');
    $form_state->set('exclusions_count', $exclusion_count + 1);
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
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
    $exclusions = array_filter($form_state->getValue(['exclusions_fieldset', 'exclusions']));
    // Retrieve the configuration.
    $this->configFactory->getEditable(static::SETTINGS)
      // Set exclusions on config.
      ->set('exclusions', $exclusions)
      ->save();

    $fid = $this->getFileId($form_state);
    if (empty($fid)) {
      $this->messenger()->addError('Error: Empty file.');
      return;
    }
    try {
      $file = $this->entity->getStorage('file')->load($fid);
      $stream_wrapper_manager = $this->swm->getViaUri($file->getFileUri());
      $filePath = $stream_wrapper_manager->realpath();
      $xml = $this->reader->parse($filePath, $exclusions);
      $this->updateLocations($xml);
      if ($exclusions) {
        $this->deleteLocations($exclusions);
      }
    }
    catch (\Exception $e) {
      $this->messenger()->addError('Error: ' . $e->getMessage());
    }
  }

  /**
   * Reads the content, executes actions on Drupal locations.
   */
  protected function updateLocations($xmlOffices) {
    $batch = [
      'title' => 'Updating Drupal Locations...',
      'operations' => [],
      'progress_message' => 'Updated @current out of @total locations, elapsed time: @elapsed, estimated time: @estimate',
      'error_message'    => 'An error occurred during processing',
      'finished' => '\Drupal\locations\Service\Writer::finishedUpdateCallback',
    ];
    foreach ($xmlOffices as $office) {
      $batch['operations'][] = ['\Drupal\locations\Service\Writer::update', [$office]];
    }
    batch_set($batch);
  }

  /**
   * Deletes Drupal locations to be excluded if present.
   */
  protected function deleteLocations($exclusions) {
    $batch = [
      'title' => 'Deleting excluded Drupal Locations if present.',
      'operations' => [],
      'progress_message' => 'Processed @current out of @total exclusions.',
      'error_message'    => 'An error occurred during processing',
      'finished' => '\Drupal\locations\Service\Writer::finishedDeleteCallback',
    ];
    foreach ($exclusions as $exclusion) {
      $batch['operations'][] = ['\Drupal\locations\Service\Writer::delete', [$exclusion]];
    }
    batch_set($batch);
  }

}
