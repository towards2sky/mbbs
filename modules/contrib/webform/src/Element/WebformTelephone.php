<?php

namespace Drupal\webform\Element;

use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a webform element for a telephone element.
 *
 * @FormElement("webform_telephone")
 */
class WebformTelephone extends WebformCompositeBase {

  /**
   * {@inheritdoc}
   */
  public function getInfo() {
    $info = parent::getInfo();
    unset($info['#title_display']);
    return $info;
  }

  /**
   * {@inheritdoc}
   */
  public static function getCompositeElements() {
    $elements = [];
    $elements['type'] = [
      '#type' => 'select',
      '#title' => t('Type'),
      '#title_display' => 'invisible',
      '#options' => 'phone_types',
      '#empty_option' => t('- Type -'),
    ];
    $elements['phone'] = [
      '#type' => 'tel',
      '#title' => t('Phone'),
      '#title_display' => 'invisible',
      '#international' => TRUE,
    ];
    $elements['ext'] = [
      '#title' => t('Ext:'),
      '#type' => 'number',
      '#size' => 5,
      '#min' => 0,
    ];
    return $elements;
  }

  /**
   * Processes a composite webform element.
   */
  public static function processWebformComposite(&$element, FormStateInterface $form_state, &$complete_form) {
    $element = parent::processWebformComposite($element, $form_state, $complete_form);
    $element['#attached']['library'][] = 'webform/webform.element.composite_telephone';
    return $element;
  }

}
