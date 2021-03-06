<?php

namespace App\Entity\DietPlan;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Entity\DietPlan\DietPlanDay;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Repository\DietPlanListRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass=DietPlanListRepository::class)
 * 
 */
class DietPlanList{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @GeneratedValue
     * @ORM\Column(type="integer")
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="id", referencedColumnName="idTrainer")
     */
    private $idTrainer;

    /**
     * @ORM\Column(type="string")
     */
    private $planName;
    
    /**
     * @ORM\Column(type="string")
     * 
     */
    private $description;
    
    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity="DietPlanDay", mappedBy="dietPlan", cascade={"remove","persist"}, orphanRemoval=true)
     */
    private $dietPlanDay;

    public function __construct()
    {
        $this->dietPlanDay = new ArrayCollection();
    }

    public function setCreatedAt()
    {
        $this->createdAt = new \DateTime("now");
    }

    public function getCreatedAt(): self
    {
        return $this->createdAt;
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
     * Get the value of planName
     */ 
    public function getPlanName()
    {
        return $this->planName;
    }

    /**
     * Set the value of planName
     *
     * @return  self
     */ 
    public function setPlanName($planName)
    {
        $this->planName = $planName;

        return $this;
    }

    public function addDietPlanDay(DietPlanDay $dietPlanDay)
    {
        $this->dietPlanDay[] = $dietPlanDay;
        $dietPlanDay->setDietPlan($this);

        return $this;
    }

    public function removeDietPlanDay(DietPlanDay $dietPlanDay)
    {
        $this->dietPlanDay->removeElement($dietPlanDay);
    }

    /**
     * @return Collection
     */
    public function getDietPlanDay()
    {
        return $this->dietPlanDay;
    }
}

?>