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

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="TrainingPlanList", inversedBy="trainingPlanDay")
     * @JoinColumn(name="id_training_plan", referencedColumnName="id", nullable="true", onDelete="CASCADE")
     */
    private $trainingPlan;

    /**
     * @ORM\Column(type="string")
     * 
     */
    private $dayName;

    /**
     * @ORM\OneToMany(targetEntity="TrainingPlanExercise", mappedBy="trainingPlanDay", cascade={"remove","persist"}, orphanRemoval=true)
     */
    private $trainingPlanExercise;

    public function __construct()
    {
        $this->trainingPlanExercise = new ArrayCollection();
    }

    /**
     * Get the value of trainingPlan
     */ 
    public function getTrainingPlan()
    {
        return $this->trainingPlan;
    }

    /**
     * Set the value of trainingPlan
     *
     * @var TrainingPlanList $trainingPlan
     * 
     * @return  TrainingPlanList
     */ 
    public function setTrainingPlan(TrainingPlanList $trainingPlan)
    {
        $this->trainingPlan = $trainingPlan;

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
     * @return  TrainingPlanList
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
    public function getTrainingPlanExercise()
    {
        return $this->trainingPlanExercise;
    }
}


?>