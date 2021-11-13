<?php

namespace App\Entity\DietPlan;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Entity\DietPlan\DietPlanList;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Entity\DietPlan\DietPlanProducts;
use App\Repository\DietPlanDayRepository;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * @Entity(repositoryClass=DietPlanDayRepository::class)
 * 
 */
class DietPlanDay{

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @ManyToOne(targetEntity="DietPlanList", inversedBy="dietPlanDay")
     * @JoinColumn(name="id_diet_plan", referencedColumnName="id", nullable="true", onDelete="CASCADE")
     */
    private $dietPlan;

    /**
     * @ORM\Column(type="string")
     * 
     */
    private $dayName;

    /**
     * @ORM\OneToMany(targetEntity="DietPlanProducts", mappedBy="dietPlanDay", cascade={"remove","persist"}, orphanRemoval=true)
     */
    private $dietPlanProduct;

    public function __construct()
    {
        $this->dietPlanProduct = new ArrayCollection();
    }

    /**
     * Get the value of trainingPlan
     */ 
    public function getDietPlan()
    {
        return $this->dietPlan;
    }

    /**
     * Set the value of trainingPlan
     *
     * @var DietPlanList $trainingPlan
     * 
     * @return  DietPlanList
     */ 
    public function setDietPlan(DietPlanList $dietPlan)
    {
        $this->dietPlan = $dietPlan;

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
     * @return  DietPlanList
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

    public function addDietPlanProduct(DietPlanProducts $dietPlanProduct)
    {
        $this->dietPlanProduct[] = $dietPlanProduct;
        $dietPlanProduct->setDietPlanDay($this);

        return $this;
    }

    public function removeDietPlanProduct(DietPlanProducts $dietPlanProduct)
    {
        $this->dietPlanProduct->removeElement($dietPlanProduct);
    }

    /**
     * @return Collection
     */
    public function getDietPlanProduct()
    {
        return $this->dietPlanProduct;
    }
}


?>