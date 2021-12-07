<?php

namespace Drupal\vloydd\Controller;

/**
 *
 */
class CatsPage
{

    /**
     *
     */
    public function content()
    {
        $form = \Drupal::formBuilder()->getForm('Drupal\vloydd\Form\CatsForms');
        return [
        '#theme' => 'vloydd-theme',
        '#form' => $form,
        ];
    }

}
