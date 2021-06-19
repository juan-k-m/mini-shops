<?php

use Shopware\Bundle\SearchBundle\Condition\ManufacturerCondition;
/**Shopware\Bundle\SearchBundle\Condition\ManufacturerCondition
* Frontend controller/nuevo-producto
*/
class Shopware_Controllers_Frontend_Vendedor extends Enlight_Controller_Action
{
  public function preDispatch()
  {


    $actionController =$this->Request()->get('action');
    $user =Shopware()->Modules()->Admin();
    if ($user->sCheckUser()) {
      // code...
      $isSupplier = (bool)$user->sGetUserData()['additional']['user']['j_is_supplier'];

    }


    if ((!$user->sCheckUser() || !$isSupplier) && 'registro' != strtolower($actionController) && 'processform' != strtolower($actionController)) {
      // redirect user to account index inorder to login
      $this->redirect('/account');
    }
    $this->view->assign('showSidebar',true);
  }

  public function indexAction()
  {
    $this->redirect('/account');
  }

  public function RegistroAction()
  {
    //    echo "<pre>";var_dump($this->front->Request()->getPost('email'));exit;

    // echo "<pre>";var_dump($_POST);exit;
    // echo "<pre>";var_dump($_SESSION);exit;
    //     echo "<pre>";var_dump($this->get('session'));exit;
  }

  //document preview
  public function documentoAction($value='')
  {
    // code...
    if (!isset($_GET['hash'])) {
      // code...

      $this->redirect('/Vendedor/pedidos');
    }
    $orderHelper = Shopware()->Container()->get('vendedor.order');
    $pedidos = $this->getPedidos();
    $hash =$_GET['hash'];
    $supplierId = $this->getSupplierid();
    $html = $pedidos->getHtml($supplierId,$hash);
    // $html = $orderHelper->getContent(unserialize($supplier->getOrderdata()));
    $defaultConfig = $orderHelper->getDefaultConfigPDF();
    $mpdf = new \Mpdf\Mpdf($defaultConfig);
    $mpdf->Bookmark('Start of the document');
    $mpdf->WriteHTML($html);

    $mpdf->Output();

    exit;



  }

  public function pedidosAction(){

    $supplierId =$this->getSupplierid();
    $pedidos = $this->getPedidos();
    $PedidosData= $pedidos->getPedidos($supplierId);
    $orderdataArray = array();
    foreach ($PedidosData as $key => $orderdata) {
      // code...

      $unserializedData  = $orderdata->getOrderdata();
      $orderdataArray[$key] = unserialize($unserializedData);
      $orderdataArray[$key]['documentHash'] = $orderdata->getDocumentHash();




    }


    $this->view->assign('documentHash',$documentHash);
    $this->view->assign('pedidos',$orderdataArray);
    // echo "<pre>";var_dump($this->view->getAssign());exit;





  }

  public function processFormAction()
  {

    if (isset($_POST['register'])) {

      $helper = $this->getHelperFunctions();
      $api = $this->getApiHelper();
      $data = $_POST;

      if ($data["sAGB"] != 'on') {
        //  $this->redirect('/Vendedor');
      }



      //save first manufacturer in order to get id and pass it to the new user

      $supplier  = $helper->builtSupplierStructure($data);
      //create a new supplier with the data
      $newSupplierId = $api->createSupplier($supplier);
      if (!is_integer($newSupplierId)) {
        // code...send error to registro

      }
      $data['supplierId'] = $newSupplierId;
      $user =$helper->builtTreeDataForApi($data);


      //use api to save new user and flag the user as supplier
      $newUserID = $api->createCustomer($user);

      if (is_integer($newUserID)) {

        //login new user
        $this->front->Request()->setPost('email', $data['register']['personal']['email']);
        $this->front->Request()->setPost('password', $data['register']['personal']['password']);
        Shopware()->Modules()->Admin()->sLogin(true);
        $this->redirect('/Vendedor/mi-tienda');
      }else{
        // code...send erro to registro
        $this->redirect('/Vendedor/registro');

      }






    } else {

      $this->redirect('/Vendedor/registro');
    }


  }

