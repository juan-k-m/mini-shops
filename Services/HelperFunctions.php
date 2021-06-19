<?php

namespace Vendedor\Services;


use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\AbstractQuery;
use Shopware\Components\CSRFWhitelistAware;
use Shopware\Models\Media\Album;
use Shopware\Models\Media\Media;
use Shopware\Models\Media\Settings;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use DateTime;
use ArrayObject;
use DateInterval;
use Shopware\Components\NumberRangeIncrementerInterface;
use Zend_Mime;
use Zend_Mime_Part;


class HelperFunctions
{

  private $_view;
  private $_template;

  public function builtProductStructure($arrayData){
    $supplier = $arrayData['supplier'];
    $imagePath = $arrayData['imagePath'];
    $articleName = $arrayData['newArticle']['product_name'];
    $articleNumber = $arrayData['newArticle']['product_number'];
    $articleDescription = $arrayData['newArticle']['product_description'];
    $articlePrice = $arrayData['newArticle']['product_price'];
    $articleStorage = $arrayData['newArticle']['product_storage'];
    $articleActive = $arrayData['newArticle']['product_active'];
    $productAvailable = $arrayData['newArticle']['product_available'];
    $articleImage = $arrayData['image'];

    $productoHelper =  Shopware()->Container()->get('vendedor.producto');
    $taxId = $productoHelper->getTaxId();


    return array(
      'name' => $articleName,

      'active'=> (bool)$articleActive,
      'description' => $articleDescription,
      'supplierId' => (integer)$supplier,
      'taxId'=> $taxId,
      'categories' => array(
        array(
          'id' => 3
        )
      ),
      'mainDetail'=> array(
        'number'=>$articleNumber,
        'active'=> $productAvailable,
        'inStock'=> $articleStorage,
        'prices'=> array(
          array(
            'customerGroupKey'=>'EK',
            'from' => 1,
            'price'=> (integer)$articlePrice
          )
        ),
      ),

      'images' => array(
        array(
          'link' => $imagePath,
          'main' => 1,
        )
      )
    );

  }

  public function builtUpdateProductStructure($arrayData){

    $supplier = $arrayData['supplier'];
    $articleName = $arrayData['editArticle']['product_name'];
    $articleNumber = $arrayData['editArticle']['product_number'];
    $articleDescription = $arrayData['editArticle']['product_description'];
    $articlePrice = $arrayData['editArticle']['product_price'];
    $articleStorage = $arrayData['editArticle']['product_storage'];
    $articleActive = $arrayData['editArticle']['product_active'];
    $productAvailable = $arrayData['editArticle']['product_available'];
    $articleImage = $arrayData['imagePath'];
  //  $lastStockDetails = ((bool)$productAvailable)?false:true;
    $toReturn =  array(
      'name' => $articleName,

      'active'=> (bool)$articleActive,
      'description' => $articleDescription,
      'lastStock'=> (bool)$productAvailable,
      //'supplierId' => (integer)$supplier,
      //'taxId'=> 1,
      // 'categories' => array(
      //   array(
      //     'id' => 3
      //   )
      // ),
      'mainDetail'=> array(
        'number'=>$articleNumber,
      //  'active'=> (bool)1,
        'lastStock'=> (bool)$productAvailable,
        'inStock'=> $articleStorage,
        'prices'=> array(
          array(
            'customerGroupKey'=>'EK',
            'from' => 1,
            'price'=> (integer)$articlePrice
          )
        ),
      ),

    );

    if (!empty($articleImage)) {
      // code...
      $toReturn['images'] = array();
      foreach ($articleImage as $key => $value) {
        // code...
        $toReturn['images'][] = array(
          'link' => $value,
        //  'position' => $key+1,
        );
      }

    }
    return $toReturn;

  }

  //builtSupplierStructure
  public function builtSupplierStructure($arrayData)
  {

    $personal = $arrayData['register']['personal'];
    $customerType = $personal['customer_type'];
    $salutation = $personal['salutation'];
    $firstname = $personal['firstname'];
    $lastname = $personal['lastname'];
    $phone = $personal['phone'];
    $email = $personal['email'];
    $newsletter = ($personal['newsletter']) ? $personal['newsletter'] : '0';

    $billing = $arrayData['register']['billing'];
    $street = $billing['street'];
    $streetNr = $billing['nr'];
    $address = $street . " " . $streetNr;
    $zipCode = $billing['zipcode'];
    $city = $billing['city'];
    $countryId = $billing['country'];
    $country = $this->getCountryName($countryId);
    $company = $billing['company'];
    $department = $billing['department'];
    $vatId = $billing['vatId'];


    // $password = $personal['password'];
    $additional = array('customer_type' => $customerType);

    return array(
      'name' => $company,
      'attribute' => array(

        'jSupplierEmail' => $email,
        'jSupplierPhone' => $phone,
        'jSupplierAddress' => "$address,$zipCode,$city,$country",

      ),
    );

  }

