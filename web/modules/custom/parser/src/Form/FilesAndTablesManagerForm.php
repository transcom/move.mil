<?php

namespace Drupal\parser\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

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
    $form['line_hauls_pack_unpack_short_hau'] = [
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

    $this->config('parser.filesandtablesmanager')
      ->set('zip_3', $form_state->getValue('zip_3'))
      ->set('zip_5', $form_state->getValue('zip_5'))
      ->set('line_hauls_pack_unpack_short_hau', $form_state->getValue('line_hauls_pack_unpack_short_hau'))
      ->save();
  }

}
