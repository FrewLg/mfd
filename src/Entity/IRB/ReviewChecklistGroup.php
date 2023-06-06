<?php

namespace App\Entity\IRB;

use App\Repository\IRB\ReviewChecklistGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewChecklistGroupRepository::class)
 */
class ReviewChecklistGroup
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
     * @ORM\OneToMany(targetEntity=ReviewChecklist::class, mappedBy="checklistGroup", orphanRemoval=true)
     */
    private $reviewChecklists;

    public function __construct()
    {
        $this->reviewChecklists = new ArrayCollection();
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
     * @return Collection|ReviewChecklist[]
     */
    public function getReviewChecklists(): Collection
    {
        return $this->reviewChecklists;
    }

    public function addReviewChecklist(ReviewChecklist $reviewChecklist): self
    {
        if (!$this->reviewChecklists->contains($reviewChecklist)) {
            $this->reviewChecklists[] = $reviewChecklist;
            $reviewChecklist->setChecklistGroup($this);
        }

        return $this;
    }

    public function removeReviewChecklist(ReviewChecklist $reviewChecklist): self
    {
        if ($this->reviewChecklists->removeElement($reviewChecklist)) {
            // set the owning side to null (unless already changed)
            if ($reviewChecklist->getChecklistGroup() === $this) {
                $reviewChecklist->setChecklistGroup(null);
            }
        }

        return $this;
    }
}
