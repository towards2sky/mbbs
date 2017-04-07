<?php

namespace Drupal\clientside_validation\Plugin\CvValidator;

use Drupal\clientside_validation\CvValidatorBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Provides a 'email' validator.
 *
 * @CvValidator(
 *   id = "email",
 *   name = @Translation("Email"),
 *   supports = {
 *     "types" = {
 *       "email"
 *     }
 *   }
 * )
 */
class Email extends CvValidatorBase {

  /**
   * {@inheritdoc}
   */
  protected function getRules($element, FormStateInterface $form_state) {
    return [
      'messages' => [
        'email' => $this->t('@title saini does not contain a valid email.', ['@title' => $this->getElementTitle($element)]),
      ],
    ];
  }



    /**
     * {@inheritdoc}
     */
    public function addValidation(array &$element, FormStateInterface $form_state) {
        parent::addValidation($element, $form_state);
        // needs patch from https://www.drupal.org/node/2613694
        $element['#attributes']['pattern'] = "/[a-zA-Z0-9_-.+]+@[a-zA-Z0-9-]+.[a-zA-Z]+/";
    }

}
