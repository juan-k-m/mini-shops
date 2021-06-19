<?php

namespace Vendedor\Services;


// use Doctrine\Common\Collections\ArrayCollection;
// use Doctrine\ORM\AbstractQuery;
// use Shopware\Components\CSRFWhitelistAware;
// use Shopware\Models\Media\Album;
// use Shopware\Models\Media\Media;
// use Shopware\Models\Media\Settings;
// use Symfony\Component\HttpFoundation\File\UploadedFile;
// use DateTime;
// use ArrayObject;
// use DateInterval;
// use Shopware\Components\NumberRangeIncrementerInterface;
// use Zend_Mime;
// use Zend_Mime_Part;
use ArrayObject;
use DateInterval;
use DateTime;
use Shopware\Components\NumberRangeIncrementerInterface;
use Zend_Mime;
use Zend_Mime_Part;
use Vendedor\Models\Supplier;


class Order
{

  private $_view;
  private $_template;



  //create orders documents from array
  public function createOrderDocuments($variables)
  {
    //CREATE ARRAYS WITH DIFERENTS SUPPLIERS
    $suppliersInOrder= array();
    foreach ($variables['sOrderDetails'] as $key => $value) {
      // code...
      $suppliersInOrder[] = $value['additional_details']['supplierID'];

    }

    //array_unique
    $documents = array();

    $suppliersInOrder =  array_unique($suppliersInOrder);
    foreach ($suppliersInOrder as $keySupplier => $idSupplier) {

      foreach ($variables['sOrderDetails'] as $keyArticles => $value) {
        // compare the supplier ids and save in array the articles

        if ($idSupplier == $value['additional_details']['supplierID']) {
          // code...
          //echo $value['additional_details']['supplierID'].'|'.$value['articlename'].'</br>';
          $documents[$idSupplier]['sOrderDetails'][] =$variables['sOrderDetails'][$keyArticles];
          $documents[$idSupplier]['billingaddress'] =$variables['billingaddress'];
          $documents[$idSupplier]['shippingaddress'] =$variables['shippingaddress'];
          $documents[$idSupplier]['additional'] =$variables['additional'];
          $documents[$idSupplier]['ordernumber'] =$variables['ordernumber'];
          $documents[$idSupplier]['sOrderDay'] =$variables['sOrderDay'];
          $documents[$idSupplier]['sOrderTime'] =$variables['sOrderTime'];
          $documents[$idSupplier]['sComment'] =$variables['sComment'];
          $documents[$idSupplier]['attributes'] =$variables['attributes'];
          $documents[$idSupplier]['sDispatch'] =$variables['sDispatch'];
          //  $documents[$idSupplier]['numberRanges'] =$numberrange;;

        }


      }
    }


    // code...
    $toReturn = array();
    foreach ($documents as $key => $orderSingleSupplier) {
      // create pdf


      $toReturn[] = $this->createPdfDocuments($orderSingleSupplier);




    }
    return $toReturn;
  }


  //attachments
  public function createSupplierAttachment($hash)
  {


    try {

      $filesystem = Shopware()->Container()->get('shopware.filesystem.private');
      $filePath = sprintf('documents/%s.pdf', $hash);
      $fileName = $this->getFileName($hash);
      $content = $filesystem->read($filePath);
      $zendAttachment = new Zend_Mime_Part($content);
      $zendAttachment->type = 'application/pdf';
      $zendAttachment->disposition = Zend_Mime::DISPOSITION_ATTACHMENT;
      $zendAttachment->encoding = Zend_Mime::ENCODING_BASE64;
      $zendAttachment->filename = 'Rechnung_' . $fileName . '.pdf';

      return $zendAttachment;

    } catch (\Exception $error) {
    }

  }








  private function getHelperFunctions()
  {
    return Shopware()->Container()->get('vendedor.helper_functions');
  }
  public function getDocTypeId()
  {

    try {

      $sql = "SELECT d.id FROM s_core_documents AS d WHERE d.key='j_purchase_request_document' LIMIT 1;";

      $result = Shopware()->Db()->fetchRow($sql);
      return $result['id'];
    } catch (\Exception $error) {
    }


  }

