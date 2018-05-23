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
    $form['zip_3'] = [
      '#type' => 'file',
      '#title' => $this->t('Zip 3'),
      '#description' => $this->t('Upload your zip3.csv file'),
      '#default_value' => $config->get('zip_3'),
    ];
    $form['zip_5'] = [
      '#type' => 'file',
      '#title' => $this->t('Zip 5'),
      '#description' => $this->t('Upload your zip5.csv file'),
      '#default_value' => $config->get('zip_5'),
    ];
    $form['400NG'] = [
      '#type' => 'file',
      '#title' => $this->t('Line hauls, pack/unpack, short hauls, service areas'),
      '#description' => $this->t('Upload your YYYY-400NG.xlsx file'),
      '#default_value' => $config->get('line_hauls_pack_unpack_short_hau'),
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
