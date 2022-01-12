<?php

namespace Drupal\vloyd\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a Confirmation Form to confirm Deleting of Something by ID.
 */
class CatsDelete extends ConfirmFormBase {

  /**
   * ID of the item to delete.
   *
   * @var int
   */
  public $id;

  /**
   * Func for Getting ID  of Deleting Form.
   *
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'vloyd_delete_form_admin';
  }

  /**
   * Func for Building Deleting Form.
   *
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL): array {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * Func for Setting Question of Deleting Form.
   *
   * {@inheritDoc}
   */
  public function getQuestion():string {
    return $this->t('Do you want to delete  this cat?');
  }

  /**
   * Func for Setting Description of Deleting Form.
   *
   * {@inheritDoc}
   */
  public function getDescription():string {
    return $this->t('Do you really want to delete cat with id %id ?', ['%id' => $this->id]);
  }

  /**
   * Func for Setting Text on Button that Confirms Deleting.
   *
   * {@inheritdoc}
   */
  public function getConfirmText():string {
    return $this->t('Delete');
  }

  /**
   * Func for Setting Text on Button that Cancels Deleting.
   *
   * {@inheritdoc}
   */
  public function getCancelText():string {
    return $this->t('Cancel');
  }

  /**
   * Func for Setting Redirect After Canceling.
   *
   * {@inheritDoc}
   */
  public function getCancelUrl(): Url {
    return new Url('vloyd.cats-page');
  }

  /**
   * Func for Submitting Deletion.
   *
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    \Drupal::database()->delete('vloyd')->condition('id', $this->id)->execute();
    $this->messenger()
      ->addStatus($this->t('You Deleted Your Cat'));
    $form_state->setRedirect('vloyd.cats-page');
  }

}
