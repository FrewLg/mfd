<?php

namespace App\Entity;

use App\Repository\FundingSchemeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FundingSchemeRepository::class)
 */
class FundingScheme
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
     * @ORM\Column(type="string", length=555, nullable=true)
     */
    private $description;

    /**
     * @ORM\ManyToMany(targetEntity=CallForProposal::class, mappedBy="fundingScheme")
     */
    private $callForProposals;

    /**
     * @ORM\OneToMany(targetEntity=Submission::class, mappedBy="fundingScheme")
     */
    private $submissions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acronym;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acronymColor;

    public function __construct()
    {
        $this->callForProposals = new ArrayCollection();
        $this->submissions = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function __toString(): string
    {
        return $this->name;
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
            $callForProposal->addFundingScheme($this);
        }

        return $this;
    }

    public function removeCallForProposal(CallForProposal $callForProposal): self
    {
        if ($this->callForProposals->removeElement($callForProposal)) {
            $callForProposal->removeFundingScheme($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Submission>
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function addSubmission(Submission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
            $submission->setFundfundingScheme($this);
        }

        return $this;
    }

    public function removeSubmission(Submission $submission): self
    {
        if ($this->submissions->removeElement($submission)) {
            // set the owning side to null (unless already changed)
            if ($submission->getFundfundingScheme() === $this) {
                $submission->setFundfundingScheme(null);
            }
        }

        return $this;
    }

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(?string $acronym): self
    {
        $this->acronym = $acronym;

        return $this;
    }

    public function getAcronymColor(): ?string
    {
        return $this->acronymColor;
    }

    public function setAcronymColor(?string $acronymColor): self
    {
        $this->acronymColor = $acronymColor;

        return $this;
    }
}
