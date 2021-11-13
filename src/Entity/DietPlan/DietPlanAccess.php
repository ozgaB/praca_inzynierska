<?php

namespace App\Entity\DietPlan;

use Doctrine\ORM\Mapping\Id;
use App\Entity\Security\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Entity\TrainingPlan\DietPlanList;
use App\Repository\DietPlanAccessRepository;
use App\Entity\TrainingPlan\TrainingPlanList;
use App\Repository\TrainingPlanAccessRepository;

/**
 * @Entity(repositoryClass=DietPlanAccessRepository::class)
 * 
 */
class DietPlanAccess{
    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @GeneratedValue
     * @ORM\Column(type="integer")
     * @ManyToOne(targetEntity="DietPlanList")
     * @JoinColumn(name="id", referencedColumnName="idDietPlan")
     */
    private $idDietPlan;

    /**
     * @ORM\GeneratedValue  
     * @ORM\Column(type="integer")
     * @OneToMany(targetEntity="User")
     * @JoinColumn(name="id", referencedColumnName="idUser")
     */
    private $idUser;
    
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
     * Get the value of idDietPlan
     */ 
    public function getIdDietPlan()
    {
        return $this->idDietPlan;
    }

    /**
     * Set the value of idDietPlan
     *
     * @return  self
     */ 
    public function setIdDietPlan($idDietPlan)
    {
        $this->idDietPlan = $idDietPlan;

        return $this;
    }
}

?>