<?php

namespace App\Entity\IRB;

use App\Repository\IRB\ReviewChecklistRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewChecklistRepository::class)
 */
class ReviewChecklist
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ReviewChecklistGroup::class, inversedBy="reviewChecklists")
     * @ORM\JoinColumn(nullable=false)
     */
    private $checklistGroup;

    /**
     * @ORM\Column(type="text")
     */
    private $name;

    /**
     * @ORM\Column(type="integer")
     */
    private $answerType;

    /**
     * @ORM\ManyToOne(targetEntity=ReviewChecklist::class, inversedBy="reviewChecklists")
     */
    private $parent;

    /**
     * @ORM\OneToMany(targetEntity=ReviewChecklist::class, mappedBy="parent")
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

    public function getChecklistGroup(): ?ReviewChecklistGroup
    {
        return $this->checklistGroup;
    }

    public function setChecklistGroup(?ReviewChecklistGroup $checklistGroup): self
    {
        $this->checklistGroup = $checklistGroup;

        return $this;
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

    public function getAnswerType(): ?int
    {
        return $this->answerType;
    }

    public function setAnswerType(int $answerType): self
    {
        $this->answerType = $answerType;

        return $this;
    }

    public function getParent(): ?self
    {
        return $this->parent;
    }

    public function setParent(?self $parent): self
    {
        $this->parent = $parent;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getReviewChecklists(): Collection
    {
        return $this->reviewChecklists;
    }

    public function addReviewChecklist(self $reviewChecklist): self
    {
        if (!$this->reviewChecklists->contains($reviewChecklist)) {
            $this->reviewChecklists[] = $reviewChecklist;
            $reviewChecklist->setParent($this);
        }

        return $this;
    }

    public function removeReviewChecklist(self $reviewChecklist): self
    {
        if ($this->reviewChecklists->removeElement($reviewChecklist)) {
            // set the owning side to null (unless already changed)
            if ($reviewChecklist->getParent() === $this) {
                $reviewChecklist->setParent(null);
            }
        }

        return $this;
    }
}
