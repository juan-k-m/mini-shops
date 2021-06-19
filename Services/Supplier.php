<?php
namespace Vendedor\Services;

use Shopware\Models\Article\Supplier as SupplierModel;


class Supplier
{
  public function supplierData($supplierId)
  {
    // code...
    $toReturn = false;
    $em = Shopware()->Models()->getRepository(SupplierModel::class);
    $supplier = $em->find($supplierId);
    #

    if (is_object($supplier)) {
      // code...
      $attr = $supplier->getAttribute();
      $toReturn = array(
        'address' =>$attr->getJSupplierAddress(),
        'phone' => $attr->getJSupplierPhone(),
        'email' => $attr->getJSupplierEmail(),

      );


    }


    return $toReturn;


  }
  public function updateAttr($data,$supplierId)
  {
    // code...
    $toReturn = false;
    $em = Shopware()->Models()->getRepository(SupplierModel::class);
    $supplier = $em->find($supplierId);


    if (is_object($supplier)) {
      // code...
      $email = $data['email'];
      $phone = $data['phone'];
      $address = $data['address-streetNr']. ",". $data['address-zipCode']."," .$data['address-city'];

      $supplier->getAttribute()->setJSupplierEmail($email);
      $supplier->getAttribute()->setJSupplierPhone($phone);
      $supplier->getAttribute()->setJSupplierAddress($address);

      Shopware()->Models()->persist($supplier);
      Shopware()->Models()->flush();
      return true;



  }


  return $toReturn;


}



}
