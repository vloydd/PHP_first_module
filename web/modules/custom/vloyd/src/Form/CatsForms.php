<?php

namespace Drupal\vloyd\Form;

use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\RedirectCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\file\Entity\File;
use Drupal\Core\Url;

/**
 * Our Form Class.
 */
class CatsForms extends FormBase {

  /**
   * This func is form ID.
   *
   * @return string
   *   Comment smth.
   */
  public function getFormId() :string {
    return 'vloydd_cats_form';
  }

  /**
   * This func is for build our form.
   */
  public function buildForm(array $form, FormStateInterface $form_state): array {

    $form['#prefix'] = '<div id="form-wrapper">';
    $form['#suffix'] = '</div>';
    $form['message'] = [
      '#type' => 'markup',
      '#markup' =>
      '<h2 class="form_message_intro">
            Hello! You can add here a photo of your cat.
         </h2>',
    ];
    $form['name'] = [
      '#title' => $this->t('Your Cat Name:'),
      '#required' => TRUE,
      '#type' => 'textfield',
      '#description' => $this->t('MinLength: 2 symb, MaxLength: 32 symb'),
      '#placeholder' => $this->t("Your Cat Name"),
      '#attributes' => [
        'autocomplete' => 'off',
      ],
    ];
    $form['email'] = [
      '#title' => $this->t('Your Email:'),
      '#required' => TRUE,
      '#type' => 'email',
      '#description' => $this->t('Only Alpha, ., _, - and @'),
      '#placeholder' => $this->t("Your Email:"),
      '#ajax' => [
        'callback' => '::validateFormAjaxEmail',
        'event' => 'change',
        'progress' => [
          'type' => 'none',
        ],
      ],
      '#attributes' => [
        'data-disable-refocus' => 'true',
        'autocomplete' => 'off',
      ],
      '#suffix' => '<p class="false_email"></p>',
    ];
    $form['image'] = [
      '#title' => $this->t('The Photo of Your Cat:'),
      '#type' => 'managed_file',
      '#name' => 'catPhoto',
      '#description' =>
      $this->t('Avaiable Formats: jpeg, jpg, png; MaxSize - 2MB'),
      '#placeholder' => $this->t("Your Cat Photo"),
      '#required' => TRUE,
      '#upload_validators' => [
        'file_validate_extensions' => ['jpg jpeg png'],
        'file_validate_size' => [2097152],
      ],
      '#upload_location' => 'public://vloyd_cats_photos',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#button_type' => 'primary',
      '#value' => $this->t('Add Cat'),
      '#attributes' => [
        'class' => ['btn', 'btn-warning'],
      ],
      '#ajax' => [
        'callback' => '::setMessage',
        'wrapper' => 'form-wrapper',
        'effect' => 'slide',
        'event' => 'click',
        'progress' => [
          'type' => 'throbber',
          'message' => $this->t("vefyring"),
        ],
      ],
    ];
    return $form;
  }

  /**
   * This func validate our email.
   *
   * @param array $form
   *   Comment smth.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Comment smth.
   */
  public function validateForm(array &$form, FormStateInterface $form_state): void {
    $title = $form_state->getValue('name');
    $email = $form_state->getValue('email');
    $file = $form_state->getValue('image');
    $emptyfile = empty($file);
    $requiers = '/[-_@A-Za-z.]/';
    if (strlen($title) < 2) {
      $form_state->setErrorByName(
            'name',
            $this->t(
                'Name: Oh No! The Name of Your Cat is Shorter Than 2 Symbols('
            )
        );
    }
    elseif (strlen($title) > 32) {
      $form_state->setErrorByName(
            'name',
            $this->t(
                'Name: Oh No! The Name of Your Cat is Longer Than 32 Symbols('
            )
            );
    }
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      for ($i = 0; $i < (strlen($email)); $i++) {
        if (!preg_match($requiers, $email[$i])) {
          $form_state->setErrorByName(
                'email',
                $this->t('Mail: Oh No! Your Email %title is Invalid', ['%title' => $email])
            );
        }
      }
    }
    if ($emptyfile) {
      $form_state->setErrorByName(
            'image',
            $this->t('File: Oh No! Your Photo is Empty')
        );
    }
  }

  /**
   * This func validate our email.
   *
   * @param array $form
   *   Comment smth.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Comment smth.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Comment smth.
   */
  public function validateFormAjaxEmail(array &$form, FormStateInterface $form_state): AjaxResponse {
    $email = $form_state->getValue('email');
    $response = new AjaxResponse();
    $requiers = '/[-_@A-Za-z.]/';
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $tmp = 0;
      for ($i = 0; $i < (strlen($email)); $i++) {
        if (!preg_match($requiers, $email[$i])) {
          $message = $this->t('Mail: Oh No! Your Email %title is Invalid(', ['%title' => $email]);
          $tmp++;
          $response->addCommand(
                new HtmlCommand(
                    '.false_email',
                    $message
                )
            );
          break;
        }
      }
      if ($tmp == 0) {
        $message = '';
        $response->addCommand(
              new HtmlCommand(
                  '.false_email',
                  $message
              )
          );
      }
    }
    else {
      $message =
            $this->t('Mail: Oh No! Your Email %title is Invalid(', ['%title' => $email]);
      $response->addCommand(
            new HtmlCommand(
                '.false_email',
                $message
            )
            );
    }
    return $response;
  }

  /**
   * This func is for AJAX Redirect.
   *
   * @param array $form
   *   Comment smth.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Comment smth.
   */
  public function setMessage(array &$form, FormStateInterface $form_state) {
    $response = new AjaxResponse();
    if (!$form_state->hasAnyErrors()) {
      $url = Url::fromRoute('vloyd.cats-page');
      $command = new RedirectCommand($url->toString());
      $response->addCommand($command);
      return $response;
    }
    return $form;
  }

  /**
   * This func submitting form.
   *
   * @param array $form
   *   Comment smth.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Comment smth.
   *
   * @throws \Drupal\Core\Entity\EntityStorageException
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {

    $image = $form_state->getValue('image');
    $data = [
      'cats_name' => $form_state->getValue('name'),
      'email' => $form_state->getValue('email'),
      'image' => $image[0],
      'timestamp' => time(),
    ];

    // Save file as Permanent.
    $file = File::load($image[0]);
    $file->setPermanent();
    $file->save();

    // Insert data to database.
    \Drupal::database()->insert('vloyd')->fields($data)->execute();
    // Succesful message.
    $this->messenger()
      ->addStatus($this->t('You Added Your Cat: %title.', ['%title' => $form_state->getValue('name')]));
  }

}