  //save hash in db
  public function createPdfDocuments($orderSingleSupplier)
  {


    $toReturn = false;
    $numberrange = $this->getNumberRanges();
    $orderSingleSupplier['numberRanges'] = $numberrange;

    $orderNumber = $orderSingleSupplier['ordernumber'];
    $orderId= $this->getOrderIdByOrderNumber($orderNumber);
    $userId = $orderSingleSupplier['additional']['user']['id'];

    //echo "<pre>";var_dump($orderSingleSupplier['sOrderDetails'][0]['additional_details']['prices'][0]['price_numeric']);exit;
    //get $amount
    $priceArrayRow= array();
    $articles= array();
    foreach ($orderSingleSupplier['sOrderDetails'] as $key => $value) {
      // code...
      $price = $value['additional_details']['prices'][0]['price_numeric'];
      $image= $value['additional_details']['image']['thumbnails'][0]['source'];
      $priceArrayRow[] = $price*$value['quantity'];
      $articles[] = array(

        'name'=> $value['articlename'],
        'price' => $price,
        'quantity' => $value['quantity'],
        'image' => $image,

      );
    }
    $amount = array_sum($priceArrayRow);

    $tmpFile = tempnam(sys_get_temp_dir(), 'document');

    $orderSingleSupplier['amount'] = $amount;

    $html = $this->getContent($orderSingleSupplier);

    $defaultConfig = $this->getDefaultConfigPDF();


    $mpdf = new \Mpdf\Mpdf($defaultConfig);
    $mpdf->Bookmark('Start of the document');
    $mpdf->WriteHTML($html);

    $mpdf->Output($tmpFile, 'F');
    $hash = md5(uniqid(rand()));
    $stream = fopen($tmpFile, 'rb');
    $path = sprintf('documents/%s.pdf', $hash);

    $filesystem = Shopware()->Container()->get('shopware.filesystem.private');
    $filesystem->putStream($path, $stream);
    unlink($tmpFile);
    $dataForTable = array(


      'orderId' => $orderId,
      'customerId' => $userId,
      'amount' => $amount,
      'hash' => $hash,
      'numberranges' => $numberrange,


    );

    //save in shopware system

    if ($this->saveInTableDocuments($dataForTable)) {


      $toReturn = $hash;

    }


    //save in Plugin J

    $orderData = array(

      'articles' => $articles,
      'amount' => $amount,
      'user' => $orderSingleSupplier['additional']['user'],
      'address' => $orderSingleSupplier['shippingaddress'],
      'numberRanges'=> $numberrange,
      'orderday'=> $orderSingleSupplier['sOrderDay'],
      'ordertime'=> $orderSingleSupplier['sOrderTime'],
      'dispatch' => array(
        'id'=>$orderSingleSupplier['sDispatch']['id'],
        'name'  =>$orderSingleSupplier['sDispatch']['name'],
      ),

    );

    $dataToSave = array(
      'name' => $orderSingleSupplier['sOrderDetails'][0]['additional_details']['supplierName'] . ' | Email user: '. $orderSingleSupplier['additional']['user']['email'],
      'user' => $orderSingleSupplier['additional']['user']['id'],
      'supplierId' => $orderSingleSupplier['sOrderDetails'][0]['additional_details']['supplierID'],
      'orderParent' => $orderSingleSupplier['ordernumber'],
      'hash'=> $hash,
      'orderdata' => $orderData,
    );




    $this->saveInPluginTable($dataToSave);


    return $toReturn;

  }

  private function saveInPluginTable($data){



    $name=$data['name'];
    $userId=(int)$data['user'];
    $supplierId=$data['supplierId'];
    $orderParent=$data['orderParent'];
    $hash=$data['hash'];
    $orderdata=$data['orderdata'];


    //create instance SUpplier Models
    $em = Shopware()->Models();

    $supplier = new Supplier();
    $supplier->setUserId($userId);
    $supplier->setName($name);
    $supplier->setSupplierId($supplierId);
    $supplier->setOrderParentId($orderParent);
    $supplier->setDocumentHash($hash);
    $supplier->setOrderdata(serialize($orderdata));
    $supplier->setOrderTime(new \DateTime("now"));

    $em->persist($supplier);
    $em->flush();



  }

