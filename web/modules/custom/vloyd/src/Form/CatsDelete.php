<?php

namespace Drupal\vloyd\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a confirmation form to confirm deletion of something by id.
 */
class CatsDelete extends ConfirmFormBase {

  /**
   * ID of the item to delete.
   *
   * @var int
   */
  public $id;

  /**
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'vloyd_delete_form';
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL): array {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function getQuestion() {
    return $this->t('Do you want to delete  this cat?');
  }

  /**
   * {@inheritDoc}
   */
  public function getDescription() {
    return t('Do you really want to delete cat with id %id ?', ['%id' => $this->id]);
  }

  /**
   * {@inheritdoc}
   */
  public function getConfirmText() {
    return $this->t('Delete');
  }

  /**
   * {@inheritdoc}
   */
  public function getCancelText() {
    return $this->t('Cancel');
  }

  /**
   * {@inheritDoc}
   */
  public function getCancelUrl() {
    return new Url('vloyd.cats-page');
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::database()->delete('vloyd')->condition('id', $this->id)->execute();
    $this->messenger()
      ->addStatus($this->t('You Deleted Your Cat'));
    $form_state->setRedirect('vloyd.cats-page');
  }

}
