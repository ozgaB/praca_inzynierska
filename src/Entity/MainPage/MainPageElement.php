<?php
namespace App\Entity\MainPage;

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
 * @Entity(repositoryClass=MainPageRepository::class)
 * 
 */
class MainPageElement
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25, nullable="true")
     */
    private $logoText;

    /**
     * @ORM\Column(type="string", length=180, nullable="true")
     */
    private $firstText;

        /**
     * @ORM\Column(type="string", length=180, nullable="true")
     */
    private $secondText;

    /**
     * @ORM\Column(type="boolean", nullable="true")
     */
    private $trainerSectionVisible;

    /**
     * Get the value of logoText
     */ 
    public function getLogoText()
    {
        return $this->logoText;
    }

    /**
     * Set the value of logoText
     *
     * @return  self
     */ 
    public function setLogoText($logoText)
    {
        $this->logoText = $logoText;

        return $this;
    }

    /**
     * Get the value of firstText
     */ 
    public function getFirstText()
    {
        return $this->firstText;
    }

    /**
     * Set the value of firstText
     *
     * @return  self
     */ 
    public function setFirstText($firstText)
    {
        $this->firstText = $firstText;

        return $this;
    }

    /**
     * Get the value of secondText
     */ 
    public function getSecondText()
    {
        return $this->secondText;
    }

    /**
     * Set the value of secondText
     *
     * @return  self
     */ 
    public function setSecondText($secondText)
    {
        $this->secondText = $secondText;

        return $this;
    }

    /**
     * Get the value of trainerSectionVisible
     */ 
    public function getTrainerSectionVisible()
    {
        return $this->trainerSectionVisible;
    }

    /**
     * Set the value of trainerSectionVisible
     *
     * @return  self
     */ 
    public function setTrainerSectionVisible($trainerSectionVisible = true)
    {
        $this->trainerSectionVisible = $trainerSectionVisible;

        return $this;
    }
}
?>