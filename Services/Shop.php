<?php
namespace Vendedor\Services;

use Shopware\Models\Article\Article;
use Shopware\Models\Article\Supplier;


class Shop
{
  public function supplierHasArticles($supplierId)
  {
    // code...
    $toReturn = false;
    $em = Shopware()->Models()->getRepository(Article::class);
    $article = $em->findOneBy(['supplier'=> $supplierId]);


    if (is_object($article)) {
      // code...

      if (is_integer($article->getId())) {
        // code...
        $toReturn = true;
      }

    }


    return $toReturn;


  }

  public function getSupplierUrl($supplierId)
  {

    /** @var Supplier|null $supplier */
    $supplier = Shopware()->Container()->get('shopware_storefront.manufacturer_service')->get(
      $supplierId,
      Shopware()->Container()->get('shopware_storefront.context_service')->getShopContext()
    );

    return $supplier->getLink();



  }

  public function getShops()
  {
    // code...
    $builder = Shopware()->Models()->createQueryBuilder();
    $builder->select([
      'supplier',
      'attribute',
    ])
    ->from(Supplier::class, 'supplier')
    ->leftJoin('supplier.attribute', 'attribute');

    $suppliers = $builder->getQuery()->getArrayResult();
    foreach ($suppliers as $key => $value) {
      // code...
      $supplierId = $value['id'];
      $suppliers[$key]['url'] = $this->getSupplierUrl($supplierId);

    }
    return $suppliers;
  }
  public function getShopsODL()
  {
    // code...
    $suppliers = $em;
    $builder = Shopware()->Models()->createQueryBuilder();
    $builder->select(['supplier'])
    ->from(Supplier::class, 'supplier');
    return $builder->getQuery()->getArrayResult();
  }


}
