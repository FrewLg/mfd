<?php

namespace App\Entity\IRB;

use App\Repository\IRB\ResearchSubjectCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResearchSubjectCategoryRepository::class)
 */
class ResearchSubjectCategory
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=ResearchSubject::class, mappedBy="category")
     */
    private $researchSubjects;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    public function __construct()
    {
        $this->researchSubjects = new ArrayCollection();
    }
    public function __toString()
    {
        return $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|ResearchSubject[]
     */
    public function getResearchSubjects(): Collection
    {
        return $this->researchSubjects;
    }

    public function addResearchSubject(ResearchSubject $researchSubject): self
    {
        if (!$this->researchSubjects->contains($researchSubject)) {
            $this->researchSubjects[] = $researchSubject;
            $researchSubject->setCategory($this);
        }

        return $this;
    }

    public function removeResearchSubject(ResearchSubject $researchSubject): self
    {
        if ($this->researchSubjects->removeElement($researchSubject)) {
            // set the owning side to null (unless already changed)
            if ($researchSubject->getCategory() === $this) {
                $researchSubject->setCategory(null);
            }
        }

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }
}
