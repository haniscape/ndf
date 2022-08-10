<?php

namespace Drupal\ndf_settings\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure NWC Module settings for this site.
 */
class NDFSettingsForm extends ConfigFormBase {

  /**
   * Form id.
   *
   * @var string
   */
  public const FORM_ID = 'ndf_settings';

  /**
   * Form settings name.
   *
   * @var string
   */
  public const CONFIG_SETTINGS = 'ndf_settings.settings';

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return self::FORM_ID;
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames(): array {
    return [self::CONFIG_SETTINGS];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->config(self::CONFIG_SETTINGS);
    // Elements for contact us.
    $form['contact_us'] = [
      '#type'  => 'details',
      '#open'  => FALSE,
      '#title' => $this->t('Contact us'),
    ];
    $form['contact_us']['email'] = [
      '#type'  => 'email',
      '#title' => $this->t('Email'),
      '#default_value' => $config->get('email'),
    ];
    $form['contact_us']['admin_email'] = [
      '#type'  => 'email',
      '#title' => $this->t('Administrator Email'),
      '#default_value' => $config->get('admin_email'),
    ];
    $form['contact_us']['address'] = [
      '#type'  => 'textfield',
      '#title' => $this->t('Address'),
      '#default_value' => $config->get('address'),
    ];
    $form['contact_us']['fax'] = [
      '#type'  => 'textfield',
      '#title' => $this->t('Fax'),
      '#default_value' => $config->get('fax'),
    ];
    $form['contact_us']['phone'] = [
      '#type'  => 'textfield',
      '#title' => $this->t('Phone'),
      '#default_value' => $config->get('phone'),
    ];
    // Elements for social links.
    $form['social_links'] = [
      '#type'  => 'details',
      '#open'  => FALSE,
      '#title' => $this->t('Social Links'),
    ];
    $form['social_links']['facebook_url'] = [
      '#type'  => 'textfield',
      '#title' => $this->t('Facebook Link'),
      '#default_value' => $config->get('facebook_url'),
      '#description' => $this->t('Begin link with https://'),
    ];
    $form['social_links']['twitter_url'] = [
      '#type'  => 'textfield',
      '#title' => $this->t('twitter Link'),
      '#default_value' => $config->get('twitter_url'),
      '#description' => $this->t('Begin link with https://'),
    ];
    $form['social_links']['instagram_url'] = [
      '#type'  => 'textfield',
      '#title' => $this->t('Instagram Link'),
      '#default_value' => $config->get('instagram_url'),
      '#description' => $this->t('Begin link with https://'),
    ];
    $form['social_links']['youtube_url'] = [
      '#type'  => 'textfield',
      '#title' => $this->t('Youtube Link'),
      '#default_value' => $config->get('youtube_url'),
      '#description' => $this->t('Begin link with https://'),
    ];
    // Elements for map location.
    $form['site_map_location'] = [
      '#type'  => 'details',
      '#open'  => FALSE,
      '#title' => $this->t('Map Location'),
    ];
    $form['site_map_location']['map_location'] = [
      '#type'  => 'textfield',
      '#open'  => TRUE,
      '#size'  => '600',
      '#maxlength' => 600,
      '#title' => $this->t('Map Location'),
      '#default_value' => $config->get('map_location'),
    ];
    // Elements for copyrights.
    $form['copyrights'] = [
      '#type'  => 'details',
      '#open'  => FALSE,
      '#title' => $this->t('Copyrights'),
    ];
    $form['copyrights']['copyrights_txt'] = [
      '#type'  => 'textfield',
      '#open'  => TRUE,
      '#size'  => '600',
      '#maxlength' => 600,
      '#title' => $this->t('Copyrights text'),
      '#default_value' => $config->get('copyrights_txt'),
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
    $config = $this->config(self::CONFIG_SETTINGS);
    $skip = ["submit", "form_build_id", "form_token", "form_id", "op"];
    foreach ($form_state->getValues() as $key => $value) {
      if (!in_array($key, $skip)) {
        $config->set($key, $value);
      }
    }
    $config->save();
  }

}
