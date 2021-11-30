<?php

/**
 * @file
 * contains \Drupal\vloyd\Controller\CatsPage.
 */
namespace Drupal\vloyd\controller;
/**
 * Provides roud to our custom module.
*/
class CatsPage {
  /**
   * Displays page.
   */
  public function content () {
    return array (
      '#markup' => 'Hello! You can add here a photo of your cat.',
    );
  }
}
