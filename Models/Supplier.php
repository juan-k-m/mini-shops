<?php

namespace Vendedor\Models;

use Shopware\Components\Model\ModelEntity;
use Doctrine\ORM\Mapping as ORM;
use DateTime;
/**
* @ORM\Table(name="s_plugin_supplier")
* @ORM\Entity(repositoryClass="Repository")
*/
class Supplier extends ModelEntity
{
  /**
  * Primary Key - autoincrement value
  *
  * @var integer $id
  *
  * @ORM\Column(name="id", type="integer", nullable=false)
  * @ORM\Id
  * @ORM\GeneratedValue(strategy="IDENTITY")
  */
  private $id;

  /**
  * @var string $name
  *
  * @ORM\Column(name="name", type="string", nullable=false)
  */
  private $name;

  /**
  * @var integer $userId
  *
  * @ORM\Column(name="user_id", type="integer", nullable=false)
  */
  private $userId;

  /**
  * @var integer $supplierId
  *
  * @ORM\Column(name="supplier_id", type="integer", nullable=false)
  */
  private $supplierId;

  /**
  * @var string $orderdata
  *
  * @ORM\Column(name="orderdata", type="text", nullable=false)
  */
  private $orderdata;

  /**
  * @var string $documentHash
  *
  * @ORM\Column(name="document_hash", type="string", nullable=false)
  */
  private $documentHash;

  /**
  * @var \DateTimeInterface
  *
  * @ORM\Column(name="ordertime", type="datetime", nullable=false)
  */
  private $orderTime = null;

  /**
  * @var integer $orderParentId
  *
  * @ORM\Column(name="order_parent_id", type="integer", nullable=false)
  */
  private $orderParentId;

  /**
  * @return int
  */
  public function getId()
  {
    return $this->id;
  }

  /**
  * @return string
  */
  public function getName()
  {
    return $this->name;
  }

  /**
  * @param $name string
  */
  public function setName($name)
  {
    $this->name = $name;
  }

  /**
  * @return int
  */
  public function getUserId()
  {
    return $this->userId;
  }

  /**
  * @param int $setUserId
  */
  public function setUserId($userId)
  {
    $this->userId = $userId;
  }

  /**
  * @return int
  */
  public function getSupplierId()
  {
    return $this->supplierId;
  }

  /**
  * @param int $setSupplierId
  */
  public function setSupplierId($supplierId)
  {
    $this->supplierId = $supplierId;
  }

  /**
  * @return int
  */
  public function getOrderParentId()
  {
    return $this->orderParentId;
  }

  /**
  * @param int $orderParentId
  */
  public function setOrderParentId($orderParentId)
  {
    $this->orderParentId = $orderParentId;
  }


  /**
  * @return string
  */
  public function getDocumentHash()
  {
    return $this->documentHash;
  }

  /**
  * @param $setDocumentHash string
  */
  public function setDocumentHash($documentHash)
  {
    $this->documentHash = $documentHash;
  }


  /**
  * @return string
  */
  public function getOrderdata()
  {
    return $this->orderdata;
  }

  /**
  * @param $setOrderdata text
  */
  public function setOrderdata($orderdata)
  {
    $this->orderdata = $orderdata;
  }

  /**
   * @param \DateTimeInterface|string $orderTime
   *
   */
  public function setOrderTime($orderTime)
  {
      if (!$orderTime instanceof \DateTimeInterface && is_string($orderTime)) {
          $orderTime = new \DateTime($orderTime);
      }
      $this->orderTime = $orderTime;

      return $this;
  }

  /**
   * @return \DateTimeInterface
   */
  public function getOrderTime()
  {
      return $this->orderTime;
  }

}
