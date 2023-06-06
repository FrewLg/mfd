<?php

namespace App\Entity;

use App\Repository\CollegeThematicAreaRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CollegeThematicAreaRepository::class)
 */
class CollegeThematicArea
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $descryption;

    /**
     * @ORM\ManyToOne(targetEntity=College::class, inversedBy="collegeThematicAreas" , cascade={"persist", "remove"})
     */
    private $college;

  

    public function __construct()
    {
        $this->callForProposals = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescryption(): ?string
    {
        return $this->descryption;
    }

    public function setDescryption(?string $descryption): self
    {
        $this->descryption = $descryption;

        return $this;
    }

    public function getCollege(): ?College
    {
        return $this->college;
    }

    public function setCollege(?College $college): self
    {
        $this->college = $college;

        return $this;
    }
    public function __toString()
    {
        
   return $this->name;
    }

   
}
