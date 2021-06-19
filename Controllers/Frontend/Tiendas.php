<?php

use Shopware\Bundle\StoreFrontBundle\Struct\Product\Manufacturer;
use Shopware\Bundle\StoreFrontBundle\Struct\ShopContextInterface;

class Shopware_Controllers_Frontend_Tiendas extends Enlight_Controller_Action
{





  public function indexAction(){




    $shopHelper = Shopware()->Container()->get('vendedor.shop');
    $shops = $shopHelper->getShops();
  
    $this->view->assign('shops', $shops);

  }

}