  public function productosAction(){


    $supplierId = $this->getSupplierId();

    $productoHelper = Shopware()->Container()->get('vendedor.producto');
    $articles = $productoHelper->getArticlesForSupplier($supplierId);

    //message from session
    if (isset($_SESSION['success']['update_article'])) {
      // code...
      $this->view->assign('articleUpdated', $_SESSION['success']['update_article']);
      unset($_SESSION['success']['update_article']);
    }

    // echo "<pre>";var_dump($articles);exit;
    $this->view->assign('sArticles',$articles);




  }

  protected function getArticleImages($articleId)
  {
    $builder = $this->getManager()->createQueryBuilder();
    $builder->select(['images'])
    ->from(Image::class, 'images')
    ->innerJoin('images.article', 'article')
    ->where('article.id = :articleId')
    ->orderBy('images.position', 'ASC')
    ->andWhere('images.parentId IS NULL')
    ->setParameter('articleId', $articleId);

    return $this->getFullResult($builder);
  }

  public function productosActionold(){

    $articlerResource = \Shopware\Components\Api\Manager::getResource('article');

    $user =Shopware()->Modules()->Admin();
    $manufacturerId = (int)$user->sGetUserData()['additional']['user']['j_supplier_id'];

    /** @var ShopContextInterface $context */
    $context = $this->get('shopware_storefront.context_service')->getShopContext();

    /** @var Criteria $criteria */
    $criteria = $this->get('shopware_search.store_front_criteria_factory')
    ->createListingCriteria($this->Request(), $context);



    //$condition = $criteria->addCondition(new ManufacturerCondition($manufacturerId));
    $criteria->addBaseCondition(new ManufacturerCondition([$manufacturerId]));


    $categoryProducts = Shopware()->Modules()->Articles()->sGetArticlesByCategory(
      $context->getShop()->getCategory()->getId(),
      $criteria
    );

    $shopHelper= Shopware()->Container()->get('vendedor.shop');
    if (!$shopHelper->supplierHasArticles($manufacturerId)) {
      // code...

      $this->view->assign('stepShopArticles',true);

    }


    $this->view->assign($categoryProducts);

  }

  public function datoscontactoAction()
  {
    // code...
    $supplierId = $this->getSupplierId();
    $supplierHelper = Shopware()->Container()->get('vendedor.supplier');
    $supplierData = $supplierHelper->supplierData($supplierId);
    $this->view->assign('shop',$supplierData);
  }

  public function saveInfoAction()
  {
    // code...
    $request = $this->request();
    if (!$request->getParam('infoShop')) {
      // code...
      $this->redirect('/Vendedor/datos-contacto');
      return false;
    }
    //update supplier data
    $supplierId = $this->getSupplierId();
    $supplierNewData = $request->getParam('infoShop');
    $supplierHelper = Shopware()->Container()->get('vendedor.supplier');
    $supplierHelper->updateAttr($supplierNewData,$supplierId);

    if ($supplierHelper->updateAttr($supplierNewData,$supplierId)) {
      // code...
      $this->redirect('/Vendedor/datoscontacto');
    }
  }

  public function nuevoproductoAction(){




    $shopHelper= Shopware()->Container()->get('vendedor.shop');
    $supplierId = $this->getSupplierid();


    if (!$shopHelper->supplierHasArticles($supplierId)) {
      // code...

      $this->view->assign('stepShopArticles',true);

    }


  }

  public function editarproductoAction()
  {
    // code



    $request = $this->request();
    if (!$request->getParam('id')) {
      // code...
      $this->redirect('/Vendedor/productos');
    }




    //check if exist ordernumber in relation with supplier
    $supplierId = $this->getSupplierid();
    $articleId= $request->getParam('id');

    $productHelper = Shopware()->Container()->get('vendedor.producto');
    if ($article = $productHelper->getArticleForEdit($articleId, $supplierId)) {
      // code...
      $md5supplierArticle = md5($articleId.$supplierId);
      $article['hidden'] = $md5supplierArticle;
      $this->view->assign('article',$article);

    }else{
      //return user to productos SeekableIterator
      $this->redirect('/Vendedor/productos');
    }



  }

