<?php

namespace App\Entity\Participant;


use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\Id;
use App\Entity\Security\User;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\ParticipantRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @Entity(repositoryClass=ParticipantRepository::class)
 * @UniqueEntity("id",message="")
 */
class Participant{
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
     * @ORM\GeneratedValue  
     * @ORM\Column(type="integer")
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="id", referencedColumnName="idUser")
     */
    private $idUser;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }

    public function getIdTrainer(): ?int
    {
        return $this->idTrainer;
    }

    public function setIdUser(int $idUser): self
    {
        $this->idUser = $idUser;
        
        return $this;
    }

    public function setIdTrainer(int $idTrainer): self
    {
        $this->idTrainer = $idTrainer;
        
        return $this;
    }



}
?>