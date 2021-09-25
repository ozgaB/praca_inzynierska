<?php

namespace App\Entity\TrainingPlan;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\Common\Collections\Collection;
use App\Entity\TrainingPlan\TrainingPlanList;
use App\Repository\TrainingPlanDayRepository;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass=TrainingPlanDayRepository::class)
 * 
 */
class TrainingPlanDay{

    public function __construct()
    {
        $this->trainingPlanExercise = new ArrayCollection();
    }

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @GeneratedValue
     * @ORM\Column(type="integer")
     * @ManyToOne(targetEntity="TrainingPlanList")
     * @JoinColumn(name="id", referencedColumnName="idTrainingPlan")
     */
    private $idTrainingPlan;

    /**
     * @ORM\Column(type="string")
     * 
     */
    private $dayName;

    /**
     * @ORM\OneToMany(targetEntity="TrainingPlanExercise", mappedBy="trainingPlanDay", cascade={"persist"})
     */
    private $trainingPlanExercise;

    /**
     * Get the value of idTrainingPlan
     */ 
    public function getIdTrainingPlan()
    {
        return $this->idTrainingPlan;
    }

    /**
     * Set the value of idTrainingPlan
     *
     * @return  self
     */ 
    public function setIdTrainingPlan($idTrainingPlan)
    {
        $this->idTrainingPlan = $idTrainingPlan;

        return $this;
    }

    /**
     * Get the value of dayName
     */ 
    public function getDayName()
    {
        return $this->dayName;
    }

    /**
     * Set the value of dayName
     *
     * @return  self
     */ 
    public function setDayName($dayName)
    {
        $this->dayName = $dayName;

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

    public function addTrainingPlanExercise(TrainingPlanExercise $trainingPlanExercise)
    {
        $this->trainingPlanExercise[] = $trainingPlanExercise;
        $trainingPlanExercise->setTrainingPlanDay($this);

        return $this;
    }

    public function removeTrainingPlanExercise(TrainingPlanExercise $trainingPlanExercise)
    {
        $this->trainingPlanExercise->removeElement($trainingPlanExercise);
    }

    /**
     * @return Collection
     */
    public function getTrainingPLanExercise()
    {
        return $this->trainingPlanExercise;
    }
}


?>