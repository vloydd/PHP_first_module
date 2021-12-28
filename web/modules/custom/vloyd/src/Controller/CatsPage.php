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
     * @return  array
     */
    public function content(): array
    {
        $form = \Drupal::formBuilder()->getForm('Drupal\vloyd\Form\CatsForms');
        $cats = $this->getCats();
         $theme ='vloyd-theme';
        return [
        '#theme' => $theme,
        '#form' => $form,
          '#cats' => $cats,
        ];
    }
  /**
   * This func gets data from database and presents it on page.
   * @return  array
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
              'imageuri'=> $pictureuri,
              'imageurl'=> $picture_url,
              'time'=> date('d.m.y H:i:s', $data->timestamp),
            ];
        }
        return $cats;
    }
}
