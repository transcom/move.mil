<?php

namespace Drupal\parser\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\parser\Reader\CsvReader;
use Drupal\parser\Reader\DiscountReader;
use Drupal\parser\Reader\ExcelReader;
use Drupal\parser\Reader\YamlReader;
use Drupal\parser\Writer\DB\EntitlementsWriter;
use Drupal\parser\Writer\DB\Rates400NGWriter;
use Drupal\parser\Writer\DB\CsvWriter;
use Drupal\parser\Writer\DB\DiscountWriter;
use Drupal\parser\Writer\DB\ZipCodesWriter;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Class FilesAndTablesManagerForm.
 */
class FilesAndTablesManagerForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'parser.filesandtablesmanager',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'files_and_tables_manager_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config('parser.filesandtablesmanager');

    $form['#tree'] = TRUE;

    $form['zip_3'] = array(
      '#type' => 'details',
      '#title' => $this
        ->t('Zip 3'),
    );
    $form['zip_3']['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Zip_3'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    ];
    $form['zip_3']['truncate'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('Clean table (removes all data previously stored)'),
    );
    $form['zip_3']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('<front>'),
    ];

    $form['zip_5'] = array(
      '#type' => 'details',
      '#title' => $this
        ->t('Zip 5'),
    );

    $form['zip_5']['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Zip_5'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    ];

    $form['zip_5']['truncate'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('Clean table (removes all data previously stored)'),
    );
    $form['zip_5']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('<front>'),
    ];

    $form['400NG'] = array(
      '#type' => 'details',
      '#title' => $this
        ->t('400NG'),
    );
    $form['400NG']['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('400NG'),
      '#upload_validators' => [
        'file_validate_extensions' => ['xlsx'],
      ],
    ];
    $form['400NG']['year'] = [
      '#type' => 'select',
      '#default_value' => NULL,
      '#title' => $this
        ->t('Select the year in which these rates are effective'),
      '#options' => [
        '2018' => $this->t('2018'),
        '2019' => $this->t('2019'),
        '2020' => $this->t('2020'),
        '2021' => $this->t('2021'),
        '2022' => $this->t('2022'),
        '2023' => $this->t('2023'),
        '2024' => $this->t('2024'),
        '2025' => $this->t('2025'),
        '2026' => $this->t('2026'),
        '2027' => $this->t('2027'),
        '2028' => $this->t('2028'),
        '2029' => $this->t('2029'),
        '2030' => $this->t('2030'),
      ],
    ];
    $form['400NG']['truncate'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('Clean table (removes all data previously stored)'),
    );
    $form['400NG']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('<front>'),
    ];

    $form['entitlements'] = array(
      '#type' => 'details',
      '#title' => $this
        ->t('Entitlements'),
    );
    $form['entitlements']['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Entitlements'),
      '#upload_validators' => [
        'file_validate_extensions' => ['yml'],
      ],
    ];
    $form['entitlements']['truncate'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('Clean table (removes all data previously stored)'),
    );
    $form['entitlements']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('<front>'),
    ];

    $form['discounts'] = array(
      '#type' => 'details',
      '#title' => $this
        ->t('Discounts'),
    );
    $form['discounts']['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Discount'),
      '#upload_validators' => [
        'file_validate_extensions' => ['xlsx'],
      ],
    ];
    $form['discounts']['effective_date'] = array(
      '#type' => 'date',
      '#title' => $this
        ->t('Please choose the effective date for this file'),
    );
    $form['discounts']['truncate'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('Clean table (removes all data previously stored)'),
    );
    $form['discounts']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('<front>'),
    ];

    $form['zipcodes'] = array(
      '#type' => 'details',
      '#title' => $this
        ->t('Zip codes'),
    );
    $form['zipcodes']['file'] = [
      '#type' => 'managed_file',
      '#title' => $this->t('Zipcodes'),
      '#upload_validators' => [
        'file_validate_extensions' => ['csv'],
      ],
    ];
    $form['zipcodes']['truncate'] = array(
      '#type' => 'checkbox',
      '#title' => $this
        ->t('Clean table (removes all data previously stored)'),
    );
    $form['zipcodes']['link'] = [
      '#title' => $this->t('What data is in this table?'),
      '#type' => 'link',
      '#url' => Url::fromRoute('<front>'),
    ];

    unset($form['400NG']['year']['#options']['_none']);
    $form['400NG']['year']['#options'] = ['' => 'Select'] + $form['400NG']['year']['#options'];

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    parent::validateForm($form, $form_state);
    $discounts = $form_state->getValue('discounts');

    if (!empty($discounts['file']) && empty($discounts['effective_date'])) {
      $form_state->setErrorByName('discounts', $this->t('Please fill in the effective date.'));
    }

    $ng400 = $form_state->getValue('400NG');
    if (!empty($ng400['file']) && empty($ng400['year'])) {
      $form_state->setErrorByName('year', $this->t('Please fill in the current year.'));
    }

  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $groups = $form_state->getValues();
    $message = "";
    foreach ($groups as $key => $group) {
      $group_data = $groups[$key];

      switch ($key) {
        case 'zip_3':
          $table = 'parser_zip3s';
          $reader = new CsvReader();
          $writer = new CsvWriter($key);
          break;

        case 'zip_5':
          $table = 'parser_zip5s';
          $reader = new CsvReader();
          $writer = new CsvWriter($key);
          break;

        case '400NG':
          $table = ['parser_service_areas', 'parser_linehauls',
            'parser_shorthauls', 'parser_packunpacks',
          ];
          $reader = new ExcelReader($group_data['year']);
          $writer = new Rates400NGWriter();
          break;

        case 'entitlements':
          $table = 'parser_entitlements';
          $reader = new YamlReader();
          $writer = new EntitlementsWriter();
          break;

        case 'discounts':
          $date = $group_data['effective_date'];
          $table = 'parser_discounts';
          $reader = new DiscountReader();
          $writer = new DiscountWriter($date);
          break;

        case 'zipcodes':
          $table = 'parser_zipcodes';
          $reader = new CsvReader();
          $writer = new ZipCodesWriter($group);
          break;

        default:
          $continue = TRUE;
      }

      if ($continue) {
        continue;
      }

      $message .= $key . ':';

      if ($group_data['truncate']) {
        $message .= "\ttruncated";
        if (is_array($table)) {
          foreach ($table as $sep) {
            \Drupal::database()->truncate($sep)
              ->execute();
          }
        }
        else {
          \Drupal::database()->truncate($table)
            ->execute();
        }
      }

      if (intval($group_data['file'][0]) != 0) {
        $message .= "\tparsed";
        $file = File::load($group_data['file'][0]);
        $filename = file_directory_temp() . '/' . $file->get('filename')->value;
        $rawdata = $reader->parse($filename);
        $writer_status = $writer->write($rawdata);
      }
      $message .= "\t, ";
    }
    drupal_set_message($this->t($message));
  }

}