  public function updateProductAction()
  {
    // code...
    $post = $_POST;

    if (!isset($post['editArticle'])) {
      // code...
      $this->redirect('Vendedor/productos');
      return false;
    }





    //verify if md5 is correct
    $md5Post = $post['editArticle']['product_hidden'];
    $articleId =$post['editArticle']['product_id'];
    $supplierId = $this->getSupplierId();

    if (md5($articleId.$supplierId) !== $md5Post) {
      // code.
      $this->redirect('Vendedor/productos');
      return false;

    }else{
      //delete images
      if (!empty($post['deleteImage'])) {
        // code...
        foreach ($post['deleteImage'] as $key => $value) {
          // code...


          $helperImage = Shopware()->Container()->get('vendedor.image');
          $helperImage->deleteArticleImage($value,$articleId);
        }
      }

      $helper = $this->getHelperFunctions();
      $fileArray = $this->Request()->files->get('image');
      if (!empty($fileArray)) {
        // code...
        $imagePath = array();
        foreach ($fileArray as $key => $value) {
          // code...
          $imagePath[] = $helper->uploadMedia($value);
        }
        //upload image
        $dataSupplier['imagePath'] = $imagePath;
        $arrayData = array_merge($post,$dataSupplier);
      }else{

        $arrayData = $post;
      }





      $dataForApi = $helper->builtUpdateProductStructure($arrayData);

      $api = $this->getApiHelper();
      $id = $api->updateProduct($articleId,$dataForApi);

      if (is_integer($id)) {
        // code...
        $_SESSION['success']['update_article'] = $post['editArticle']['product_name'];
        $this->redirect('/Vendedor/productos');

      }else{
        $this->redirect('/Vendedor/editar-producto?id='.$articleId);
      }


    }




  }

  public function saveProductAction(){


    /***************************************************/
    $post = $_POST;

    //check if user is supplier
    $user = Shopware()->Modules()->Admin();
    $supplierId = $user->sGetUserData()['additional']['user']['j_supplier_id'];
    $isSupplier = $user->sGetUserData()['additional']['user']['j_is_supplier'];

    $helper = $this->getHelperFunctions();


    $file = $this->Request()->files->get('image');

    //upload image
    $imagePath = $helper->uploadMedia($file);

    $dataSupplier['supplier'] =$supplierId;
    $dataSupplier['imagePath'] = $imagePath;
    $dataSupplier = array_merge($dataSupplier,$post);
    //
    $_SESSION['algo'] = $dataSupplier;

    $helper = $this->getHelperFunctions();
    $api = $this->getApiHelper();
    $newProduct = $helper->builtProductStructure($dataSupplier);
    $id = $api->createProduct($newProduct);

    if (!is_int($id)) {
      Shopware()->Session()->offsetSet('j_error',true);
      $this->redirect('/Vendedor/nuevo-producto');

    }

    $this->redirect('/Vendedor/productos');

  }

  public function mitiendaAction()
  {

    if ($_SESSION['j_error_name_exist']) {
      // code...
      $name = $_SESSION['j_error_name_exist'];
      unset($_SESSION['j_error_name_exist']);
      $errorMsg = "Por favor seleccione otro nombre para la tienda ya que <b>$name</b> ya fue tomado.";
      $this->view->assign('j_error',$errorMsg);
    }
    try{
      $supplierDataSmarty = false;
      // get the supplier data
      $supplierId = $this->getSupplierid();
      $supplier = $this->getSupplierDataFromDb($supplierId);

      //get link to the shop
      $shopLink = $this->getShopUrl($supplierId);

      $supplierDataSmarty = array(
        'name' => $supplier->getName(),
        'website' => $supplier->getLink(),
        'logo' => $supplier->getImage(),
        'description' => strip_tags($supplier->getDescription()),
        'coverImage' => $supplier->getAttribute()->getJSupplierCoverImage(),
        'shopLink' => $shopLink,
      );

    }catch(\Exception $e){

    }


    if (!$supplier->getDescription() &&
    !$supplier->getAttribute()->getJSupplierCoverImage() &&
    empty($supplier->getImage())
    )
    {
      // code...

      $this->view->assign('stepShopCover', true);

    }

    $this->view->assign('supplier', $supplierDataSmarty);




  }
  public function saveShopChangesAction()
  {
    // code...
    $helper = $this->getHelperFunctions();
    $api = $this->getApiHelper();

    $user = Shopware()->Modules()->Admin();
    $supplierId = $user->sGetUserData()['additional']['user']['j_supplier_id'];

    $post = $_POST;
    //check if supplier  name apc_exists
    $newSupplierName = $post['myShop']['shop_name'];

    if ($helper->checkIfNameExist($newSupplierName,$supplierId)) {
      // code...
      $_SESSION['j_error_name_exist'] = $newSupplierName;
      $this->redirect('/Vendedor/mi-tienda');

    }else{

      $supplierAlbumId = -12;
      $files = $this->Request()->files;
      if (is_object($files)) {
        // code...

        $coverShopImage =$this->Request()->files->get('image');
        $shopLogo =$this->Request()->files->get('logo');


        if ($coverShopImage) {
          // code...
          $coverImagePath = $helper->uploadMedia($coverShopImage,$supplierAlbumId);
          $post['cover_image'] = $coverImagePath;
        }

        if ($shopLogo) {
          // code...
          $logoPath = $helper->uploadMedia($shopLogo,$supplierAlbumId);
          $post['logo'] = $logoPath;
        }

      }

      //prepare data for api
      $data = $helper->builtSupplierStructureUpdate($post);



      //update supplier
      $idUpdated = $api->updateSupplier($supplierId,$data);

      //check if the supplier has articles

      $shopHelper = Shopware()->Container()->get('vendedor.shop');
      if (!$shopHelper->supplierHasArticles($supplierId)) {
        // code...

        $this->redirect('/Vendedor/nuevo-producto');

      }else{
        $this->redirect('/Vendedor/mi-tienda');
      }


    }



  }

