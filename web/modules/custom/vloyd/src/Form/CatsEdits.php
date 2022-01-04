<?php

namespace Drupal\vloyd\Form;

use Drupal\Core\Database\Database;
use Drupal\Core\Form\FormStateInterface;
use Drupal\file\Entity\File;

/**
 * Our Form Edit Class.
 */
class CatsEdits extends CatsForms {
  /**
   * ID of the item to edit.
   *
   * @var int
   */
  public $id;

  /**
   * {@inheritdoc}
   */
  public function getFormId() :string {
    return 'vloydd_cats_edit';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL): array {
    $this->id = \Drupal::routeMatch()->getParameter('id');
    $conn = Database::getConnection();
    $data = [];
    if (isset($this->id)) {
      $query = $conn->select('vloyd', 'v')
        ->condition('id', $this->id)
        ->fields('v');
      $data = $query->execute()->fetchAssoc();
    }

    $form = parent::buildForm($form, $form_state);
    $form['message'] = [
      '#type' => 'markup',
      '#markup' => '<p class="error"></p>',
    ];
    $form['name']['#default_value'] = (isset($data['cats_name'])) ? $data['cats_name'] : '';
    $form['email']['#default_value'] = (isset($data['email'])) ? $data['email'] : '';
    $form['image']['#default_value'][] = (isset($data['image'])) ? $data['image'] : '';
    $form['actions']['submit']['#value'] = $this->t('Edit');
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
   * @throws \Exception
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $image = $form_state->getValue('image');
    $data = [
      'cats_name' => $form_state->getValue('name'),
      'email' => $form_state->getValue('email'),
      'image' => $image[0],
    ];

    // Save file as Permanent.
    $file = File::load($image[0]);
    $file->setPermanent();
    $file->save();

    if (isset($this->id)) {
      // Update data in database.
      \Drupal::database()->update('vloyd')->fields($data)->condition('id', ($this->id))->execute();
    }
    else {
      // Insert data to database.
      \Drupal::database()->insert('vloyd')->fields($data)->execute();
    }
    // Show message and redirect to list page.
    \Drupal::messenger()->addStatus($this->t('You Edited Your Cat: %title.', ['%title' => $form_state->getValue('name')]));
  }

}
