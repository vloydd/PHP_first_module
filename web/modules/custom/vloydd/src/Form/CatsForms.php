<?php

namespace Drupal\vloydd\Form;

use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;

class CatsForms extends FormBase  {

  public function getFormId() {
    return 'vloydd_cats_form';
  }

  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<div class="form_message_intro">Hello! You can add here a photo of your cat.</div>',
    ];
    $form['catsname'] = array (
      '#type' => 'textfield',
      '#title' => $this->t("Your Cat's Name"),
      '#description' => $this->t('MinLength: 2 symb, MaxLength: 32 symb'),
      '#placeholder' => $this->t("Enter Your Cat's Name"),
      '#required' => TRUE,
  );

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Add Cat'),
      '#ajax' => array(
        'callback' => '::validateFormAjax',
        'event' => 'click',
        'progress'=> array (
          'type' => 'throbber',
          'message' => $this->t("vefyring"),
        ),
      ),
    ];
    return $form;
  }
//    public function validateForm(array &$form, FormStateInterface $form_state) {
//      $title = $form_state->getValue('catsname');
//
//      if (strlen($title) < 2) {
//        $form_state->setErrorByName('catsname', $this->t('Oh No! The Name of Your Cat is Shorter Than 2 Symbols('));
//      }
//      else if (strlen($title) > 32) {
//        $form_state->setErrorByName('catsname', $this->t('Oh No! The Name of Your Cat is Longer Than 32 Symbols('));
//      }
//    }
  public function validateFormAjax(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('catsname');
    $response = new AjaxResponse();
    if ((ctype_alnum($title)) && ((strlen($title) < 2) || (strlen($title) > 32))) {
      if (strlen($title) < 2) {
        $message =  $this->t('Oh No! The Name of Your Cat is Shorter Than 2 Symbols(');
      }
      if (strlen($title) > 32) {
        $message =  $this->t('Oh No! The Name of Your Cat is Longer Than 32 Symbols(');
      }
      $response -> addCommand(new MessageCommand($message, null, ['type'=> 'error']));
    }
    else if (ctype_alnum($title)) {
      $message =  $this->t('Oh Yes! You Added Your Cat: %title.', ['%title' => $title], ['%str' => strlen($title)]);
      $response -> addCommand(new MessageCommand($message));
    }
    else {
      $message =  $this->t('Oh No! Please Use Alphanumeric chatacters');
      $response -> addCommand(new MessageCommand($message, null, ['type'=> 'error']));
    }
    return $response;
  }
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $title = $form_state->getValue('catsname');
    \Drupal::messenger()->addStatus($this->t('You Added Your Cat: %title. %str', ['%title' => $title], ['%str' => strlen($title)]));
  }
}
