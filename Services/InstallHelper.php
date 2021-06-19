<?php

namespace Vendedor\Services;

use Shopware\Models\Document\Document as DocumentType;
use Shopware\Models\Order\Number as NumberRanges;
use Shopware\Models\Document\Element as DocumentElement;

class InstallHelper
{
  public function createDocument()
  {


    $pdfDocument = $this->getRepository('document')->findOneBy(['key' => 'j_purchase_request_document']);

    if (is_object($pdfDocument)) {

      return false;

    }
    //purchase requisition

    $name = 'Pedidos';
    $template = 'j_purchase_request_document.tpl';
    $numberRangeName = 'j_pedidos_n';
    $left = 25;
    $right = 10;
    $top = 20;
    $bottom = 20;
    $pageBreak = 10;
    $technicalName = 'j_purchase_request_document';

    $documentType = new DocumentType();
    $documentType->setName($name);
    $documentType->setTemplate($template);
    $documentType->setNumbers($numberRangeName);
    $documentType->setLeft($left);
    $documentType->setRight($right);
    $documentType->setTop($top);
    $documentType->setKey($technicalName);
    $documentType->setBottom($bottom);
    $documentType->setPageBreak($pageBreak);
    $this->entityManager()->persist($documentType);
    $this->entityManager()->flush($documentType);

    $this->createDocumentElement();


  }

  private function createDocumentElement()
  {
    try {

      $model = $this->getRepository('document')->findOneBy(['key' => 'j_purchase_request_document']);
      $data['elements'] = $this->createDocumentElements($model);
      $model->fromArray($data);
      $em = $this->entityManager();
      $em->persist($model);
      $em->flush();

    } catch (\Exception $error) {
    }


  }


  private function createDocumentElements($model)
  {
    $elementCollection = new \Doctrine\Common\Collections\ArrayCollection();

    /**
    * @var \Shopware\Models\Document\Document
    */
    $elementModel = new DocumentElement();
    $elementModel->setName('Body');
    $elementModel->setValue('');
    $elementModel->setStyle('width:100%; font-family: Verdana, Arial, Helvetica, sans-serif; font-size:11px;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Logo');
    $elementModel->setValue('<p><img src="http://www.shopware.de/logo/logo.png" alt="" /></p>');
    $elementModel->setStyle('height: 20mm; width: 90mm; margin-bottom:5mm;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Header_Recipient');
    $elementModel->setValue('');
    $elementModel->setStyle('');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Header');
    $elementModel->setValue('');
    $elementModel->setStyle('height: 60mm;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Header_Sender');
    $elementModel->setValue('<p>Demo GmbH - Stra&szlig;e 3 - 00000 Musterstadt</p>');
    $elementModel->setStyle('');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Header_Box_Left');
    $elementModel->setValue('');
    $elementModel->setStyle('width: 120mm; height:60mm; float:left;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Header_Box_Right');
    $elementModel->setValue('<p><strong>Demo GmbH </strong><br /> Max Mustermann<br /> Stra&szlig;e 3<br /> 00000 Musterstadt<br /> Fon: 01234 / 56789<br /> Fax: 01234 /            56780<br />info@demo.de<br />www.demo.de</p>');
    $elementModel->setStyle('width: 45mm; height: 60mm; float:left; margin-top:-20px; margin-left:5px;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Header_Box_Bottom');
    $elementModel->setValue('');
    $elementModel->setStyle('font-size:14px; height: 10mm;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Content');
    $elementModel->setValue('');
    $elementModel->setStyle('height: 65mm; width: 170mm;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Td');
    $elementModel->setValue('');
    $elementModel->setStyle('white-space:nowrap; padding: 5px 0;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Td_Name');
    $elementModel->setValue('');
    $elementModel->setStyle('white-space:normal;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Td_Line');
    $elementModel->setValue('');
    $elementModel->setStyle('border-bottom: 1px solid #999; height: 0px;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Td_Head');
    $elementModel->setValue('');
    $elementModel->setStyle('border-bottom:1px solid #000;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Footer');
    $elementModel->setValue(
      '<table style="vertical-align: top;" width="100%" border="0">
      <tbody>
      <tr valign="top">
      <td style="width: 25%;">
      <p><span style="font-size: xx-small;">Demo GmbH</span></p>
      <p><span style="font-size: xx-small;">Steuer-Nr <br />UST-ID: <br />Finanzamt </span><span style="font-size: xx-small;">Musterstadt</span></p>
      </td>
      <td style="width: 25%;">
      <p><span style="font-size: xx-small;">Bankverbindung</span></p>
      <p><span style="font-size: xx-small;">Sparkasse Musterstadt<br />BLZ: <br />Konto: </span></p>
      <span style="font-size: xx-small;">aaaa<br /></span></td>
      <td style="width: 25%;">
      <p><span style="font-size: xx-small;">AGB<br /></span></p>
      <p><span style="font-size: xx-small;">Gerichtsstand ist Musterstadt<br />Erf&uuml;llungsort Musterstadt<br />Gelieferte Ware bleibt bis zur vollst&auml;ndigen Bezahlung unser Eigentum</span></p>
      </td>
      <td style="width: 25%;">
      <p><span style="font-size: xx-small;">Gesch&auml;ftsf&uuml;hrer</span></p>
      <p><span style="font-size: xx-small;">Max Mustermann</span></p>
      </td>
      </tr>
      </tbody>
      </table>'
    );
    $elementModel->setStyle('width: 170mm; position:fixed; bottom:-20mm; height: 15mm;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Content_Amount');
    $elementModel->setValue('');
    $elementModel->setStyle('margin-left:90mm;');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    $elementModel = new DocumentElement();
    $elementModel->setName('Content_Info');
    $elementModel->setValue('<p>Die Ware bleibt bis zur vollst&auml;ndigen Bezahlung unser Eigentum</p>');
    $elementModel->setStyle('');
    $elementModel->setDocument($model);
    $elementCollection->add($elementModel);

    return $elementCollection;
  }


