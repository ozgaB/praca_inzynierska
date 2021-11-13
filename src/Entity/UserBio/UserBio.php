<?php

namespace App\Entity\UserBio;

use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\UserBioRepository;
use Doctrine\ORM\Mapping\GeneratedValue;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @Entity(repositoryClass=UserBioRepository::class)
 * 
 */
class UserBio {

    /**
     * @Id
     * @GeneratedValue
     * @Column(type="integer", unique=true)
     */
    private $id;

    /**
     * @ORM\GeneratedValue  
     * @ORM\Column(type="integer")
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="id", referencedColumnName="idUser")
     */
    private $idUser;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isPublic;

    /**
     * @ORM\Column(type="integer", length=2)
     */
    private $age;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $height;

    /**
     * @ORM\Column(type="integer", length=3)
     */
    private $weight;

    /**
     * @ORM\Column(type="integer", length=3, nullable="true")
     */
    private $targetWeight;

    /**
     * @ORM\Column(type="integer", length=2, nullable="true")
     */
    private $actualBmi;

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
     * Get the value of isPublic
     */ 
    public function getIsPublic()
    {
        return $this->isPublic;
    }

    /**
     * Set the value of isPublic
     *
     * @return  self
     */ 
    public function setIsPublic($isPublic)
    {
        $this->isPublic = $isPublic;

        return $this;
    }

    /**
     * Get the value of phoneNumber
     */ 
    public function getPhoneNumber()
    {
        return $this->phoneNumber;
    }

    /**
     * Set the value of phoneNumber
     *
     * @return  self
     */ 
    public function setPhoneNumber($phoneNumber)
    {
        $this->phoneNumber = $phoneNumber;

        return $this;
    }

    /**
     * Get the value of adress
     */ 
    public function getAdress()
    {
        return $this->adress;
    }

    /**
     * Set the value of adress
     *
     * @return  self
     */ 
    public function setAdress($adress)
    {
        $this->adress = $adress;

        return $this;
    }

    /**
     * Get the value of age
     */ 
    public function getAge()
    {
        return $this->age;
    }

    /**
     * Set the value of age
     *
     * @return  self
     */ 
    public function setAge($age)
    {
        $this->age = $age;

        return $this;
    }

    /**
     * Get the value of height
     */ 
    public function getHeight()
    {
        return $this->height;
    }

    /**
     * Set the value of height
     *
     * @return  self
     */ 
    public function setHeight($height)
    {
        $this->height = $height;

        return $this;
    }

    /**
     * Get the value of targetWeight
     */ 
    public function getTargetWeight()
    {
        return $this->targetWeight;
    }

    /**
     * Set the value of targetWeight
     *
     * @return  self
     */ 
    public function setTargetWeight($targetWeight)
    {
        $this->targetWeight = $targetWeight;

        return $this;
    }

    /**
     * Get the value of actualBmi
     */ 
    public function getActualBmi()
    {
        return $this->actualBmi;
    }

    /**
     * Set the value of actualBmi
     *
     * @return  self
     */ 
    public function setActualBmi($actualBmi)
    {
        $this->actualBmi = $actualBmi;

        return $this;
    }


    /**
     * Get the value of weight
     */ 
    public function getWeight()
    {
        return $this->weight;
    }

    /**
     * Set the value of weight
     *
     * @return  self
     */ 
    public function setWeight($weight)
    {
        $this->weight = $weight;

        return $this;
    }
}
?>