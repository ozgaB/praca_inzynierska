<?php

namespace App\Entity\Subscription;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Repository\SubscriptionTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass=SubscriptionTypeRepository::class)
 * 
 */
class SubscriptionType
{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @ORM\Column(type="string")
     */
    private $name;
    
    /**
     * @ORM\Column(type="string")
     *
     */
    private $description;
    
    /**
     * @ORM\Column(type="bigint")
     */
    private $lifeTimeInSeconds;

    /**
     * @ORM\Column(type="integer")
     */
     private $pricePln;
     
    /**
     * @ORM\Column(type="integer")
     */
    private $priceEur;
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
     * Get the value of name
     */ 
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */ 
    public function setName($name)
    {
        $this->name = $name;

        return $this;
    }

    /**
     * Get the value of description
     */ 
    public function getDescription()
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */ 
    public function setDescription($description)
    {
        $this->description = $description;

        return $this;
    }

    /**
     * Get the value of lifeTimeInSeconds
     */ 
    public function getLifeTimeInSeconds()
    {
        return $this->lifeTimeInSeconds;
    }

    /**
     * Set the value of lifeTimeInSeconds
     *
     * @return  self
     */ 
    public function setLifeTimeInSeconds($lifeTimeInSeconds)
    {
        $this->lifeTimeInSeconds = $lifeTimeInSeconds;

        return $this;
    }

     /**
      * Get the value of pricePln
      */ 
     public function getPricePln()
     {
          return $this->pricePln;
     }

     /**
      * Set the value of pricePln
      *
      * @return  self
      */ 
     public function setPricePln($pricePln)
     {
          $this->pricePln = $pricePln;

          return $this;
     }

    /**
     * Get the value of priceEur
     */ 
    public function getPriceEur()
    {
        return $this->priceEur;
    }

    /**
     * Set the value of priceEur
     *
     * @return  self
     */ 
    public function setPriceEur($priceEur)
    {
        $this->priceEur = $priceEur;

        return $this;
    }
}
?>