  //customer data
  public function builtTreeDataForApi($arrayData)
  {

    $supplierId = $arrayData['supplierId'];
    $personal = $arrayData['register']['personal'];
    $customerType = $personal['customer_type'];
    $salutation = $personal['salutation'];
    $firstname = $personal['firstname'];
    $lastname = $personal['lastname'];
    $email = $personal['email'];
    $phone = $personal['phone'];
    $newsletter = ($personal['newsletter']) ? $personal['newsletter'] : '0';

    $billing = $arrayData['register']['billing'];
    $street = $billing['street'];
    $streetNr = $billing['nr'];
    $address = $street . " " . $streetNr;
    $zipCode = $billing['zipcode'];
    $city = $billing['city'];
    $country = $billing['country'];
    $company = $billing['company'];
    $department = $billing['department'];
    $vatId = $billing['vatId'];


    $password = $personal['password'];
    //$passwordMd5 = md5($password);
    $additional = array('customer_type' => $customerType);

    return array(
      'email' => $email,
      'firstname' => $firstname,
      'lastname' => $lastname,
      'salutation' => $salutation,
      'accountMode' => '0',
      'newsletter' => $newsletter,
      'encoderName'=> "md5",
      'password'=>"$password",
      'attribute' => [
        'jIsSupplier' => '1',
        'jSupplierId' => $supplierId,
        ],
        'billing' => array(
          'firstname' => $firstname,
          'lastname' => $lastname,
          'phone' => $phone,
          'salutation' => $salutation,
          'street' => $address,
          'city' => $city,
          'zipcode' => $zipCode,
          'country' => $country,
          'company' => $company,
          'department' => $department,
          'vatid' => $vatId,
        )
      );

    }

    public function uploadMedia($file, $albumId = null)
    {
      // code...

      if (!is_object($file)) {
        // code...
        return false;
      }

      if (!$albumId) {
        // code...
        $albumId = -1;
      }

      // Try to get the transferred file
      try {
        /** @var UploadedFile $file */

        if (!$file->isValid()) {
          throw new Exception('The file exceeds the max file size.');
        }
      } catch (Exception $e) {
        return;
      }

      // Create a new model and set the properties
      $media = new Media();

      /** @var Album|null $album */
      $album = Shopware()->Models()->find(Album::class, $albumId);

      if (!$album) {
        return;
      }

      $media->setAlbum($album);
      $media->setDescription('');
      $media->setCreated(new DateTime());
      $media->setUserId(0);
      //    $this->Response()->headers->set('content-type', 'text/plain');


      // Set the upload file into the model. The model saves the file to the directory
      $media->setFile($file);

      // Persist the model into the model manager
      Shopware()->Models()->persist($media);
      Shopware()->Models()->flush();
      $data = $this->getMedia($media->getId())->getQuery()->getArrayResult();

      if ($media->getType() === Media::TYPE_IMAGE // GD doesn't support the following image formats
      && !in_array($media->getExtension(), ['tif', 'tiff'], true)) {
        $manager = Shopware()->Container()->get('thumbnail_manager');
        $manager->createMediaThumbnail($media, [], true);
      }

      $mediaService = Shopware()->Container()->get('shopware_media.media_service');

      $imagePath  = $mediaService->getUrl($data[0]['path']);
      return $imagePath;
    }


    public function builtSupplierStructureUpdate($data)
    {
      $toReturn = false;
      // Update supplier name
      $newSupplierName = $data['myShop']['shop_name'];
      $newSupplierWebsite = $data['myShop']['shop_website'];
      //check if has http

      $newSupplierDescription = $data['myShop']['shop_description'];
      $newSupplierCoverImage = $data['cover_image'];
      $newSupplierLogo = $data['logo'];

      if ($newSupplierLogo && $newSupplierCoverImage) {
        // code...
        $logoAndCoverImage = array(
          'name' => $newSupplierName,
          'description' => $newSupplierDescription,
          'link' => $newSupplierWebsite,
          'image' => array(
            'link' => $newSupplierLogo,
          ),
          'attribute' => [
            'jSupplierCoverImage' => $newSupplierCoverImage,
            ],
          );
          $toReturn= $logoAndCoverImage;
        }else{

          if ($newSupplierLogo) {
            // code...
            $image = array(
              'name' => $newSupplierName,
              'description' => $newSupplierDescription,
              'link' => $newSupplierWebsite,
              'image' => array(
                'link' => $newSupplierLogo,
              ),
            );
            $toReturn=$image;

          }
          elseif ($newSupplierCoverImage) {
            // code...
            $jSupplierCoverImage = array(
              'name' => $newSupplierName,
              'description' => $newSupplierDescription,
              'link' => $newSupplierWebsite,
              'attribute' => [
                'jSupplierCoverImage' => $newSupplierCoverImage,
                ],
              );
              $toReturn= $jSupplierCoverImage;
            }else{
              $withoutImages = array(
                'name' => $newSupplierName,
                'description' => $newSupplierDescription,
                'link' => $newSupplierWebsite,

              );
              $toReturn=$withoutImages;

            }


          }

          return $toReturn;


        }

