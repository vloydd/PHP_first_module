<?php

namespace Drupal\vloydd\Controller;

class CatsPage {
  public function content () {
    $form = \Drupal::formBuilder() -> getForm('Drupal\vloydd\Form\CatsForms');
    return array (
//      '#markup' => 'Hello! You can add here a photo of your cat.',
      '#theme' => 'vloydd-theme',
//      '#markup' => 'Hello! You can add here a photo of your cat.',
      '#form' => $form,
    );
  }
}
