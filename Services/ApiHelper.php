<?php

namespace Vendedor\Services;


class ApiHelper
{

  public function createCustomer($data)
  {

    $insertedCustomerID = false;
    $customerResource = \Shopware\Components\Api\Manager::getResource('customer');
    $email = $data['email'];

    try {

      $insertedCustomerID = $customerResource->create($data)->getId();

    } catch (\Exception $error) {

      $error = $error->getMessage();
      ///  $_SESSION['j_error'] = $error;
      // header('Location:/Muster');
    }

    return $insertedCustomerID;


  }

  public function createProduct($data)
  {

    $insertedCustomerID = false;
    $articlerResource = \Shopware\Components\Api\Manager::getResource('article');

    try {

      $insertedArticleID = $articlerResource->create($data)->getId();

    } catch (\Exception $error) {

      //             $errorMsg = $error->getMessage();
      //
      //         //  $_SESSION['j_error'] = $errorMsg;
      //
      //
      // Shopware()->Session()->offsetSet('j_error',$errorMsg);
      //              header('Location:/Vendedor/nuevo-producto');
    }

    return $insertedArticleID;


  }


  public function createSupplier($data)
  {

    $insertedCustomerID = false;
    $manufacturerResource = \Shopware\Components\Api\Manager::getResource('manufacturer');

    try {

      $insertedSupplierID = $manufacturerResource->create($data)->getId();

    } catch (\Exception $error) {

      $error = $error->getMessage();
      ///  $_SESSION['j_error'] = $error;
      // header('Location:/Muster');
    }

    return $insertedSupplierID;


  }

  public function updateSupplier($supplierId,$data)
  {
    // code...
    $insertedCustomerID = false;
    $manufacturerResource = \Shopware\Components\Api\Manager::getResource('manufacturer');

    try {

      $updateSupplierID = $manufacturerResource->update($supplierId,$data)->getId();

    } catch (\Exception $error) {

      $error = $error->getMessage();
      ///  $_SESSION['j_error'] = $error;
      // header('Location:/Muster');
    }

    return $updateSupplierID;
  }

  public function getProductById($articleId)
  {
    // code...
    $toReturn = false;
    try {
      $articlerResource = \Shopware\Components\Api\Manager::getResource('article');
      $toReturn = $articlerResource->getOne($articleId);
    } catch (\Exception $error) {

      $error = $error->getMessage();
      ///  $_SESSION['j_error'] = $error;
      // header('Location:/Muster');
    }
    return $toReturn;

  }

    public function updateProduct($articleId,$dataArray)
    {
      // code...

      $toReturn = false;
      try {
        $articlerResource = \Shopware\Components\Api\Manager::getResource('article');
         $toReturn = $articlerResource->update($articleId,$dataArray)->getId();
      } catch (\Exception $error) {

        $error = $error->getMessage();
      $_SESSION['j_error'] = $error;
        // header('Location:/Muster');
      }
      return $toReturn;

    }



}
