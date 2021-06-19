<?php
namespace Vendedor\Services;

use Vendedor\Models\Supplier;


class Pedidos{


  public function getPedidos($supplierId){


    $repository = Shopware()->Models()->getRepository(Supplier::class);
    $supplier = $repository->findBy(['supplierId'=>$supplierId]);

    return $supplier;
  }

  public function getDocument($hash){

    $repository = Shopware()->Models()->getRepository(Supplier::class);
    $orderData = $repository->findOneBy(['documentHash'=>$hash]);
    return $orderData;
  }

public function getHtml($supplierId,$hash)
{
  // code...

  $dataObject = $this->getDocument($hash);
  $data = unserialize($dataObject->getOrderdata());
  $helper = Shopware()->Container()->get('vendedor.order');
  $toReturn = $helper->getContent($data,$supplierId);
return $toReturn;


}



}


?>
