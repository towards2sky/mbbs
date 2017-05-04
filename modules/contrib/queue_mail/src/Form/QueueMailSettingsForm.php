<?php

namespace Drupal\queue_mail\Form;

use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;


/**
 * Configure pants settings for this site.
 */
class QueueMailSettingsForm extends ConfigFormBase {

  /**
   * {@inheritdoc}
   */
  public function getFormID() {
    return 'queue_mail_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return [
      'queue_mail.settings',
    ];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $config = $this->configFactory->get('queue_mail.settings');

    $form['queue_status'] = array(
      '#type' => 'item',
      '#title' => t('Queue status'),
      '#markup' => \Drupal::translation()->formatPlural(_queue_mail_get_queue()->numberOfItems(), '1 mail currently queued for sending.', '@count mails currently queued for sending.'),
      //'#description' => t('Sending of queued mails happens on cron. You can <a href="@cron_link">run cron manually</a>.', array('@cron_link' => url('admin/reports/status/run-cron', array('query' => drupal_get_destination())))),
    );

    $form['queue_mail_keys'] = array(
      '#type' => 'textarea',
      '#title' => t('Mail IDs to queue'),
      '#description' => t('Enter each mail ID to queue on a separate line. Use <strong>*</strong> to do a wildcard match.') .'<br/>'. t('Mail IDs are a combination of the first and second arguments sent to <em>drupal_mail</em> when a module sends an email. E.g. <em>user_mail</em>, <em>register_pending_approval_admin</em>') . '<br />' . t('For example, to queue all mails sent by the User module, enter: <em>user_*</em> above, to just queue password recovery emails enter: <em>user_password_reset</em>'),
      '#default_value' => $config->get('queue_mail_keys'),
    );

    // Get a list of modules that implement hook_mail.
    $mail_modules = \Drupal::moduleHandler()->getImplementations('mail');

    $rows = array();
    foreach ($mail_modules as $name) {
      $row = array();
      $row[] = $name;
      $row[] = $name . '_*';
      $rows[] = $row;
    }
    $headers = array(
      t('Module name'),
      t('Mail ID prefix'),
    );

    $form['tables'] = array(
      '#type' => 'table',
      '#caption' => $this->t('The following modules send emails. The contents of the second column can be used in the options above to queue the sending of those emails.'),
      '#header' => $headers,
      '#rows' => $rows,
      '#empty' => $this->t('No tables available.'),
    );

    $form['advanced'] = array(
      '#type' => 'details',
      '#title' => t('Advanced settings'),
      '#collapsed' => TRUE,
    );

    $options = array();
    for ($i = 5; $i <= 240; $i += 5) {
      $options[$i] = \Drupal::translation()->formatPlural($i, '1 second', '@count seconds');
    }

    $form['advanced']['queue_mail_queue_time'] = array(
      '#type' => 'select',
      '#title' => t('Queue processing time (max)'),
      '#description' => t('How much time in seconds to allow queue mail to send emails for on cron. Warning if you set a very high limit your cron run could timeout and never complete.'),
      '#options' => $options,
      '#default_value' => $config->get('queue_mail_queue_time'),
    );

    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('queue_mail.settings')
      ->set('queue_mail_keys', $form_state->getValue('queue_mail_keys'))
      ->set('queue_mail_queue_time', $form_state->getValue('queue_mail_queue_time'))
      ->save();

    parent::submitForm($form, $form_state);
  }

}
