<?php

namespace Drupal\vloyd\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;
use Drupal\file\Entity\File;

/**
 * Our Form Admin Class.
 */
class CatsFormsAdmin extends ConfirmFormBase {
  /**
   * ID of the item to edit.
   *
   * @var int
   */
  public $id;

  /**
   * Func for Setting Question to Delete.
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
   * Func for Setting Description.
   *
   * @inheritDoc
   */
  public function getDescription() {
    return $this->t("Are You Really Sure to Delete this Pretty Cats?");
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
      $this->messenger()->addError($this->t("Choose Something to Delete."));
    }
    else {
      \Drupal::database()->delete('vloyd')->condition('id', $values, 'in')->execute();
      $form_state->setRedirect('vloyd.cats-admin');
      $this->messenger()->addStatus($this->t("Cats Are Deleted."));
    }
  }

  /**
   * Func for Building Our Admin Form.
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
      $delete_url = Url::fromRoute('vloyd.delete_form_admin', ['id' => $data->id], []);
      $edit_url = Url::fromRoute('vloyd.edit_form_admin', ['id' => $data->id], []);
      $image = [
        'data' => [
          '#theme'      => 'image',
          '#alt'        => 'catImg',
          '#uri'        => $pictureuri,
          '#width'      => 150,
          '#attributes' => [
            // 'target' => '_blank',
            // 'href' => $picture_url,
            'class' => [
              'cat_image_admin',
            ],
          ],
        ],
      ];
      $delete = [
        'data' => [
          '#type' => 'link',
          '#title' => $this->t('Delete'),
          '#url' => $delete_url,
          '#options' => [
            'attributes' => [
              'class' => [
                'vloyd-item',
                'vloyd-delete',
                'use-ajax',
              ],
              'data-dialog-type' => 'modal',
            ],
          ],
        ],
      ];
      $edit = [
        'data' => [
          '#type' => 'link',
          '#title' => $this->t('Edit'),
          '#url' => $edit_url,
          '#options' => [
            'attributes' => [
              'class' => [
                'use-ajax',
                'vloyd-item',
                'vloyd-edit',
              ],
              'data-dialog-type' => 'modal',
            ],
          ],
        ],
      ];
      $cats[$data->id] = [
        'id' => $data->id,
        'name' => $data->cats_name,
        'email' => $data->email,
        'image' => $image,
        'time' => date('d.m.y H:i:s', $data->timestamp),
        'delete' => $delete,
        'edit'  => $edit,
      ];
      $header = [
        'id' => $this->t('Cats ID'),
        'name' => $this->t('Name'),
        'email' => $this->t('Email'),
        'image' => $this->t('Photo'),
        'time' => $this->t('Data'),
        'edit' => $this->t('Edit'),
        'delete' => $this->t('Delete'),
      ];
      $form['table'] = [
        '#type' => 'tableselect',
        '#header' => $header,
        '#options' => $cats,
        '#empty' => $this->t('Nothing there.'),
      ];
      $form['submit'] = [
        '#type' => 'submit',
        '#value' => $this->t("Delete"),
      ];
    }
    return $form;
  }

}
