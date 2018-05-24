<?php

namespace Drupal\parser\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\parser\Reader\CsvReader;
use Drupal\parser\Reader\DecryptReader;
use Drupal\parser\Reader\ExcelReader;
use Drupal\parser\Reader\YamlReader;
use Drupal\parser\Writer\DB\EntitlementsWriter;
use Drupal\parser\Writer\DB\Rates400NGWriter;
use Drupal\parser\Writer\DB\CsvWriter;
use Drupal\parser\Writer\DB\DiscountWriter;
use Drupal\parser\Writer\DB\ZipCodesWriter;
use Drupal\Core\Url;

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

    $form['zip_3'] = array(
      '#type' => 'details',
      '#title' => $this
        ->t('Zip 3'),
    );
    $form['zip_3']['file'] = [
      '#type' => 'file',
      '#title' => $this->t('Zip 3'),
      '#description' => $this->t('Upload zip3.csv file'),
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
      '#type' => 'file',
      '#title' => $this->t('Zip 5'),
      '#description' => $this->t('Upload zip5.csv file'),
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
      '#type' => 'file',
      '#title' => $this->t('Line hauls, pack/unpack, short hauls, service areas'),
      '#description' => $this->t('Upload YYYY-400NG.xlsx file'),
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
      '#type' => 'file',
      '#title' => $this->t('Entitlements'),
      '#description' => $this->t('Upload entitlements.yml file'),
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
      '#type' => 'file',
      '#title' => $this->t('Discounts'),
      '#description' => $this->t('Upload discounts.xlsx file'),
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
      '#type' => 'file',
      '#title' => $this->t('Zip codes'),
      '#description' => $this->t('Upload all_us_zipcodes.csv file'),
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
    parent::submitForm($form, $form_state);
    $all_files = $this->getRequest()->files->get('files', []);

    foreach ($all_files as $key => $file) {
      if (!empty($file)) {
        if ($file->isValid()) {

          $filename = $file->getRealPath();

          switch ($key) {
            case (preg_match('/^zip_[\d]+/', $key) ? TRUE : FALSE):
              $reader = new CsvReader();
              $writer = new CsvWriter($key);
              //$truncate = form['zip_3']['truncate'];
              break;

            case '400NG':
              $reader = new ExcelReader(2017);//$file['year']);
              $writer = new Rates400NGWriter();
              //$truncate = form['zip_5']['truncate'];
              break;

            case 'entitlements':
              $reader = new YamlReader();
              $writer = new EntitlementsWriter();
             // $truncate = form['entitlements']['truncate'];
              break;

            case 'discounts':
              $date = form['discounts']['date'];
              $reader = new DecryptReader();
              $writer = new DiscountWriter($date->getValue());
             // $truncate = form['discounts']['truncate'];
              break;

            case 'all_us_zipcodes':
              $reader = new CsvReader();
              $writer = new ZipCodesWriter($key);
//              $truncate = form['all_us_zipcodes']['truncate'];
              break;

          }

          $rawdata = $reader->parse($filename);
          $writer->write($rawdata, TRUE); //$truncate->getValue());
        }
      }
    }
  }

}
