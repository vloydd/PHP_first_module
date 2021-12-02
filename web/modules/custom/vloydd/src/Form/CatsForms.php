<?php

namespace Drupal\vloydd\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class CatsForms extends FormBase  {

  public function getFormId() {
    return 'vloydd_cats_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['catsname'] = [
      '#type' => 'textfield',
      '#title' => $this->t("Your Cat's Name"),
      '#description' => $this->t('MinLength: 2 symb, MaxLength: 32 symb'),
      '#placeholder' => $this->t("Enter Your Cat's Name"),
      '#required' => TRUE,
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Add Cat'),
    ];
    return $form;
  }
    public function validateForm(array &$form, FormStateInterface $form_state) {
      $title = $form_state->getValue('catsname');

      if (strlen($title) < 2) {
        $form_state->setErrorByName('catsname', $this->t('Oh No! Please Enter the Name of Your Cat That is Longer Than 2 Symbols'));
      }
      else if (strlen($title) > 32) {
        $form_state->setErrorByName('catsname', $this->t('Oh No! Please Enter the Name of Your Cat That is Shorter Than 32 Symbols'));
      }
    }
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('catsname');
    \Drupal::messenger()->addStatus($this->t('You Added Your Cat: %title.', ['%title' => $title]));

  }
}
