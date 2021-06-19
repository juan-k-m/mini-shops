<?php
namespace Vendedor\Services;

use Shopware\Bundle\MediaBundle\Exception\MediaFileExtensionIsBlacklistedException;
use Shopware\Bundle\MediaBundle\MediaServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\AdditionalTextServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\ContextServiceInterface;
use Shopware\Bundle\StoreFrontBundle\Service\Core\ContextService;
use Shopware\Bundle\StoreFrontBundle\Struct\ListProduct;
use Shopware\Components\CSRFWhitelistAware;
use Shopware\Components\Thumbnail\Manager;
use Shopware\Models\Article\Article;
use Shopware\Models\Article\Configurator\Dependency;
use Shopware\Models\Article\Configurator\Group;
use Shopware\Models\Article\Configurator\Option;
use Shopware\Models\Article\Configurator\Set;
use Shopware\Models\Article\Configurator\Template\Template;
use Shopware\Models\Article\Detail;
use Shopware\Models\Article\Esd;
use Shopware\Models\Article\EsdSerial;
use Shopware\Models\Article\Image;
use Shopware\Models\Article\Image\Mapping;
use Shopware\Models\Article\Image\Rule;
use Shopware\Models\Article\Price;
use Shopware\Models\Article\SeoCategory;
use Shopware\Models\Article\Supplier;
use Shopware\Models\Article\Unit;
use Shopware\Models\Attribute\Article as ProductAttribute;
use Shopware\Models\Category\Category;
use Shopware\Models\Price\Group as PriceGroup;
use Shopware\Models\Property\Group as PropertyGroup;
use Shopware\Models\Shop\Repository;
use Shopware\Models\Shop\Shop;
use Shopware\Models\Tax\Tax;
use Symfony\Component\HttpFoundation\Cookie;


class Producto{

public function verifyIfSupplierOnwsProduct($articleId,$supplierId)
{
  // code...
  $sql= "SELECT id FROM s_articles WHERE supplierID=$supplierId AND id=$articleId";
  $result = Shopware()->Db()->fetchRow($sql);

  if (empty($result)) {
    // code...
    return false;
  }
}

  public function getArticleForEdit($articleId,$supplierId)
  {
    $toReturn = false;
    if (!is_numeric($articleId)) {
      // code...
      return false;
    }


    //verify that supplier owns the article
$sql= "SELECT id FROM s_articles WHERE supplierID=$supplierId AND id=$articleId";
$result = Shopware()->Db()->fetchRow($sql);

if (empty($result)) {
  // code...
  return false;
}


    try {
      $article = $this->getArticle($articleId);



      $toReturn = $article[0];


    } catch (\Exception $error) {

      return false;

    }


    return $toReturn;
  }


  public function getArticlesForSupplier($supplierId)
  {
    // code...
    $toReturn = false;
    $builder = Shopware()->Models()->createQueryBuilder();
    $builder->select(['article'])
    ->from(Article::class, 'article')
    ->where('article.supplier = ?1')
    ->setParameter(1, $supplierId);
    $articles = $builder->getQuery()->getArrayResult();

    if (empty($articles)) {
      // code...
      return false;

    }
    $arrayIds =array();

    foreach ($articles as $key => $value) {
      // code...

      $arrayIds[] = $value['id'];
    }

    $articlesSupplier = array();
    foreach ($arrayIds as $key => $value) {
      // code...
      $rawArray = $this->getArticle($value);
      $articlesSupplier[] =$rawArray[0];


    }

    if (!empty($articlesSupplier)) {
      // code...

      $toReturn = $articlesSupplier;

    }


    return $toReturn;

  }

  public function getTaxId()
  {
    // code...

$em = Shopware()->Models()->getRepository(Tax::class);
$taxRaw = $em->findOneBy(['name'=>'IVA']);
return $taxRaw->getId();


  }


  public function verifyIfArticleBelogSuppplier($articleId, $supplierId)
  {
    // code...

    $toReturn = false;
    $apiHelper = Shopware()->Container()->get('vendedor.api_helper');
    if ($articleData = $apiHelper->getProductById($articleId)) {
      // code...

      if ($articleData['supplierId'] == (integer)$supplierId) {
        // show product
        $toReturn = $articleData;
      }



    }
    return $toReturn;


  }

