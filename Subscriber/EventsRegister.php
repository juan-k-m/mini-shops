<?php

namespace Vendedor\Subscriber;


use Enlight\Event\SubscriberInterface;
use Shopware\Models\Media\Album;
use Shopware\Models\Media\Media;
use Shopware\Models\Order\Order;
use Smtp;

class EventsRegister implements SubscriberInterface
{

  public static function getSubscribedEvents()
  {
    return [
      //  'Shopware_Modules_Admin_Login_Successful' => 'onLoginSuccesful',
      'Enlight_Controller_Action_PostDispatch_Frontend'      => 'onFront',
      'Enlight_Controller_Action_PreDispatch_Frontend_Account'      => 'onPreAccount',
      'Enlight_Controller_Action_PreDispatch_Frontend_Note'      => 'onPreNote',
      'Enlight_Controller_Action_PreDispatch_Frontend_Address'      => 'onPreAddress',
      'Enlight_Controller_Action_PostDispatch_Frontend_Vendedor'      => 'onPostVendedor',
      'Enlight_Controller_Action_PostDispatch_Frontend_Listing'      => 'onPostListing',
      'Shopware_Components_Document::assignValues::after' => 'addProductImage',
      'Shopware_Modules_Order_SendMail_Filter' => 'onBeforeSendMail',
    ];
  }

  public function onBeforeSendMail(\Enlight_Event_EventArgs $args)
  {


    /***Purchse request document will be sent to the user as the same time that the order is make***/

    $variables = $args->get('variables');
    $mail = $args->getReturn();
    $orderNumber = $variables['ordernumber'];
    $orderHelper = $this->getOrderHelper();

    $hashArray = $orderHelper->createOrderDocuments($variables);

    foreach ($hashArray as $key => $value) {
      // code...
      $attachment = $orderHelper->createSupplierAttachment($value);
      $mail->addAttachment($attachment);

    }


  }

  public function onPostListing(\Enlight_Event_EventArgs $args)
  {

    $subject  = $args->get('subject');
    $view = $subject->View();
    $request = $subject->Request();
    $controllerAction = strtolower($request->getActionName());

    $supplierId = $request->getParam('sSupplier');

    if ($controllerAction === 'manufacturer') {


      try{
        // get the supplier website
        $helper= $this->getHelperFunctions();

        $supplierData = $helper->getSupplierData($supplierId);
        $supplierLinkWebsite = $supplierData->getLink();
        if (is_object($supplierData->getAttribute())) {
          // code...
          $supplierCoverImage  = $supplierData->getAttribute()->getJSupplierCoverImage();
          $view->assign('supplierCoverImage',$supplierCoverImage);
        }

        $view->assign('supplierLinkWebsite',$supplierLinkWebsite);
        $view->assign('isSupplier',true);

      }catch(\Exception $e){

      }


    }


  }



  public function onPostVendedor(\Enlight_Event_EventArgs $args)
  {

    $subject = $args->getSubject();
    $controller = $subject->Request()->getControllerName();
    $request = $args->get('subject')->Request();
    $actionName = strtolower($request->getParam('action'));
    $view = $subject->View();

    if ($actionName === 'registro') {

      $view->assign('countryList', Shopware()->Modules()->Admin()->sGetCountryList());


    }
    if ($this->isSupplierUser()) {

      // code
      $view->assign('jIsSupplier',true);


    }

  }


  public function onPreAccount(\Enlight_Event_EventArgs $args)
  {
    $subject  = $args->get('subject');
    $view = $subject->View();
    $user = Shopware()->Modules()->Admin();
    $controllerAction = $subject->request()->getActionName();
    //check if user is supplier
    if ($this->isSupplierUser() && strtolower($controllerAction) != 'logout') {

      // code
      $subject->redirect('/Vendedor/datos-contacto');


    }

  }

  public function onPreAddress(\Enlight_Event_EventArgs $args)
  {
    $subject  = $args->get('subject');
    $view = $subject->View();
    $user = Shopware()->Modules()->Admin();
    $controllerAction = $subject->request()->getActionName();
    //check if user is supplier
    if ($this->isSupplierUser()) {

      // code
      $subject->redirect('/Vendedor/datos-contacto');


    }

  }

  public function onFront(\Enlight_Event_EventArgs $args)
  {
    $subject  = $args->get('subject');
    $view = $subject->View();
    $user = Shopware()->Modules()->Admin();
    $controllerAction = $subject->request()->getActionName();
    //manage all errors

    if ($_SESSION['j_error']) {
      // code...

      $view->assign('j_error',$_SESSION['j_error']);
      unset($_SESSION['j_error']);
    }
    //check if user is supplier
    if ($this->isSupplierUser()) {

      // code
      $view->assign('jIsSupplier',true);


    }

    $user = Shopware()->Modules()->Admin()->sCheckUser();
    //check if user is supplier

    if ((bool)$user) {
      $view->assign('userIsLogged',1);

    }

  }

  public function onPreNote(\Enlight_Event_EventArgs $args)
  {
    $subject  = $args->get('subject');
    $view = $subject->View();
    $user = Shopware()->Modules()->Admin();
    $controllerAction = $subject->request()->getActionName();
    //check if user is supplier
    if ($this->isSupplierUser()) {

      // code
      $subject->redirect('/Vendedor/datos-contacto');


    }

  }



  public function onLoginSuccesful(\Enlight_Event_EventArgs $args)
  {

    $attrbutesLoader = Shopware()->Container()->get('shopware_attribute.data_loader');

    //check if user has the attr. j_is_supplier
    $user = $args->get('user');
    $attr = $attrbutesLoader->load('s_user_attributes', $user['id']);

    $email = $args->get('email');
    var_dump($attr);exit;
  }

  private function getHelperFunctions()
  {
    return Shopware()->Container()->get('vendedor.helper_functions');
  }

  public function addProductImage(\Enlight_Hook_HookArgs $args)
  {
    /** @var \Shopware_Components_Document $subject */
    $template = $args->getSubject();
    $view = $template->_view;
    $templatePages = $view->getTemplateVars('Pages');

    $mediaAlbumRepository = Shopware()->Models()->getRepository(Album::class);

    $mediaAlbum = $mediaAlbumRepository->findOneBy(['name' => 'Artikel']);

    foreach ($templatePages as $keyTemplate => $positions) {

      foreach ($positions as $keyPositions => $position) {

        $articleID = $position['articleID'];
        $orderNumber = $position['ordernumber'];
        if ($articleID != 0) {
          $sArticleImages = Shopware()->Modules()->Articles()->getArticleCover($articleID, $orderNumber, $mediaAlbum);
          $templatePages[$keyTemplate][$keyPositions]['img'] = $sArticleImages;
        }


      }


    }

    $view->assign('Pages', $templatePages);

  }

  private function getOrderHelper()
  {
    return Shopware()->Container()->get('vendedor.order');
  }

  private function isSupplierUser(){
    $toReturn = false;
    $user = Shopware()->Modules()->Admin();
    //check if user is supplier
    if ($user->sCheckUser()) {
      // falg supplier
      $isSupplier = (bool)$user->sGetUserData()['additional']['user']['j_is_supplier'];

      //assign variable
      if ($isSupplier) {
        // code
        $toReturn = true;

      }

    }
    return $toReturn;

  }

}
