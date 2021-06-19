<?php
namespace Vendedor\Services;

use Shopware\Models\Article\Image as IMG;
/**
*
*/
class Image
{

  public function deleteArticleImage($imageId,$articleId)
  {
    // code...

    $toReturn = false;
    if ($this->verifyIfImageBelonsArticle($imageId,$articleId)) {
      // code...
      $image = Shopware()->Models()->find(IMG::class, $imageId);
      if (is_object($image)) {
        // code...
        Shopware()->Models()->remove($image);
        Shopware()->Models()->flush();

        //return true or false
        $toReturn = true;
      }

    }



    return  $toReturn;
  }

  public function verifyIfImageBelonsArticle($imageId,$articleId)
  {
    // code...
  $toReturn = false;
    $em = Shopware()->Models()->getRepository(IMG::class);
    $articlesIds = $em->findOneBy(['article'=>(int)$articleId,'id'=>(int)$imageId]);

    if (is_object($articlesIds)) {
      // code...
      if (!empty($articlesIds->getId())) {
        // code...
        $toReturn =true;

      }
      return $toReturn;
    }



  }
}



?>
