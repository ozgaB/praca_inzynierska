<?php

namespace App\Entity\DietPlan;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Entity\TrainingPlan\TrainingPlanDay;
use App\Repository\DietPlanProductsRepository;
use App\Repository\TrainingPlanExerciseRepository;

/**
 * @Entity(repositoryClass=DietPlanProductsRepository::class)
 * 
 */
class DietPlanProducts{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer")
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="DietPlanDay", inversedBy="dietPlanProducts")
     * @JoinColumn(name="diet_plan_day_id", referencedColumnName="id", onDelete="CASCADE")
     */
    private $dietPlanDay;

    /**
     * @ORM\Column(type="string")
     * 
     */
    private $mealGroup;

    /**
     * @ORM\Column(type="string")
     * 
     */
    private $productName;

    /**
     * @ORM\Column(type="integer")
     * 
     */
    private $protein;

    /**
     * @ORM\Column(type="integer")
     * 
     */
    private $carbo;

    /**
     * @ORM\Column(type="integer")
     * 
     */
    private $fat;

    /**
     * @ORM\Column(type="integer")
     * 
     */
    private $kcl;

    /**
     * Set user
     *
     * @param DietPlanDay $trainingPlanDay
     *
     * @return DietPlanDay
     */
    public function setDietPlanDay(DietPlanDay $dietPlanDay)
    {
        $this->dietPlanDay = $dietPlanDay;

        return $this;
    }

    /**
     * Get user
     *
     * @return DietPlanDay
     */
    public function getDietPlanDay()
    {
        return $this->dietPlanDay;
    }

    /**
     * Get the value of mealGroup
     */ 
    public function getMealGroup()
    {
        return $this->mealGroup;
    }

    /**
     * Set the value of mealGroup
     *
     * @return  self
     */ 
    public function setMealGroup($mealGroup)
    {
        $this->mealGroup = $mealGroup;

        return $this;
    }

    /**
     * Get the value of productName
     */ 
    public function getProductName()
    {
        return $this->productName;
    }

    /**
     * Set the value of productName
     *
     * @return  self
     */ 
    public function setProductName($productName)
    {
        $this->productName = $productName;

        return $this;
    }

    /**
     * Get the value of protein
     */ 
    public function getProtein()
    {
        return $this->protein;
    }

    /**
     * Set the value of protein
     *
     * @return  self
     */ 
    public function setProtein($protein)
    {
        $this->protein = $protein;

        return $this;
    }

    /**
     * Get the value of carbo
     */ 
    public function getCarbo()
    {
        return $this->carbo;
    }

    /**
     * Set the value of carbo
     *
     * @return  self
     */ 
    public function setCarbo($carbo)
    {
        $this->carbo = $carbo;

        return $this;
    }

    /**
     * Get the value of fat
     */ 
    public function getFat()
    {
        return $this->fat;
    }

    /**
     * Set the value of fat
     *
     * @return  self
     */ 
    public function setFat($fat)
    {
        $this->fat = $fat;

        return $this;
    }

    /**
     * Get the value of kcl
     */ 
    public function getKcl()
    {
        return $this->kcl;
    }

    /**
     * Set the value of kcl
     *
     * @return  self
     */ 
    public function setKcl($kcl)
    {
        $this->kcl = $kcl;

        return $this;
    }
}


?>