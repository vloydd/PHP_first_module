<?php

namespace Drupal\vloyd\Form;

use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Ajax\MessageCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountProxyInterface;
use Drupal\Component\Utility\Bytes;
use Drupal\Core\Utility\Token;
use Drupal\image\Plugin\Field\FieldWidget\ImageWidget;

/**
 *
 */
class CatsForms extends FormBase
{

    /**
     * {@inheritDoc}
     */
    public function getFormId()
    {
        return 'vloydd_cats_form';
    }

    /**
     * {@inheritDoc}
     */
    public function buildForm(array $form, FormStateInterface $form_state): array
    {
        $form['message'] = [
        '#type' => 'markup',
        '#markup' =>
        '<h2 class="form_message_intro">
            Hello! You can add here a photo of your cat.
         </h2>',
        ];
        //      $form['message_eror'] = [
        //        '#type' => 'markup',
        //        '#markup' =>
        //          '<div class="form_fails">
        //            Hello! You can add here a photo of your cat.
        //         </div>',
        //      ];
        $form['catsname'] = [
        '#type' => 'textfield',
//        '#title' => $this->t("Your Cat Name:"),
        '#description' => $this->t('MinLength: 2 symb, MaxLength: 32 symb'),
        '#placeholder' => $this->t("Your Cat Name"),
        '#required' => true,
        ];
        $form['catsemail'] = [
        '#type' => 'email',
//        '#title' => $this->t("Your Email:"),
        '#description' => $this->t('Only Alpha, ., _, - and @ '),
        '#placeholder' => $this->t("Your Email:"),
        '#required' => true,
        '#ajax' => [
        'callback' => '::validateFormAjaxEmail',
        'event' => 'change',
        'progress' => [
          'type' => 'none',
        ],
        ],
          '#attributes' => [
            'data-disable-refocus' => 'true',
          ],
        '#suffix' => '<p class="false_email"></p>',
        ];
        $form['catsPhoto'] = [
        '#type' => 'managed_file',
        '#name' => 'catPhoto',
//        '#title' => $this->t("Your Cat Photo:"),
        '#description' =>
          $this->t('Avaiable Formats: jpeg, jpg, png; MaxSize - 2MB'),
        '#placeholder' => $this->t("Your Cat Photo"),
        '#required' => true,
        '#upload_validators' => [
          'file_validate_extensions' => $this->getAllowedFileExtensions(

          ),
          'file_validate_size' => $this->getAllowedFileSize(),
        ],
          '#theme' => 'image_widget',
          '#preview_image_style' => 'medium',
        '#upload_location' => 'public://vloyd_cats_photos',
        '#ajax' => [
          'callback' => '::validateFormAjaxFile',
          'event' => 'change',
          'progress' => [
            'type' => 'none',
          ],
        ],
        ];

        $form['actions']['submit'] = [
        '#type' => 'submit',
        '#button_type' => 'primary',
        '#value' => $this->t('Add Cat'),
        '#ajax' => [
        'callback' => '::validateFormAjax',
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
     * {@inheritDoc}
     */
    public function validateForm(array &$form, FormStateInterface $form_state): void
    {
        $title = $form_state->getValue('catsname');
        $email = $form_state->getValue('catsemail');
        $requiers = '/[-_@A-Za-z.]/';
        if (!(ctype_alnum($title)) && ((strlen($title)<2) || (strlen($title)>32))) {
            if (strlen($title) < 2) {
                $form_state->setErrorByName(
                    'catsname',
                    $this->t(
                      'Oh No! The Name of Your Cat is Shorter Than 2 Symbols('
                    )
                );
            } elseif (strlen($title) > 32) {
                $form_state->setErrorByName(
                    'catsname',
                    $this->t(
                      'Oh No! The Name of Your Cat is Longer Than 32 Symbols('
                    )
                );
            }
        } elseif (!ctype_alnum($title)) {
            $form_state->setErrorByName(
                'catsname',
                $this->t('Oh No! Please Use Alphanumeric chatacters')
            );
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            for ($i = 0; $i < (strlen($email)); $i++) {
                if (!preg_match($requiers, $email[$i])) {
                    $form_state->setErrorByName(
                        'catsname',
                        $this->t('Mail: Oh No! Your Email %title is Invalid', ['%title' => $email])
                    );
                    break;
                }
            }
        }
    }
//    public function validateFormAjaxFile
//    (array &$form, FormStateInterface $form_state): AjaxResponse
//    {
//        $file = $form_state->getValue('catsPhoto');
//        $response = new AjaxResponse();
//        if ($file['size'] > 2*1024*1024) {
//            $message = $this->t(
//              "Cat Photo: Oh No! Your File is Larger Than 2MB("
//            );
//        }
//        $response->addCommand(new MessageCommand($message, null, ['type'=> 'error']));
//        return $response;
//
//    }
    /**
     * {@inheritDoc}
     */
    public function validateFormAjax(array &$form, FormStateInterface $form_state): AjaxResponse
    {
        $email = $form_state->getValue('catsemail');
        $title = $form_state->getValue('catsname');
        $file = $form_state->getValue('catsPhoto');
        $requiers = '/[-_@A-Za-z.]/';
        $responses = new AjaxResponse();
        if ((ctype_alnum($title)) && ((strlen($title) < 2) || (strlen($title) > 32))) {
            if (strlen($title) < 2) {
                $message = $this->t(
                  "Cat Name: Oh No! The Name of Your Cat is Shorter Than 2 Symbols("
                );
            }
            if (strlen($title) > 32) {
                $message = $this->t(
                  "Cat Name: Oh No! The Name of Your Cat is Longer Than 32 Symbols("
                );
            }
            $responses -> addCommand(new MessageCommand($message, null, ['type'=> 'error']));
        } elseif (ctype_alnum($title)) {

            $message = $this->t(
              "Cat Name: Oh Yes! You Added Your Cat: %title.", ['%title' => $title]
            );
            $responses->addCommand(new MessageCommand($message));
        } else {
            $message = $this->t(
              "Cat Name: Oh No! Please Use Alphanumeric chatacters"
            );
            $responses -> addCommand(new MessageCommand($message, null, ['type'=> 'error']));
        }
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $tmp = 0;
            for ($i = 0; $i < (strlen($email)); $i++) {
                if (!preg_match($requiers, $email[$i])) {
                    $message = $this->t('Mail: Oh No! Your Email %title is Invalid', ['%title' => $email]);
                    $tmp++;
                    $responses->addCommand(new MessageCommand($message, null, ['type'=> 'error']));
                    break;
                }
            }
            if ($tmp == 0) {
            }
        }
        else {
            $message =
            $this->t('Mail: Oh No! Your Email %title is Invalid', ['%title' => $email]);
            $responses->addCommand(new MessageCommand($message, null, ['type'=> 'error']));
            //      $response -> setErrorByName($email, $this->t('Your name is less than 2 symbols.'));
        }
        return $responses;
    }

    /**
     * {@inheritDoc}
     */
    public function validateFormAjaxEmail(array &$form, FormStateInterface $form_state): AjaxResponse
    {
        $email = $form_state->getValue('catsemail');
        $response = new AjaxResponse();
        $requiers = '/[-_@A-Za-z.]/';
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $tmp = 0;
            for ($i = 0; $i < (strlen($email)); $i++) {
                if (!preg_match($requiers, $email[$i])) {
                    $message = $this->t('Mail: Oh No! Your Email %title is Invalid', ['%title' => $email]);
                    $tmp++;
                    $response->addCommand(
                        new HtmlCommand(
                            '.false_email', $message
                        )
                    );
                    break;
                }
            }
            if ($tmp == 0) {
              $message='';
              $response->addCommand(
                new HtmlCommand(
                  '.false_email', $message
                )
              );
            }
        } else {
            $message =
              $this->t('Mail: Oh No! Your Email %title is Invalid', ['%title' => $email]);
            $response->addCommand(
                new HtmlCommand(
                    '.false_email', $message
                )
            );
            //      $response -> setErrorByName($email, $this->t('Your name is less than 2 symbols.'));
        }
        return $response;
    }

    /**
     * {@inheritDoc}
     */
    public function submitForm(array &$form, FormStateInterface $form_state): void
    {
        $title = $form_state->getValue('catsname');
        \Drupal::messenger()->addStatus($this->t('You Added Your Cat: %title.', ['%title' => $title]));
    }
    public function setMessage(array &$form, FormStateInterface $form_state): array
    {
        return $form;
    }
    private function getAllowedFileExtensions(): array
    {
        return array('jpg jpeg png');
    }
    private function getAllowedFileSize(): array
    {
        return array(2097152);
    }
}