  private function getNumberRanges()
  {
    $numberrange = 'j_pedidos_n';
    /** @var NumberRangeIncrementerInterface $incrementer */
    $incrementer = Shopware()->Container()->get('shopware.number_range_incrementer');

    // Get the next number and save it in the document
    return $incrementer->increment($numberrange);


  }

  //data
  public function getContext($orderNumber)
  {


    $orderId = $this->getOrderIdByOrderNumber($orderNumber);
    $orderDetails = Shopware()->Modules()->Order()->getCustomerInformationByOrderId($orderId);

    $firstname = $orderDetails['billing_firstname'];
    $lastname = $orderDetails['billing_lastname'];
    $email = $orderDetails['email'];
    $salutation = $orderDetails['salutation'];
    $salutation = ($salutation === 'mr') ? 'Herr' : 'Frau';
    $shopManagerEmail = Shopware()->Config()->get('mail');

    $toReturn = array(
      'salutation' => $salutation,
      'firstName' => $firstname,
      'lastName' => $lastname,
      'email' => $email,
      'adminEmail' => $shopManagerEmail,

    );
    return $toReturn;

  }

  private function getDataForHtml($orderSingleSupplier)
  {


    $articles = $articlesFound['articles'];
    $orderId = $articlesFound['orderId'];

    $order = Shopware()->Modules()->Order()->getOrderById($orderId);
    $customer = Shopware()->Modules()->Order()->getCustomerInformationByOrderId('2');
    $arrayToReturn = array();

    $arrayCustomer = array(
      'customerId' => $customer['id'],
      'customerNumber' => $customer['customernumber'],
      'firstname' => $customer['billing_firstname'],
      'lastname' => $customer['billing_lastname'],
      'street' => $customer['billing_street'],
      'zipcode' => $customer['billing_zipcode'],
      'city' => $customer['billing_city'],


    );


    $orderNumber = $order['ordernumber'];
    $purchaseTime = $order['ordertime'];

    try{

      $getDates = $this->getGuarantyCalculation($purchaseTime);

    }catch (\Exception $error){
    }



    $guarantyData = array(

      'generalGuaranty' => $getDates['24M'],
      'frameGuaranty' => $getDates['120M'],
      'engineGuaranty' => $getDates['24M'],
      'batteryGuaranty' => $getDates['24M'],


    );

    foreach ($articles as $key => $article) {

      $price = $article['price'];
      $articleNumber = $article['ordernumber'];
      $articleId = $article['articleID'];
      $mtoTyp = $article['mto_typ'];
      $mtoColor = $article['mto_color'];
      $mtoWheelSize = $article['mto_wheel_size'];
      $mtoFrameHeight = $article['mto_frame_height'];
      $mtoGears = $article['mto_gears'];
      $mtoEngine = $article['mto_engine'];
      $mtoBattery = $article['mto_battery'];
      $articleName = $article['articleName'];
      $supplierName = $article['supplierName'];


      $articlesArrayTemp = array(
        'articleID' => $articleId,
        'articlenumber' => $articleNumber,
        'articleName' => $articleName,
        'price' => $price,
        'mto_typ' => $mtoTyp,
        'mto_color' => $mtoColor,
        'mto_wheel_size' => $mtoWheelSize,
        'mto_frame_height' => $mtoFrameHeight,
        'mto_gears' => $mtoGears,
        'mto_engine' => $mtoEngine,
        'mto_battery' => $mtoBattery,
      );


      $arrayToReturn[$key]['customer'] = $arrayCustomer;
      $arrayToReturn[$key]['guaranty'] = $guarantyData;
      $arrayToReturn[$key]['purchaseTime'] = $purchaseTime;
      $arrayToReturn[$key]['numberranges'] = $this->getNumberRanges();
      $arrayToReturn[$key]['article'] = $articlesArrayTemp;
      $arrayToReturn[$key]['orderId'] = $orderId;
      $arrayToReturn[$key]['ordernumber'] = $orderNumber;
      $arrayToReturn[$key]['supplierName'] = $supplierName;
    }


    return $arrayToReturn;


  }


