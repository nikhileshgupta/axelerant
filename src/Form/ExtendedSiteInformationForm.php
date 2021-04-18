<?php

namespace Drupal\axelerant\Form;

use Drupal\Core\Form\FormStateInterface;
use Drupal\system\Form\SiteInformationForm;

/**
 * Extended Form for Site Information settings.
 */
class ExtendedSiteInformationForm extends SiteInformationForm {

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $site_config = $this->config('system.site');
    $form =  parent::buildForm($form, $form_state);
    $form['site_information']['siteapikey'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Site API Key'),
      '#default_value' => $site_config->get('siteapikey') ?: 'No API Key yet',
      '#description' => $this->t("Axelerant Site API Key."),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $key = $form_state->getValue('siteapikey');
    $this->config('system.site')
      ->set('siteapikey', $key)
      ->save();
    parent::submitForm($form, $form_state);
    $this->messenger->addStatus($this->t('Site API Key has been saved with @key value.', ['@key' => $key]));
  }

}