  public function createNumberRanges()
  {


    $numberRanges = $this->getRepository('number')->findOneBy(['name' => 'j_pedidos_n']);
    if (is_object($numberRanges)) {
      return false;
    }

    $name = 'j_pedidos_n';
    $description = 'Pedidos';
    $number = '90000';

    $numberRangesObject = new NumberRanges();
    $numberRangesObject->setName($name);
    $numberRangesObject->setDescription($description);
    $numberRangesObject->setNumber($number);

    $em = $this->entityManager();
    $em->persist($numberRangesObject);
    $em->flush();


  }


  public function deleteNumberRange()
  {

    $toDelete = $this->getRepository('number')->findOneBy(['name' => 'j_pedidos_n']);
    if (is_object($toDelete)) {
      $em = $this->entityManager();
      $em->remove($toDelete);
      $em->flush();

    }


  }

  public function deleteDocument()
  {

    $toDelete = $this->getRepository('document')->findOneBy(['key' => 'j_purchase_request_document']);
    if (is_object($toDelete)) {
      $em = $this->entityManager();
      $em->remove($toDelete);
      $em->flush();

    }


  }

  private function getRepository($name)
  {
    $repository = '';
    switch ($name) {
      case 'number':
      $repository = 'Shopware\Models\Order\Number';
      break;
      case 'document':
      $repository = 'Shopware\Models\Document\Document';
      break;
    }

    return Shopware()->Models()->getRepository($repository);

  }

  private function entityManager()
  {
    return Shopware()->Models();
  }

  public function deleteAllVehicleDocuments()
  {


    try {

      $this->deleteVehicleDocAttr();


      $typID = $this->getDocTypeId();
      $sql = "DELETE FROM `s_order_documents` WHERE `type`=$typID;";
      Shopware()->Db()->query($sql);


    } catch (\Exception $error) {
    }


  }

  public function getDocTypeId()
  {

    try {

      $sql = "SELECT d.id FROM s_core_documents AS d WHERE d.key='mto_vehicle_document' LIMIT 1;";

      $result = Shopware()->Db()->fetchRow($sql);
      return $result['id'];
    } catch (\Exception $error) {
    }


  }

  public function deleteVehicleDocAttr()
  {

    try {
      $typID = $this->getDocTypeId();
      $sql = "DELETE FROM s_order_documents_attributes WHERE documentID IN (SELECT id FROM `s_order_documents` WHERE `type`=$typID);";

      Shopware()->Db()->query($sql);
    } catch (\Exception $error) {
    }

  }

}
