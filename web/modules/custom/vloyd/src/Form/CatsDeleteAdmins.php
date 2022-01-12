<?php

namespace Drupal\vloyd\Form;

use Drupal\Core\Form\ConfirmFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Defines a Form to Confirm Deletion of Something by ID for Admin.
 */
class CatsDeleteAdmins extends ConfirmFormBase {

  /**
   * ID of the Item to Delete and Choose.
   *
   * @var int
   */
  public $id;

  /**
   * Func for Getting ID  of Admin Form.
   *
   * {@inheritdoc}
   */
  public function getFormId(): string {
    return 'vloyd_delete_form';
  }

  /**
   * Func for Building Admin Form.
   *
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state, $id = NULL): array {
    $this->id = $id;
    return parent::buildForm($form, $form_state);
  }

  /**
   * {@inheritDoc}
   */
  public function getQuestion():string {
    return $this->t('Delete This Cat?');
  }

  /**
   * Func for Setting Description of Deleting Form.
   *
   * {@inheritDoc}
   */
  public function getDescription():string {
    return $this->t('Dear Admin, Do You Really Want to Delete Cat With id %id?', ['%id' => $this->id]);
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
   * {@inheritdoc}
   */
  public function getCancelText():string {
    return $this->t('Cancel');
  }

  /**
   * {@inheritDoc}
   */
  public function getCancelUrl() {
    return new Url('vloyd.cats-admin');
  }

  /**
   * Func for Submitting Deletion.
   *
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state): void {
    \Drupal::database()->delete('vloyd')->condition('id', $this->id)->execute();
    $this->messenger()
      ->addStatus($this->t('You Deleted Your Cat(s)'));
    $form_state->setRedirect('vloyd.cats-admin');
  }

}
