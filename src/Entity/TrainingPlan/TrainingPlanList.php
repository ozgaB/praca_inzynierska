<?php

namespace App\Entity\TrainingPlan;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Entity\TrainingPlan\TrainingPlanDay;
use App\Repository\TrainingPlanListRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass=TrainingPlanListRepository::class)
 * 
 */
class TrainingPlanList{
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
     * @ORM\OneToMany(targetEntity="TrainingPlanDay", mappedBy="trainingPlan", cascade={"remove","persist"}, orphanRemoval=true)
     */
    private $trainingPlanDay;

    public function __construct()
    {
        $this->trainingPlanDay = new ArrayCollection();
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

    public function addTrainingPlanDay(TrainingPlanDay $trainingPlanDay)
    {
        $this->trainingPlanDay[] = $trainingPlanDay;
        $trainingPlanDay->setTrainingPlan($this);

        return $this;
    }

    public function removeTrainingPlanDay(TrainingPlanDay $trainingPlanDay)
    {
        $this->trainingPlanDay->removeElement($trainingPlanDay);
    }

    /**
     * @return Collection
     */
    public function getTrainingPlanDay()
    {
        return $this->trainingPlanDay;
    }
}

?>