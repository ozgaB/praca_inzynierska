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
use App\Repository\TrainingPlanExerciseRepository;

/**
 * @Entity(repositoryClass=TrainingPlanExerciseRepository::class)
 * 
 */
class TrainingPlanExercise{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="TrainingPlanDay", inversedBy="trainingPlanExercise")
     * @JoinColumn(name="training_plan_day_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $trainingPlanDay;
    /**
     * @ORM\Column(type="string")
     * 
     */
    private $exerciseName;
    /**
     * @ORM\Column(type="string")
     * 
     */
    private $muscleGroup;
    /**
     * @ORM\Column(type="string")
     * 
     */
    private $sets;
     /**
     * @ORM\Column(type="string")
     * 
     */    
    private $repetition;
    /**
     * @ORM\Column(type="string")
     * 
     */
    private $time;
    /**
     * @ORM\Column(type="string")
     * 
     */
    private $break;

    /**
     * Get the value of exerciseName
     */ 
    public function getExerciseName()
    {
        return $this->exerciseName;
    }

    /**
     * Set user
     *
     * @param TrainingPlanDay $trainingPlanDay
     *
     * @return TrainingPlanDay
     */
    public function setTrainingPlanDay(TrainingPlanDay $trainingPlanDay)
    {
        $this->trainingPlanDay = $trainingPlanDay;

        return $this;
    }

    /**
     * Get user
     *
     * @return TrainingPlanDay
     */
    public function getTrainingPlanDay()
    {
        return $this->trainingPlanDay;
    }


    /**
     * Set the value of exerciseName
     *
     * @return  self
     */ 
    public function setExerciseName($exerciseName)
    {
        $this->exerciseName = $exerciseName;

        return $this;
    }

    /**
     * Get the value of muscleGroup
     */ 
    public function getMuscleGroup()
    {
        return $this->muscleGroup;
    }

    /**
     * Set the value of muscleGroup
     *
     * @return  self
     */ 
    public function setMuscleGroup($muscleGroup)
    {
        $this->muscleGroup = $muscleGroup;

        return $this;
    }

    /**
     * Get the value of set
     */ 
    public function getSets()
    {
        return $this->sets;
    }

    /**
     * Set the value of set
     *
     * @return  self
     */ 
    public function setSets($sets)
    {
        $this->sets = $sets;

        return $this;
    }

    /**
     * Get the value of repetition
     */ 
    public function getRepetition()
    {
        return $this->repetition;
    }

    /**
     * Set the value of repetition
     *
     * @return  self
     */ 
    public function setRepetition($repetition)
    {
        $this->repetition = $repetition;

        return $this;
    }

    /**
     * Get the value of time
     */ 
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set the value of time
     *
     * @return  self
     */ 
    public function setTime($time)
    {
        $this->time = $time;

        return $this;
    }


    /**
     * Get the value of break
     */ 
    public function getBreak()
    {
        return $this->break;
    }

    /**
     * Set the value of break
     *
     * @return  self
     */ 
    public function setBreak($break)
    {
        $this->break = $break;

        return $this;
    }
}


?>