  private function getHelperFunctions()
  {
    return $this->container->get('vendedor.helper_functions');
  }

  private function getApiHelper()
  {
    return $this->container->get('vendedor.api_helper');
  }

  private function getPedidos()
  {
    return $this->container->get('vendedor.pedidos');
  }

  /**
  * Internal helper function to get a single media.
  *
  * @param int $id
  *
  * @return Doctrine\ORM\QueryBuilder
  */
  private function getMedia($id)
  {
    $builder = Shopware()->Models()->createQueryBuilder();

    return $builder->select(['media'])
    ->from(Media::class, 'media')
    ->where('media.id = ?1')
    ->setParameter(1, $id);
  }

  private function getShopUrl($idSupplier){
    /** @var Supplier|null $supplier */
    $supplier = $this->get('shopware_storefront.manufacturer_service')->get(
      $idSupplier,
      $this->get('shopware_storefront.context_service')->getShopContext()
    );

    return $supplier->getLink();

  }

  private function getSupplierDataFromDb($idSupplier)
  {

    // /** @var Manufacturer|null $manufacturer */
    // $manufacturer = $this->get('shopware_storefront.manufacturer_service')->get(
    //   $idSupplier,
    //   $this->get('shopware_storefront.context_service')->getShopContext()
    // );
    //

    //return $manufacturer;
    $supplier  =Shopware()->Models()->find(\Shopware\Models\Article\Supplier::class,$idSupplier);
    return $supplier;

    //     $builder = Shopware()->Models()->createQueryBuilder();
    //
    // $supplierModel = $builder->getEntityManager()->find(\Shopware\Models\Article\Supplier::class,$idSupplier);
    // $supplierModprivateel->getAttribute()->getJSupplierCoverImage();
    //
    //     return $builder->select(['supplier'])
    //     ->from(Supplier::class, 'supplier')
    //     ->where('supplier.id = echo "<pre>";var_dump($idSupplier);exit;?1')
    //     ->setParameter(1, $idSupplier);
  }

  private function isSupplierUser(){

    $toReturn = false;
    $user =Shopware()->Modules()->Admin();
    if (!$user->sCheckUser()) {
      // redirect user to account index inorder to login
      $this->redirect('/account');
    }
  }

  private function getSupplierid(){
    $toReturn = false;
    $user = Shopware()->Modules()->Admin();
    $isLoged = $user->sCheckUser();

    if ($isLoged) {
      // code...
      $supplierId = $user->sGetUserData()['additional']['user']['j_supplier_id'];
      $toReturn =  $supplierId;
    }

    return $toReturn;
  }

  public function checkIfSupplierNameExistAction(){

    if (isset($_POST['supplierName'])) {

      $helper= $this->getHelperFunctions();
      //sanitize input
      $supplierName = $_POST['supplierName'];
      $nameExist = $helper->checkIfNameExist($supplierName, $this->getSupplierid());

      echo json_encode($nameExist);
      exit;

    }else{
      $this->redirect('/');
    }



  }

}