        private function getMedia($id)
        {
          $builder = Shopware()->Models()->createQueryBuilder();

          return $builder->select(['media'])
          ->from(Media::class, 'media')
          ->where('media.id = ?1')
          ->setParameter(1, $id);
        }

        public function getSupplierData($idSupplier)
        {
          //return $manufacturer;
          $supplier  = Shopware()->Models()->find(\Shopware\Models\Article\Supplier::class,$idSupplier);

          return $supplier;

        }

        public function getSupplierWebsite($idSupplier)
        {
          //return $manufacturer;
          $supplier  = Shopware()->Models()->find(\Shopware\Models\Article\Supplier::class,$idSupplier);

          return $supplier->getLink();

        }
        public function checkIfNameExist($supplierName,$supplierId)
        {
          // code...

          $toReturn = false;
          $repository =     Shopware()->Models()->getRepository(\Shopware\Models\Article\Supplier::class);
          $nameExist = $repository->findOneBy(['name'=>$supplierName]);
          if (is_object($nameExist) && $nameExist->getId() != $supplierId) {
            // code...
            $toReturn  = true;
          }
          return $toReturn;


        }

        public function createInvoiceDocument($orderNumber)
        {
          $toReturn = false;
          $orderId = $this->getOrderIdByOrderNumber($orderNumber);

          //create the invoice document
          if ($this->generatePdfDocument($orderId)) {

            //get invoice document created
            $attachment = $this->createInvoiceAttachment($orderId);

            $toReturn = $attachment;


          }


          return $toReturn;

        }
        private function createInvoiceAttachment($orderId)
        {


          try {
            $sqlGetHashFromOrder = "SELECT doc.hash FROM s_order_documents AS doc WHERE doc.orderID=$orderId AND doc.type=7 LIMIT 1;";
            $hashRaw = Shopware()->Db()->fetchRow($sqlGetHashFromOrder);
            $hash = $hashRaw['hash'];
            $filesystem = Shopware()->Container()->get('shopware.filesystem.private');
            $filePath = sprintf('documents/%s.pdf', $hash);
            $fileName = $this->getFileName($hash);
            $content = $filesystem->read($filePath);
            $zendAttachment = new Zend_Mime_Part($content);
            $zendAttachment->type = 'application/pdf';
            $zendAttachment->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
            $zendAttachment->encoding = Zend_Mime::ENCODING_BASE64;
            $zendAttachment->filename = 'Solicitud_de_pedido_' . $fileName . '.pdf';

            return $zendAttachment;

          } catch (\Exception $error) {
          }

        }

        public function getFileName($hash)
        {

          try {

            $sql = "SELECT docID FROM s_order_documents WHERE hash='$hash' LIMIT 1;";
            $result = Shopware()->Db()->fetchRow($sql);
            return $result['docID'];

          } catch (\Exception $error) {
          }

        }

        private function getOrderIdByOrderNumber($orderNumber)
        {


          try {

            $sql = "SELECT id FROM s_order WHERE ordernumber=$orderNumber LIMIT 1;";

            $result = Shopware()->Db()->fetchRow($sql);
            return $result['id'];
          } catch (\Exception $error) {
          }


        }

        private function generatePdfDocument($orderId)
        {

          $toReturn = false;

          $document = \Shopware_Components_Document::initDocument($orderId, 7, array('netto' => false, 'date' => date('d.m.Y'), 'shippingCostsAsPosition' => true, '_renderer' => 'pdf'));
          $document->render();

          $toReturn = true;



          return $toReturn;
        }

        private function getCountryName($countryId){


          // code...

          $sql = "SELECT countryname FROM s_core_countries WHERE id='$countryId';";
          $db= Shopware()->Db();
          $result = $db->fetchRow($sql);
          return $result['countryname'];




        }




      }