  protected function loadConfiguration4x()
  {
    $id = $this->getDocTypeId();

    $this->_document = new ArrayObject(
      Shopware()->Db()->fetchRow(
        'SELECT * FROM s_core_documents WHERE id = ?',
        [$id],
        \PDO::FETCH_ASSOC
        )
      );

      // Load Containers
      $containers = Shopware()->Db()->fetchAll(
        'SELECT * FROM s_core_documents_box WHERE documentID = ?',
        [$id],
        \PDO::FETCH_ASSOC
      );

      //  $translation = $this->translationComponent->read($this->_order->order->language, 'documents');
      $this->_document->containers = new ArrayObject();
      foreach ($containers as $key => $container) {
        if (!is_numeric($key)) {
          continue;
        }
        if (!empty($translation[$id][$container['naloadConfiguration4xme'] . '_Value'])) {
          $containers[$key]['value'] = $translation[$id][$container['name'] . '_Value'];
        }
        if (!empty($translation[$id][$container['name'] . '_Style'])) {
          $containers[$key]['style'] = $translation[$id][$container['name'] . '_Style'];
        }

        // parse smarty tags
        $containers[$key]['value'] = $this->_template->fetch('string:' . $containers[$key]['value']);

        $this->_document->containers->offsetSet($container['name'], $containers[$key]);
      }
    }

public function reconfigureArrayData($data,$supplierId)
{
  // code...
//unset $data
$data['additional']['user'] = $data['user'];

//get supplierId
$em = Shopware()->Models()->getRepository(\Shopware\Models\Article\Supplier::class);

$supplier =$em->findOneBy(['id'=>(int)$supplierId]);

$data['supplierData'] = array(
  'name' => $supplier->getName(),
  'address' => $supplier->getAttribute()->getJSupplierAddress(),
  'phone' => $supplier->getAttribute()->getJSupplierPhone(),
  'email' => $supplier->getAttribute()->getJSupplierEmail(),
  'image' => $supplier->getImage(),
  'website' => $supplier->getLink(),
);


return $data;

}

    public function getContent($data,$supplierId=null)
    {
      if ($supplierId) {
        // code...

        $data = $this->reconfigureArrayData($data,$supplierId);

      }

      $templateData = $this->getTemplateId();
      $templateID = $templateData['doc_template_id'];
      $templateName = $templateData['template'];

      $template = Shopware()->Container()->get('models')->find(\Shopware\Models\Shop\Template::class, $templateID);


      $this->_template = clone Shopware()->Template();

      $this->_view = $this->_template->createData();
      $this->loadConfiguration4x();
      //$this->assignValues4x();


      $this->_template->setTemplateDir(['custom' => $templateName]);
      $inheritance = Shopware()->Container()->get('theme_inheritance')->getTemplateDirectories($template);
      $this->_template->setTemplateDir($inheritance);
      $datos = $this->_document->containers->getArrayCopy();
      $datos['Pedido'] = $data;

      $this->_view->assign('data', $datos);

      return $html = $this->_template->fetch('themes/Frontend/Mercado/documents/j_purchase_request_document.tpl', $this->_view);


    }

    public function getTemplateId()
    {


      $sql = "SELECT
      s.id,
      s.document_template_id as doc_template_id,
      s.template_id,
      (SELECT CONCAT('templates/', template) FROM s_core_templates WHERE id = s.document_template_id) as doc_template,
      (SELECT CONCAT('templates/', template) FROM s_core_templates WHERE id = s.template_id) as template,
      s.id as isocode,
      s.locale_id as locale
      FROM s_core_shops s
      WHERE s.default = 1";
      try {

        $id = Shopware()->Db()->fetchRow($sql);

      } catch (\Exception $error) {
      }

      return $id;

    }


