<?php

namespace App\Entity\Subscription;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Repository\SubscriptionRepository;
use App\Entity\TrainingPlan\TrainingPlanDay;
use App\Repository\TrainingPlanListRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass=SubscriptionRepository::class)
 * 
 */
class Subscription{
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
    private $idTrainer;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime")
     */
    private $expireAt;


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
     * Get the value of idTrainer
     */ 
    public function getIdTrainer()
    {
        return $this->idTrainer;
    }

    /**
     * Set the value of idTrainer
     *
     * @return  self
     */ 
    public function setIdTrainer($idTrainer)
    {
        $this->idTrainer = $idTrainer;

        return $this;
    }

    /**
     * Get the value of createdAt
     */ 
    public function getCreatedAt()
    {
        return $this->createdAt;
    }

    /**
     * Set the value of createdAt
     *
     * @return  self
     */ 
    public function setCreatedAt($createdAt)
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * Get the value of expireAt
     */ 
    public function getExpireAt()
    {
        return $this->expireAt;
    }

    /**
     * Set the value of expireAt
     *
     * @return  self
     */ 
    public function setExpireAt($expireAt)
    {
        $this->expireAt = $expireAt;

        return $this;
    }
}

?>