  protected function getArticleData($articleId)
  {
    return $this->getRepository()
    ->getArticleBaseDataQuery($articleId)
    ->getArrayResult();
  }

  protected function getRepository()
  {
    if ($this->repository === null) {
      $this->repository = Shopware()->Models()->getRepository(Article::class);
    }

    return $this->repository;
  }

  public function getArticle($id)
  {
    $data = $this->getArticleData($id);

    $tax = $data[0]['tax'];

    $data[0]['categories'] = $this->getArticleCategories($id);
      $data[0]['seoCategories'] = $this->getArticleSeoCategories($id);

    // $data[0]['similar'] = $this->getArticleSimilars($id);
    // $data[0]['streams'] = $this->getArticleRelatedProductStreams($id);
    // $data[0]['related'] = $this->getArticleRelated($id);
    $data[0]['images'] = $this->getArticleImages($id);

    $data[0]['links'] = $this->getArticleLinks($id);
    // $data[0]['downloads'] = $this->getArticleDownloads($id);
    // $data[0]['customerGroups'] = $this->getArticleCustomerGroups($id);
    $data[0]['mainPrices'] = $this->getPrices($data[0]['mainDetail']['id'], $tax);
    $data[0]['configuratorSet'] = $this->getArticleConfiguratorSet($id);

    $data[0]['dependencies'] = [];

    if (!empty($data[0]['configuratorSetId'])) {
      $data[0]['dependencies'] = $this->getArticleDependencies($data[0]['configuratorSetId']);
    }

    $data[0]['configuratorTemplate'] = $this->getArticleConfiguratorTemplate($id, $tax);

    if ($data[0]['added'] && $data[0]['added'] instanceof \DateTime) {
      $added = $data[0]['added'];
      $data[0]['added'] = $added->format('d.m.Y');
    }

    return $data;
  }

  public function getArticleImages($articleId)
  {
    trigger_error(sprintf('%s:%s is deprecated since Shopware 5.6 and will be private with 5.8.', __CLASS__, __METHOD__), E_USER_DEPRECATED);

    /** @var MediaServiceInterface $mediaService */
    $mediaService = Shopware()->Container()->get('shopware_media.media_service');

    /** @var Manager $thumbnailManager */
    $thumbnailManager = Shopware()->Container()->get('thumbnail_manager');

    $builder = Shopware()->Models()->createQueryBuilder();
    $builder->select(['images', 'media', 'imageMapping', 'mappingRule', 'ruleOption'])
    ->from(Image::class, 'images')
    ->leftJoin('images.article', 'article')
    ->leftJoin('images.media', 'media')
    ->leftJoin('images.mappings', 'imageMapping')
    ->leftJoin('imageMapping.rules', 'mappingRule')
    ->leftJoin('mappingRule.option', 'ruleOption')
    ->where('article.id = :articleId')
    ->andWhere('images.parentId IS NULL')
    ->orderBy('images.position')
    ->setParameter('articleId', $articleId);

    $result = $builder->getQuery()->getArrayResult();

    foreach ($result as &$item) {
      $thumbnails = $thumbnailManager->getMediaThumbnails(
        $item['media']['name'],
        $item['media']['type'],
        $item['media']['extension'],
        [
          [
            'width' => 140,
            'height' => 140,
          ],
        ]
      );

      $item['original'] = $mediaService->getUrl($item['media']['path']);

      if (!empty($thumbnails)) {
        $item['thumbnail'] = $mediaService->getUrl($thumbnails[0]['source']);
      } else {
        $item['thumbnail'] = $mediaService->getUrl($item['media']['path']);
      }
    }

    return $result;
  }

