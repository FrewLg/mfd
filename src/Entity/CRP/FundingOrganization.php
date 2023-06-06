<?php

namespace App\Entity\CRP;

use App\Repository\CRP\FundingOrganizationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=FundingOrganizationRepository::class)
 * @ORM\Table(name="crp_funding_organization")
 * 
 */
class FundingOrganization
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $logo;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $createdAt;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acronym;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $acronymColor;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $detailsAboutOrg;

    /**
     * @ORM\ManyToMany(targetEntity=CollaborativeResearchProject::class, mappedBy="fundingOrganization")
     */
    private $collaborativeResearchProjects;

    /**
     * @ORM\OneToMany(targetEntity=CollaborativeResearchProject::class, mappedBy="principalInvestigatingOrganization")
     */
    private $piOrg;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $website;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $contact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $email;

    public function __construct()
    {
        $this->collaborativeResearchProjects = new ArrayCollection();
        $this->piOrg = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString(): string
    {
        return  $this->name;
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

    public function getLogo(): ?string
    {
        return $this->logo;
    }

    public function setLogo(?string $logo): self
    {
        $this->logo = $logo;

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

    public function getDetailsAboutOrg(): ?string
    {
        return $this->detailsAboutOrg;
    }

    public function setDetailsAboutOrg(?string $detailsAboutOrg): self
    {
        $this->detailsAboutOrg = $detailsAboutOrg;

        return $this;
    }

    /**
     * @return Collection<int, CollaborativeResearchProject>
     */
    public function getCollaborativeResearchProjects(): Collection
    {
        return $this->collaborativeResearchProjects;
    }

    public function addCollaborativeResearchProject(CollaborativeResearchProject $collaborativeResearchProject): self
    {
        if (!$this->collaborativeResearchProjects->contains($collaborativeResearchProject)) {
            $this->collaborativeResearchProjects[] = $collaborativeResearchProject;
            $collaborativeResearchProject->addFundingOrganization($this);
        }

        return $this;
    }

    public function removeCollaborativeResearchProject(CollaborativeResearchProject $collaborativeResearchProject): self
    {
        if ($this->collaborativeResearchProjects->removeElement($collaborativeResearchProject)) {
            $collaborativeResearchProject->removeFundingOrganization($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, CollaborativeResearchProject>
     */
    public function getPiOrg(): Collection
    {
        return $this->piOrg;
    }

    public function addPiOrg(CollaborativeResearchProject $piOrg): self
    {
        if (!$this->piOrg->contains($piOrg)) {
            $this->piOrg[] = $piOrg;
            $piOrg->setPrincipalInvestigatingOrganization($this);
        }

        return $this;
    }

    public function removePiOrg(CollaborativeResearchProject $piOrg): self
    {
        if ($this->piOrg->removeElement($piOrg)) {
            // set the owning side to null (unless already changed)
            if ($piOrg->getPrincipalInvestigatingOrganization() === $this) {
                $piOrg->setPrincipalInvestigatingOrganization(null);
            }
        }

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getContact(): ?string
    {
        return $this->contact;
    }

    public function setContact(?string $contact): self
    {
        $this->contact = $contact;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): self
    {
        $this->email = $email;

        return $this;
    }
}
