<?php

namespace App\Entity\TrainingPlan;

use Doctrine\ORM\Mapping\Id;
use App\Entity\Security\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\JoinColumn;
use Doctrine\ORM\Mapping\GeneratedValue;
use App\Entity\TrainingPlan\TrainingPlanList;
use App\Repository\TrainingPlanAccessRepository;

/**
 * @Entity(repositoryClass=TrainingPlanAccessRepository::class)
 * 
 */
class TrainingPlanAccess{
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
     * @ORM\GeneratedValue  
     * @ORM\Column(type="integer")
     * @OneToMany(targetEntity="User")
     * @JoinColumn(name="id", referencedColumnName="idUser")
     */
    private $idUser;



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
}

?>