  public function getArticleLinks($articleId)
  {
    trigger_error(sprintf('%s:%s is deprecated since Shopware 5.6 and will be private with 5.8.', __CLASS__, __METHOD__), E_USER_DEPRECATED);

    $result = $this->getRepository()
    ->getArticleLinksQuery($articleId)
    ->getArrayResult();

    if (empty($result[0]['links'])) {
      return [];
    }
    // map the link target to the boolean format that is expected by the ExtJS backend module
    $links = $result[0]['links'];
    foreach ($links as &$linkData) {
      $linkData['target'] = $linkData['target'] === '_blank';
    }

    return $links;
  }
  protected function getPrices($id, $tax)
  {
    $prices = $this->getRepository()
    ->getPricesQuery($id)
    ->getArrayResult();

    return $this->formatPricesFromNetToGross($prices, $tax);
  }
  protected function formatPricesFromNetToGross($prices, $tax)
  {
    foreach ($prices as $key => $price) {
      $customerGroup = $price['customerGroup'];
      if ($customerGroup['taxInput']) {
        $price['price'] = $price['price'] / 100 * (100 + $tax['tax']);
        $price['pseudoPrice'] = $price['pseudoPrice'] / 100 * (100 + $tax['tax']);
      }
      $prices[$key] = $price;
    }

    return $prices;
  }
  public function getArticleCategories($articleId)
  {
    trigger_error(sprintf('%s:%s is deprecated since Shopware 5.6 and will be private with 5.8.', __CLASS__, __METHOD__), E_USER_DEPRECATED);

    $builder = Shopware()->Models()->createQueryBuilder();
    $builder->select(['categories.id'])
    ->from(Category::class, 'categories', 'categories.id')
    ->andWhere(':articleId MEMBER OF categories.articles')
    ->setParameter('articleId', $articleId);

    $result = $builder->getQuery()->getArrayResult();
    if (empty($result)) {
      return [];
    }

    $categories = [];
    foreach ($result as $item) {
      $categories[] = [
        'id' => $item['id'],
        'name' => $this->getCategoryRepository()->getPathById($item['id'], 'name', '>'),
      ];
    }

    return $categories;
  }
  public function getArticleConfiguratorSet($articleId)
  {
    trigger_error(sprintf('%s:%s is deprecated since Shopware 5.6 and will be private with 5.8.', __CLASS__, __METHOD__), E_USER_DEPRECATED);

    $builder = Shopware()->Models()->createQueryBuilder();
    $builder->select(['configuratorSet', 'groups', 'options'])
    ->from(Set::class, 'configuratorSet')
    ->innerJoin('configuratorSet.articles', 'article')
    ->leftJoin('configuratorSet.groups', 'groups')
    ->leftJoin('configuratorSet.options', 'options')
    ->addOrderBy('groups.position', 'ASC')
    ->addOrderBy('options.groupId', 'ASC')
    ->addOrderBy('options.position', 'ASC')
    ->where('article.id = :articleId')
    ->setParameter('articleId', $articleId);

    $result = $builder->getQuery()->getArrayResult();

    return $result[0];
  }
  protected function getCategoryRepository()
  {
    if ($this->categoryRepository === null) {
      $this->categoryRepository = Shopware()->Models()->getRepository(Category::class);
    }

    return $this->categoryRepository;
  }
  public function getArticleConfiguratorTemplate($articleId, $tax)
  {
    trigger_error(sprintf('%s:%s is deprecated since Shopware 5.6 and will be private with 5.8.', __CLASS__, __METHOD__), E_USER_DEPRECATED);

    $query = $this->getRepository()->getConfiguratorTemplateByArticleIdQuery($articleId);

    $configuratorTemplate = $query->getArrayResult();

    $prices = $configuratorTemplate[0]['prices'];

    if (!empty($prices)) {
      $configuratorTemplate[0]['prices'] = $this->formatPricesFromNetToGross($prices, $tax);
    }

    return $configuratorTemplate;
  }
  protected function getArticleSeoCategories($articleId)
  {
      $builder = $this->getManager()->createQueryBuilder();
      $builder->select(['seoCategories', 'category', 'shop'])
          ->from(SeoCategory::class, 'seoCategories')
          ->innerJoin('seoCategories.shop', 'shop')
          ->innerJoin('seoCategories.category', 'category')
          ->where('seoCategories.articleId = :articleId')
          ->setParameter('articleId', $articleId);

      return $builder->getQuery()->getArrayResult();
  }
  protected function getManager()
  {
      if ($this->manager === null) {
          $this->manager = Shopware()->Models();
      }

      return $this->manager;
  }


}


?>
