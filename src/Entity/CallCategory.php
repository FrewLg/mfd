<?php

namespace App\Entity;

use App\Repository\CallCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CallCategoryRepository::class)
 */
class CallCategory
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $descryption;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\OneToMany(targetEntity=CallForProposal::class, mappedBy="callType")
     */
    private $callForProposals;

    /**
     * @ORM\ManyToOne(targetEntity=College::class, inversedBy="callCategories")
     * @ORM\JoinColumn(nullable=false)
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

    public function setName(string $name): self
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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    /**
     * @return Collection<int, CallForProposal>
     */
    public function getCallForProposals(): Collection
    {
        return $this->callForProposals;
    }

    public function addCallForProposal(CallForProposal $callForProposal): self
    {
        if (!$this->callForProposals->contains($callForProposal)) {
            $this->callForProposals[] = $callForProposal;
            $callForProposal->setCallType($this);
        }

        return $this;
    }

    public function removeCallForProposal(CallForProposal $callForProposal): self
    {
        if ($this->callForProposals->removeElement($callForProposal)) {
            // set the owning side to null (unless already changed)
            if ($callForProposal->getCallType() === $this) {
                $callForProposal->setCallType(null);
            }
        }

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
}
