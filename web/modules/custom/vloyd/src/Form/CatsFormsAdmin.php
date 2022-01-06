<?php

namespace Drupal\vloyd\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Our Form Edit Class.
 */
class CatsFormsAdmin extends ConfirmFormBase {
  /**
   * ID of the item to edit.
   *
   * @var int
   */
  protected $id;

  /**
   * Func for Setting Question.
   *
   * @inheritDoc
   */
  public function getQuestion(): string {
    return $this->t('Do you want to delete  this cat?');
  }

  /**
   * Func for Get Back to Our Form.
   *
   * @inheritDoc
   */
  public function getCancelUrl(): Url {
    return new Url('vloyd.cats-admin');
  }

  /**
   * Func for Getting ID.
   *
   * @inheritDoc
   */
  public function getFormId(): string {
    return 'vloydd_cats_admin';
  }

  /**
   * Func for Submitting Our Deleting.
   *
   * @inheritDoc
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $values = $form_state->getValue('table');
    $delete = array_filter($values);
    if (empty($delete)) {
      $this->messenger()->addError($this->t("Choose something to delete."));
    }
    else {
      \Drupal::database()->delete('vloyd')
        ->condition('id', $delete, 'IN')
        ->execute();
      $form_state->setRedirect('vloyd.cats-admin');
      $this->messenger()->addStatus($this->t("Cats are deleted."));
    }

//    \Drupal::database()->delete('vloyd')->condition('id', $this->id)->execute();
//    $this->messenger()
//      ->addStatus($this->t('You Deleted Your Cat' ));
//    $form_state->setRedirect('vloyd.cats-page');
////    return $this->t('You Deleted Your Cat');
  }

  /**
   * Func for Building Our Form.
   *
   * @inheritDoc
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL) {
    $this->id = \Drupal::routeMatch()->getParameter('id');
    $queries = \Drupal::database()->select('vloyd', 'v');
    $queries->fields('v', ['id', 'cats_name', 'email', 'image', 'timestamp']);
    $queries->orderBy('v.timestamp', 'DESC');
    $results = $queries->execute()->fetchAll();
    $cats = [];
    foreach ($results as $data) {
      $file = File::load($data->image);
      $pictureuri = $file->getFileUri();
      $picture_url = file_create_url($pictureuri);
      $delete_url = Url::fromRoute('vloyd.delete_form', ['id' => $data->id], []);
      $delete = [
        '#type' => 'link',
        '#title' => $this->t('Delete'),
        '#url' => $delete_url,
        '#options' => [
          'attributes' => [
      // 'class' => [
      //              'vloyd-item',
      //              'vloyd-delete',
      //              'use-ajax',
      //            ],
            'data-dialog-type' => 'modal',
          ],
        ],
      ];
      $edit_url = Url::fromRoute('vloyd.edit_form', ['id' => $data->id], []);
      $edit = [
        '#type' => 'link',
        '#title' => $this->t('Edit'),
        '#url' => $edit_url,
        '#options' => [
//          'attributes' => [
//            'class' => [
//              'use-ajax',
//            ],
//            'data-dialog-type' => 'modal',
//          ],
        ],
      ];
      $picture = [
        '#theme' => 'image_style',
        '#style_name' => 'medium',
        '#uri' => $pictureuri,
        '#attributes' => [
          'class' => 'vloyd_image',
          'height' => '150px',
          'alt' => 'The photo of ' . $data->cats_name,
        ],
      ];
      $cats[] = [
        'id' => $data->id,
        'name' => $data->cats_name,
        'email' => $data->email,
        'image' => $picture,
        'imageuri' => $pictureuri,
        'imageurl' => $picture_url,
        'time' => date('d.m.y H:i:s', $data->timestamp),
        'delete' => $delete,
        'edit' => $edit,
      ];
      $header = [
        'id' => $this->t('ID'),
        'name' => $this->t('Name'),
        'email' => $this->t('Email'),
        'image' => $this->t('Photo'),
      // 'imageuri' => $this->t('Photo Uri'),
      //        'imageurl' => $this->t('Photo Url'),
        'time' => $this->t('Data'),
        'edit' => $this->t('Edit'),
        'delete' => $this->t('Delete'),
      ];
      $form['table'] = [
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $cats,
      ];
      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t("Delete"),
      ];
    }
    return $form;
  }

}
