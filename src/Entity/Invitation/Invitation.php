<?php
namespace App\Entity\Invitation;

use App\Entity\Security\User;
use Doctrine\ORM\Mapping\Table;
use Doctrine\ORM\Mapping as ORM;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\OneToOne;
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\JoinColumn;
use App\Repository\InvitationRepository;
use Doctrine\ORM\Mapping\UniqueConstraint;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @Table(name="invitation", 
 *    uniqueConstraints={
 *        @UniqueConstraint(name="invitation_unique", 
 *            columns={"id_user", "id_trainer"},
 * )
 *    }
 * )
 * @ORM\Entity(repositoryClass=InvitationRepository::class)
 * @UniqueEntity(fields={"id_user","id_trainer"}, message="It looks like you send invi!")
 */
class Invitation {
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer", unique=true)
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
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @ManyToOne(targetEntity="User")
     * @JoinColumn(name="id", referencedColumnName="idTrainer")
     */
    private $idTrainer;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isSend = false;

    public function getId(): ?int
    {
        return $this->id;
    }

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

    public function getisSend(): ?bool
    {
        return $this->isSend;
    }

    public function setIsSend(bool $isSend): self
    {
        $this->isSend = $isSend;
        
        return $this;
    }

}
?>