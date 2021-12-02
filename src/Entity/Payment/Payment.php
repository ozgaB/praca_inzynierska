<?php

namespace App\Entity\Payment;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\PaymentRepository;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Repository\SubscriptionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass=PaymentRepository::class)
 * 
 */
class Payment
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", unique=true)
     */
    private $id;
    
    /**
     * @ORM\Column(type="integer", unique=true)
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="id", referencedColumnName="idTrainer")
     */
    private $idUser;

    /**
     * @ORM\Column(type="integer", unique=true)
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="id", referencedColumnName="idTrainer")
     */
    private $idSubscriptionType;

    /**
     * @ORM\Column(type="string")
     */
     private $status;

    /**
     * Get the value of id
     */ 
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */ 
    public function setId($id)
    {
        $this->id = $id;

        return $this;
    }

    /**
     * Get the value of orderId
     */ 
    public function getOrderId()
    {
        return $this->orderId;
    }

    /**
     * Set the value of orderId
     *
     * @return  self
     */ 
    public function setOrderId($orderId)
    {
        $this->orderId = $orderId;

        return $this;
    }

    /**
     * Get the value of idUser
     */ 
    public function getIdUser()
    {
        return $this->idUser;
    }

    /**
     * Set the value of idUser
     *
     * @return  self
     */ 
    public function setIdUser($idUser)
    {
        $this->idUser = $idUser;

        return $this;
    }

    /**
     * Get the value of idSubscriptionType
     */ 
    public function getIdSubscriptionType()
    {
        return $this->idSubscriptionType;
    }

    /**
     * Set the value of idSubscriptionType
     *
     * @return  self
     */ 
    public function setIdSubscriptionType($idSubscriptionType)
    {
        $this->idSubscriptionType = $idSubscriptionType;

        return $this;
    }

     /**
      * Get the value of status
      */ 
     public function getStatus()
     {
          return $this->status;
     }

     /**
      * Set the value of status
      *
      * @return  self
      */ 
     public function setStatus($status)
     {
          $this->status = $status;

          return $this;
     }
}
?>