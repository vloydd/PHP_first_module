<?php

namespace Drupal\vloyd\Controller;
use Drupal\Core\Controller\ControllerBase;
//use Drupal\vloyd\Form\CatsForms;
/**
 *
 */
class CatsPage extends ControllerBase
{

    /**
     *
     */
    public function content(): array
    {
        $form = \Drupal::formBuilder()->getForm('Drupal\vloyd\Form\CatsForms');
        return [
        '#theme' => 'vloyd-theme',
        '#form' => $form,
        ];
    }

}
