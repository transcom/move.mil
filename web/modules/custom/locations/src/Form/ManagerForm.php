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

    $num_filters = $form_state->get('num_filters');
    if ($num_filters === NULL) {
      $form_state->set('num_filters', 1);
      $num_filters = 1;
    }

    $form['filters_fieldset'] = [
      '#type' => 'fieldset',
      '#title' => $this->t('Filters'),
      '#prefix' => '<div id="filters-fieldset-wrapper">',
      '#suffix' => '</div>',
    ];

    for ($i = 0; $i < $num_filters; $i++) {
      $form['filters_fieldset']['filter'][$i] = [
        '#type' => 'number',
        '#title' => $this->t('Filter ID'),
      ];
    }

    $form['filters_fieldset']['actions'] = [
      '#type' => 'actions',
    ];
    $form['filters_fieldset']['actions']['add_filter'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add filter'),
      '#submit' => ['::addOne'],
      '#ajax' => [
        'callback' => '::addmoreCallback',
        'wrapper' => 'filters-fieldset-wrapper',
      ],
    ];

    if ($num_filters > 1) {
      $form['filters_fieldset']['actions']['remove_filter'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::removeCallback'],
        '#ajax' => [
          'callback' => '::addmoreCallback',
          'wrapper' => 'filters-fieldset-wrapper',
        ],
      ];
    }

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
  public function addmoreCallback(array &$form, FormStateInterface $form_state) {
    return $form['filters_fieldset'];
  }

  /**
   * {@inheritdoc}
   */
  public function addOne(array &$form, FormStateInterface $form_state) {
    $filter_field = $form_state->get('num_filters');
    $form_state->set('num_filters', $filter_field + 1);
    $form_state->setRebuild();
  }

  /**
   * {@inheritdoc}
   */
  public function removeCallback(array &$form, FormStateInterface $form_state) {
    $filter_field = $form_state->get('num_filters');
    if ($filter_field > 1) {
      $form_state->set('num_filters', $filter_field - 1);
    }
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
    $fid = $this->getFileId($form_state);
    if (empty($fid)) {
      $this->messenger()->addError('Error: Empty file.');
      return;
    }
    try {
      $file = $this->entity->getStorage('file')->load($fid);
      $stream_wrapper_manager = $this->swm->getViaUri($file->getFileUri());
      $filePath = $stream_wrapper_manager->realpath();
      $ignored = $form_state->getValue(['filters_fieldset', 'filter']);
      $xml = $this->reader->parse($filePath, $ignored);
      $this->updateLocations($xml);
      $this->deleteOldLocations($xml);
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
      'progress_message' => 'Updated @current out of @total locations, elapsed time: @elapsed',
      'error_message'    => 'An error occurred during processing',
      'finished' => '\Drupal\locations\Service\Writer::finishedUpdateCallback',
    ];
    foreach ($xmlOffices as $office) {
      $batch['operations'][] = ['\Drupal\locations\Service\Writer::update', [$office]];
    }
    batch_set($batch);
  }

  /**
   * Deletes Drupal locations not present in XML file.
   */
  protected function deleteOldLocations($xmlOffices) {
    $batchSize = 50;
    $batch = [
      'title' => 'Deleting Drupal Locations not present in XML file...',
      'operations' => [
        [
          '\Drupal\locations\Service\Writer::deleteLocations',
          [$batchSize, $xmlOffices],
        ],
      ],
      'progress_message' => 'Deleting old locations: @percentage% processed. @elapsed',
      'error_message'    => 'An error occurred during processing',
      'finished' => '\Drupal\locations\Service\Writer::finishedDeleteCallback',
    ];
    batch_set($batch);
  }

}
