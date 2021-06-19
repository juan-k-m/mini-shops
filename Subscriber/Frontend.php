<?php

namespace Vendedor\Subscriber;

use Enlight\Event\SubscriberInterface;
use Vendedor\Models\Supplier;

class Frontend implements SubscriberInterface
{
  public static function getSubscribedEvents()
  {
    return array(
    'Enlight_Controller_Action_PostDispatchSecure_Frontend' => 'onFrontendPostDispatch'
    );
  }

  public function onFrontendPostDispatch(\Enlight_Event_EventArgs $args)
  {
    /** @var $controller \Enlight_Controller_Action */

  //echo "<pre>";var_dump($_POST);exit;

// echo "<pre>";var_dump($_SESSION);exit;
// echo "<pre>";var_dump(get_class_methods(Shopware()->MOdules()->Admin()));exit;
//    // $orderHelper = $this->getOrderHelper();
// //     //  $orderHelper->createOrderDocuments(array());
//
//    //Retrieve the serialized string.
   //$fileContents = file_get_contents('custom/plugins/Vendedor/serialized.txt');
  // $fileContents = file_get_contents('files/documents/0b43ba92123d4c7f783c6e75f6f756c0.pdf');
//
   //Unserialize the string back into an array.
   // $mpdf = new \Mpdf\Mpdf();
// $mpdf->WriteHTML($fileContents);

// $mpdf->Output('files/documents/0b43ba92123d4c7f783c6e75f6f756c0.pdf');
// exit;
   //echo "<pre>";var_dump($fileContents);exit;
   // $arrayUnserialized = unserialize($fileContents);

// echo "<pre>";var_dump($arrayUnserialized);exit;
//
//    //End result.
// $orderHelper->createOrderDocuments($arrayUnserialized);


// $repository = Shopware()->Models()->getRepository(Supplier::class);
// $supplier = $repository->findOneBy(['documentHash'=>'1175daa66cbe03e59a44509586e3ba78']);
//
// // echo "<pre>";var_dump(unserialize($supplier->getOrderdata()));exit;
//
// $html = $orderHelper->getContent(unserialize($supplier->getOrderdata()));
// $defaultConfig = $orderHelper->getDefaultConfigPDF();
//
//
// $mpdf = new \Mpdf\Mpdf($defaultConfig);
// $mpdf->Bookmark('Start of the document');
// $mpdf->WriteHTML($html);
//
// $mpdf->Output();
//
// exit;
//echo "<pre>";var_dump('fin');exit;

    $controller = $args->getSubject();
    $view = $controller->View();

  }


  private function getOrderHelper()
  {
    return Shopware()->Container()->get('vendedor.order');
  }
}