    //default configuration pdf

    public function getDefaultConfigPDF()
    {

      //$mpdfConfig =  Shopware()->Container()->getParameter('shopware.mpdf.defaultConfig');
      $eventManager = Shopware()->Container()->get('events');

      $defaultConfig = Shopware()->Container()->getParameter('shopware.mpdf.defaultConfig');
      $defaultConfig = $eventManager->filter(
        'Shopware_Components_Document_Render_FilterMpdfConfig',
        $defaultConfig,
        [
          'template' => $this->_document['template'],
          'document' => $this->_document,
        ]
      );
      $mpdfConfig = array_replace_recursive(
        $defaultConfig,
        [
          'margin_left' => $this->_document['left'],
          'margin_right' => $this->_document['right'],
          'margin_top' => $this->_document['top'],
          'margin_bottom' => $this->_document['bottom'],
        ]
      );
      return $mpdfConfig;
    }




    //save the hash in //
    private function saveInTableDocuments($data)
    {
      $toReturn = false;
      try {

        $orderId = $data['orderId'];
        $customerId = (int)$data['customerId'];
        $amount = (float)$data['amount'];
        $hash = $data['hash'];
        $documentNumber = $data['numberranges'];
        $typID = $this->getDocTypeId();


        $sql = '
        INSERT INTO s_order_documents (`date`, `type`, `userID`, `orderID`, `amount`, `docID`,`hash`)
        VALUES ( NOW() , ? , ? , ?, ?, ?,?)
        ';


        Shopware()->Db()->query($sql, [
          $typID,
          $customerId,
          $orderId,
          $amount,
          $documentNumber,
          $hash,
        ]);
        $rowID = Shopware()->Db()->lastInsertId();


        if ((int)$rowID > 0) {

          // Add an entry in s_order_documents_attributes for the created document
          // containing all values found in the 'attributes' element of '_config'
          $createdDocument = Shopware()->Models()->getRepository('\Shopware\Models\Order\Document\Document')->findOneById($rowID);
          // Create a new attributes entity for the document
          $documentAttributes = new \Shopware\Models\Attribute\Document();
          $createdDocument->setAttribute($documentAttributes);
          if (!empty($this->_config['attributes'])) {
            // Save all given attributes
            $createdDocument->getAttribute()->fromArray($this->_config['attributes']);
          }
          // Persist the document
          Shopware()->Models()->flush($createdDocument);


          $toReturn = true;

        }


      } catch (\Exception $error) {
      }


      return $toReturn;
    }







    /********************************************************************/


    public function builtProductStructure($arrayData){
      $supplier = $arrayData['supplier'];
      $imagePath = $arrayData['imagePath'];
      $articleName = $arrayData['newArticle']['product_name'];
      $articleNumber = $arrayData['newArticle']['product_number'];
      $articleDescription = $arrayData['newArticle']['product_description'];
      $articlePrice = $arrayData['newArticle']['product_price'];
      $articleStorage = $arrayData['newArticle']['product_storage'];
      $articleActive = $arrayData['newArticle']['product_active'];
      $articleImage = $arrayData['image'];


      return array(
        'name' => $articleName,

        'active'=> (bool)$articleActive,
        'description' => $articleDescription,
        'supplierId' => (integer)$supplier,
        'taxId'=> 1,
        'categories' => array(
          array(
            'id' => 3
          )
        ),
        'mainDetail'=> array(
          'number'=>$articleNumber,
          'active'=> true,
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
      $passwordMd5 = md5($password);
      $additional = array('customer_type' => $customerType);

      return array(
        'email' => $email,
        'firstname' => $firstname,
        'lastname' => $lastname,
        'salutation' => $salutation,
        'accountMode' => '0',
        'newsletter' => $newsletter,
        'encoderName'=> "md5",
        'hashPassword'=>$passwordMd5,
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
