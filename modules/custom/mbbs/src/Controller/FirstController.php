<?php
/**
 * @file
 * contains \Drupal\mymodule\Controller\MyModuleController
 *
 */

namespace Drupal\mbbs\Controller;
use Drupal\Core\Controller\ControllerBase;

class FirstController extends ControllerBase {
    public function content() {
        return array();
    }


    public static function custom_form_validate(&$form, FormStateInterface $form_state) {
        print_r('Validate from controller class');
    }
}


