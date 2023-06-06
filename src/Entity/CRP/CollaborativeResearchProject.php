<?php

namespace App\Entity\CRP;

use App\Entity\User;
use App\Repository\CRP\CollaborativeResearchProjectRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;


/**
 * @ORM\Entity(repositoryClass=CollaborativeResearchProjectRepository::class)
 * @ORM\Table(name="crp_collaborative_research_project")
 * 
 */
class CollaborativeResearchProject
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
    private $title;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $shortDescription;

   
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $YearOfCemmencement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $EndDate;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\College::class, inversedBy="collaborativeResearchProjects")
     */
    private $ResponsiblePrimaryInstitute;

    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\User::class, inversedBy="collaborativeResearchProjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $PrincipalInvestigator;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $FundingOpportunityName;

    /**
     * @ORM\Column(type="integer")
     */
    private $AmountOfGrant;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $ThematicArea;

    /**
     * @ORM\ManyToOne(targetEntity=Currency::class, inversedBy="collaborativeResearchProjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $Currency;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectType::class, inversedBy="collaborativeResearchProjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ProjectType;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectStatus::class, inversedBy="collaborativeResearchProjects")
     * @ORM\JoinColumn(nullable=false)
     */
    private $ProjectStatus;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $OtherInsitutes;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="collaborativeResearchProjectsAsCoPI")
     */
    private $CoPrincipalInvestigator;

   
    /**
     * @ORM\ManyToMany(targetEntity=FundingOrganization::class, inversedBy="collaborativeResearchProjects")
     */
    private $fundingOrganization;
   
    /**
     * @ORM\ManyToMany(targetEntity=User::class, inversedBy="coInvestigators")
     */
    private $coInvestigators;

    /**
     * @ORM\ManyToOne(targetEntity=FundingOrganization::class, inversedBy="piOrg")
     */
    private $principalInvestigatingOrganization;

    /**
     * @ORM\OneToMany(targetEntity=Deliverables::class, cascade={"persist", "remove"}, mappedBy="collaborativeResearchProject", orphanRemoval=true)
     */
    private $deliverables;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectProgress::class, inversedBy="project")
     */
    private $projectProgress;

    /**
     * @ORM\OneToMany(targetEntity=ProjectAttachment::class, mappedBy="project", orphanRemoval=true)
     */
    private $projectAttachments;

    

    public function __construct()
    {
        $this->coInvestigators = new ArrayCollection();
        $this->fundingOrganization = new ArrayCollection();
        $this->deliverables = new ArrayCollection();
        $this->projectAttachments = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    function __toString()
    {
  
          return "".$this->title;
    }
  
    

    public function setTitle(?string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getShortDescription(): ?string
    {
        return $this->shortDescription;
    }

    public function setShortDescription(?string $shortDescription): self
    {
        $this->shortDescription = $shortDescription;

        return $this;
    }

  

    public function getYearOfCemmencement(): ?\DateTimeInterface
    {
        return $this->YearOfCemmencement;
    }

    public function setYearOfCemmencement(?\DateTimeInterface $YearOfCemmencement): self
    {
        $this->YearOfCemmencement = $YearOfCemmencement;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->EndDate;
    }

    public function setEndDate(?\DateTimeInterface $EndDate): self
    {
        $this->EndDate = $EndDate;

        return $this;
    }

    public function getResponsiblePrimaryInstitute(): ?\App\Entity\College
    {
        return $this->ResponsiblePrimaryInstitute;
    }

    public function setResponsiblePrimaryInstitute(?\App\Entity\College $ResponsiblePrimaryInstitute): self
    {
        $this->ResponsiblePrimaryInstitute = $ResponsiblePrimaryInstitute;

        return $this;
    }

    public function getPrincipalInvestigator(): ?\App\Entity\User
    {
        return $this->PrincipalInvestigator;
    }

    public function setPrincipalInvestigator(?\App\Entity\User $PrincipalInvestigator): self
    {
        $this->PrincipalInvestigator = $PrincipalInvestigator;

        return $this;
    }

    public function getFundingOpportunityName(): ?string
    {
        return $this->FundingOpportunityName;
    }

    public function setFundingOpportunityName(?string $FundingOpportunityName): self
    {
        $this->FundingOpportunityName = $FundingOpportunityName;

        return $this;
    }

    public function getAmountOfGrant(): ?int
    {
        return $this->AmountOfGrant;
    }

    public function setAmountOfGrant(int $AmountOfGrant): self
    {
        $this->AmountOfGrant = $AmountOfGrant;

        return $this;
    }

    public function getThematicArea(): ?string
    {
        return $this->ThematicArea;
    }

    public function setThematicArea(?string $ThematicArea): self
    {
        $this->ThematicArea = $ThematicArea;

        return $this;
    }

    public function getCurrency(): ?Currency
    {
        return $this->Currency;
    }

    public function setCurrency(?Currency $Currency): self
    {
        $this->Currency = $Currency;

        return $this;
    }

    public function getProjectType(): ?ProjectType
    {
        return $this->ProjectType;
    }

    public function setProjectType(?ProjectType $ProjectType): self
    {
        $this->ProjectType = $ProjectType;

        return $this;
    }

    public function getProjectStatus(): ?ProjectStatus
    {
        return $this->ProjectStatus;
    }

    public function setProjectStatus(?ProjectStatus $ProjectStatus): self
    {
        $this->ProjectStatus = $ProjectStatus;

        return $this;
    }

    public function getOtherInsitutes(): ?string
    {
        return $this->OtherInsitutes;
    }

    public function setOtherInsitutes(?string $OtherInsitutes): self
    {
        $this->OtherInsitutes = $OtherInsitutes;

        return $this;
    }

    public function getCoPrincipalInvestigator(): ?User
    {
        return $this->CoPrincipalInvestigator;
    }

    public function setCoPrincipalInvestigator(?User $CoPrincipalInvestigator): self
    {
        $this->CoPrincipalInvestigator = $CoPrincipalInvestigator;

        return $this;
    }

    /**
     * @return Collection<int, coInvestigator>
     */
    public function getcoInvestigators(): Collection
    {
        return $this->coInvestigators;
    }

    public function addcoInvestigator(User $coInvestigator): self
    {
        if (!$this->coInvestigators->contains($coInvestigator)) {
            $this->coInvestigators[] = $coInvestigator;
        }

        return $this;
    }

    public function removecoInvestigator(User $coInvestigator): self
    {
        $this->coInvestigators->removeElement($coInvestigator);

        return $this;
    }

    /**
     * @return Collection<int, FundingOrganization>
     */
    public function getFundingOrganization(): Collection
    {
        return $this->fundingOrganization;
    }

    public function addFundingOrganization(FundingOrganization $fundingOrganization): self
    {
        if (!$this->fundingOrganization->contains($fundingOrganization)) {
            $this->fundingOrganization[] = $fundingOrganization;
        }

        return $this;
    }

    public function removeFundingOrganization(FundingOrganization $fundingOrganization): self
    {
        $this->fundingOrganization->removeElement($fundingOrganization);

        return $this;
    }

    public function getPrincipalInvestigatingOrganization(): ?FundingOrganization
    {
        return $this->principalInvestigatingOrganization;
    }

    public function setPrincipalInvestigatingOrganization(?FundingOrganization $principalInvestigatingOrganization): self
    {
        $this->principalInvestigatingOrganization = $principalInvestigatingOrganization;

        return $this;
    }

    /**
     * @return Collection<int, Deliverables>
     */
    public function getDeliverables(): Collection
    {
        return $this->deliverables;
    }

    public function addDeliverable(Deliverables $deliverable): self
    {
        if (!$this->deliverables->contains($deliverable)) {
            $this->deliverables[] = $deliverable;
            $deliverable->setCollaborativeResearchProject($this);
        }

        return $this;
    }

    public function removeDeliverable(Deliverables $deliverable): self
    {
        if ($this->deliverables->removeElement($deliverable)) {
            // set the owning side to null (unless already changed)
            if ($deliverable->getCollaborativeResearchProject() === $this) {
                $deliverable->setCollaborativeResearchProject(null);
            }
        }

        return $this;
    }

    public function getProjectProgress(): ?ProjectProgress
    {
        return $this->projectProgress;
    }

    public function setProjectProgress(?ProjectProgress $projectProgress): self
    {
        $this->projectProgress = $projectProgress;

        return $this;
    }

    /**
     * @return Collection<int, ProjectAttachment>
     */
    public function getProjectAttachments(): Collection
    {
        return $this->projectAttachments;
    }

    public function addProjectAttachment(ProjectAttachment $projectAttachment): self
    {
        if (!$this->projectAttachments->contains($projectAttachment)) {
            $this->projectAttachments[] = $projectAttachment;
            $projectAttachment->setProject($this);
        }

        return $this;
    }

    public function removeProjectAttachment(ProjectAttachment $projectAttachment): self
    {
        if ($this->projectAttachments->removeElement($projectAttachment)) {
            // set the owning side to null (unless already changed)
            if ($projectAttachment->getProject() === $this) {
                $projectAttachment->setProject(null);
            }
        }

        return $this;
    }
 
}
