<?php

namespace Drupal\vloyd\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\file\Entity\File;

/**
 * Controller Class
 */
class CatsPage extends ControllerBase
{

    /**
     *This func shows our content
     */
    public function content(): array
    {
        $form = \Drupal::formBuilder()->getForm('Drupal\vloyd\Form\CatsForms');
        $cats = $this->getCats();
        return [
//        '#theme' => 'vloyd-theme',
//        '#form' => $form,
//          '#catsdisplay' => $cats,
          $form, $cats,
        ];
    }
  /**
   * This func gets data from database and presents it on page.
   */
    public function getCats(): array
    {
        $query = \Drupal::database()->select('vloyd', 'v');
        $query -> fields('v', ['id', 'cats_name', 'email', 'image', 'timestamp']);
        $query -> orderBy('v.timestamp', 'DESC');
        $results = $query->execute()->fetchAll();
        $cats = [];
        foreach ($results as $data) {
            $file =File::load($data->image);
            $pictureuri = $file->getFileUri();
            $picture_url = file_create_url($pictureuri);
            $picture = [
              '#theme' => 'image_style',
//              '' => '',
              '#uri' => $pictureuri,
              '#attributes' => [
                'class' => 'cat_image',
                'width' => '150',
                'alt' => 'The photo of ' . $data->cats_name,
              ],
            ];
            $time = date('Y-m-d H:i:s', $data->timestamp);
            $cats[] = [
//            'id' => $data->id,
            'cats_name' => $data->cats_name,
            'email' => $data->email,
              'image' => $picture,
              'imageuri'=> $pictureuri,
              'timestamp'=> $time,
            ];

            $createlist['table'] = [
              '#type' => 'table',
              '#rows' => $cats,
            ];
        }
        return $createlist;
    }
}
