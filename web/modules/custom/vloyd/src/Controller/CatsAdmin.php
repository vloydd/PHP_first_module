<?php

namespace Drupal\vloyd\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Controller Class.
 */
class CatsAdmin extends ControllerBase {

  /**
   * This func shows our content.
   *
   * @return array
   *   Comment smth
   */
  public function content(): array {
    // $cats = $this->getAdmin();
    $form = \Drupal::formBuilder()->getForm('Drupal\vloyd\Form\CatsFormsAdmin');
    return [
      // '#catsdisplay' => $cats,
      $form,
    ];
  }

}

/**
 * This func shows our content.
 *
 * @return array
 *   Comment smth
 */
// Public function getAdmin() {
// $query = \Drupal::database()->select('vloyd', 'v');
// $query->fields('v', ['id', 'cats_name', 'email', 'image', 'timestamp']);
// $query->orderBy('v.timestamp', 'DESC');
// $results = $query->execute()->fetchAll();
//    $header = [
//      'name' => $this->t('Name'),
//      'email' => $this->t('Email'),
//      'image' => $this->t('Photo'),
//      'time' => $this->t('Data'),
//      'edit' => $this->t('Edit'),
//      'delete' => $this->t('Delete'),
//    ];
// $cats = [];
// foreach ($results as $data) {
// $file = File::load($data->image);
// $pictureuri = $file->getFileUri();
// $picture_url = file_create_url($pictureuri);
// $delete_url = Url::fromRoute('vloyd.delete_form', ['id' => $data->id], []);
// $delete = [
// '#type' => 'link',
// '#title' => $this->t('Delete'),
// '#url' => $delete_url,
// '#options' => [
// 'attributes' => [
// 'class' => [
// 'vloyd-item',
// 'vloyd-delete',
// 'use-ajax',
// ],
// 'data-dialog-type' => 'modal',
// ],
// ],
// ];
// $edit_url = Url::fromRoute('vloyd.edit_form', ['id' => $data->id], []);
// $edit = [
// '#type' => 'link',
// '#title' => $this->t('Edit'),
// '#url' => $edit_url,
// '#options' => [
// 'attributes' => [
// 'width' => 500,
// 'class' => [
// 'vloyd-item',
// 'vloyd-edit',
// 'use-ajax',
// ],
// 'data-dialog-type' => 'modal',
// ],
// ],
// ];
// $picture = [
// '#theme' => 'image_style',
// '#style_name' => 'medium',
// '#uri' => $pictureuri,
// '#attributes' => [
// 'class' => 'vloyd_image',
// 'height' => '150px',
// 'alt' => 'The photo of ' . $data->cats_name,
// ],
// ];
// $cats[] = [
// 'id' => $data->id,
// 'name' => $data->cats_name,
// 'email' => $data->email,
// 'image' => $picture,
// 'imageuri' => $pictureuri,
// 'imageurl' => $picture_url,
// 'time' => date('d.m.y H:i:s', $data->timestamp),
// 'delete' => $delete,
// 'edit' => $edit,
// ];
// $createlist['table'] = [
// '#type' => 'tableselect',
// '#header' => $header,
// '#rows' => $cats,
// ];
// }
// return $createlist;
//
//
//    $query = \Drupal::database()->select('vloyd', 'v');
//    $query -> fields('v', ['id', 'cats_name', 'email', 'image', 'timestamp']);
//    $query -> orderBy('v.timestamp', 'DESC');
//    $results = $query->execute()->fetchAll();
//    $cats = [];
//    foreach ($results as $data) {
//      $file =File::load($data->image);
//      $pictureuri = $file->getFileUri();
//      $picture_url = file_create_url($pictureuri);
//      $picture = [
//        '#theme' => 'image_style',
//        //              '' => '',
//        '#uri' => $pictureuri,
//        '#attributes' => [
//          'class' => 'cat_image',
//          'width' => '150',
//          'alt' => 'The photo of ' . $data->cats_name,
//        ],
//      ];
//      $time = date('Y-m-d H:i:s', $data->timestamp);
//      $cats[] = [
//        //            'id' => $data->id,
//        'cats_name' => $data->cats_name,
//        'email' => $data->email,
//        'image' => $picture,
//        'imageuri'=> $pictureuri,
//        'timestamp'=> $time,
//      ];
//
//      $createlist['table'] = [
//                '#type' => 'tableselect',
//                '#header' => $header,
//        '#options' => $cats,
//        '#rows' => $cats,
//      ];
//    }
//    return $createlist;
//  }
//
// }.
