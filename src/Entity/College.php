<?php

namespace App\Entity;

use App\Entity\IRB\BoardMember;
use App\Repository\CollegeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=CollegeRepository::class)
 */
class College
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
     * @ORM\OneToMany(targetEntity=CollegeCoordinator::class, mappedBy="college")
     */
    private $collegeCoordinators;
    /**
     * @ORM\OneToMany(targetEntity=UserInfo::class, mappedBy="college")
     */
    private $registeredUsers;

    /**
     * @ORM\OneToMany(targetEntity=Department::class, mappedBy="college")
     */
    private $departments;

    /**
     * @ORM\OneToMany(targetEntity=CallForProposal::class, mappedBy="college")
     */
    private $callForProposals;
    /**
     * @ORM\OneToMany(targetEntity=CallForProposal::class, mappedBy="college")
     */
    private $applications;

    /**
     * @ORM\Column(type="string", length=500, nullable=true)
     */
    private $principal_contact;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $identification_code;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $mission;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $objective;

    /**
     * @ORM\OneToMany(targetEntity=ThematicArea::class, mappedBy="college")
     */
    private $thematicAreas;

    /**
     * @ORM\OneToMany(targetEntity=Submission::class, mappedBy="college")
     */
    private $submissions;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $prefix;

    /**
     * @ORM\OneToOne(targetEntity=GuidelineForReviewer::class, mappedBy="college", cascade={"persist", "remove"})
     */
    private $guidelineForReviewer;

 

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Guidelines::class, mappedBy="college")
     */
    private $guidelines;

    /**
     * @ORM\OneToMany(targetEntity=CallForTraining::class, mappedBy="college")
     */
    private $callForTrainings;

    /**
     * @ORM\OneToMany(targetEntity=BoardMember::class, mappedBy="college")
     */
    private $boardMembers;

    /**
     * @ORM\OneToMany(targetEntity=\App\Entity\IRB\IrbReviewAtachement::class, mappedBy="college")
     */
    private $irbReviewAtachements;

    /**
     * @ORM\OneToMany(targetEntity=CallCategory::class, mappedBy="college")
     */
    private $callCategories;

    /**
     * @ORM\OneToMany(targetEntity=CollegeThematicArea::class, mappedBy="college")
     */
    private $collegeThematicAreas;

    /**
     * @ORM\OneToMany(targetEntity=\App\Entity\CRP\CollaborativeResearchProject::class, mappedBy="ResponsiblePrimaryInstitute")
     */
    private $collaborativeResearchProjects;


 
   

    public function __construct()
    {
        $this->collegeCoordinators = new ArrayCollection();
        $this->registeredUsers = new ArrayCollection();
        $this->departments = new ArrayCollection();
        $this->callForProposals = new ArrayCollection();
        $this->applications = new ArrayCollection();
        $this->thematicAreas = new ArrayCollection();
        $this->submissions = new ArrayCollection();
        // $this->guidelineForReviewers = new ArrayCollection();
         $this->guidelines = new ArrayCollection();
        $this->callForTrainings = new ArrayCollection();
        $this->boardMembers = new ArrayCollection();
        $this->irbReviewAtachements = new ArrayCollection();
        $this->callCategories = new ArrayCollection();
        $this->collegeThematicAreas = new ArrayCollection();
        $this->collaborativeResearchProjects = new ArrayCollection();
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

    public function __toString()
    {
        
   return $this->name;
    }
    
    /**
     * @return Collection|UserInfo[]
     */
    public function getRegisteredUsers(): Collection
    {
        return $this->registeredUsers;
    }
    /**
     * @return Collection|CollegeCoordinator[]
     */
    public function getCollegeCoordinators(): Collection
    {
        return $this->collegeCoordinators;
    }

    public function addCollegeCoordinator(CollegeCoordinator $collegeCoordinator): self
    {
        if (!$this->collegeCoordinators->contains($collegeCoordinator)) {
            $this->collegeCoordinators[] = $collegeCoordinator;
            $collegeCoordinator->setCollege($this);
        }

        return $this;
    }

    public function removeCollegeCoordinator(CollegeCoordinator $collegeCoordinator): self
    {
        if ($this->collegeCoordinators->removeElement($collegeCoordinator)) {
            // set the owning side to null (unless already changed)
            if ($collegeCoordinator->getCollege() === $this) {
                $collegeCoordinator->setCollege(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Department[]
     */
    public function getDepartments(): Collection
    {
        return $this->departments;
    }

    public function addDepartment(Department $department): self
    {
        if (!$this->departments->contains($department)) {
            $this->departments[] = $department;
            $department->setCollege($this);
        }

        return $this;
    }

    public function removeDepartment(Department $department): self
    {
        if ($this->departments->removeElement($department)) {
            // set the owning side to null (unless already changed)
            if ($department->getCollege() === $this) {
                $department->setCollege(null);
            }
        }

        return $this;
    }
 

    /**
     * @return Collection|CallForProposal[]
     */
    public function getCallForProposals(): Collection
    {
        return $this->callForProposals;
    }

    public function addCallForProposal(CallForProposal $callForProposal): self
    {
        if (!$this->callForProposals->contains($callForProposal)) {
            $this->callForProposals[] = $callForProposal;
            $callForProposal->setCollege($this);
        }

        return $this;
    }

    public function removeCallForProposal(CallForProposal $callForProposal): self
    {
        if ($this->callForProposals->removeElement($callForProposal)) {
            // set the owning side to null (unless already changed)
            if ($callForProposal->getCollege() === $this) {
                $callForProposal->setCollege(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|CallForProposal[]
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(\App\Entity\IRB\Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setCollege($this);
        }

        return $this;
    }

    public function removeApplication(\App\Entity\IRB\Application $application): self
    {
        if ($this->application->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getCollege() === $this) {
                $application->setCollege(null);
            }
        }

        return $this;
    }

     
    public function getPrincipalContact(): ?string
    {
        return $this->principal_contact;
    }

    public function setPrincipalContact(?string $principal_contact): self
    {
        $this->principal_contact = $principal_contact;

        return $this;
    }

    public function getIdentificationCode(): ?string
    {
        return $this->identification_code;
    }

    public function setIdentificationCode(?string $identification_code): self
    {
        $this->identification_code = $identification_code;

        return $this;
    }

    public function getMission(): ?string
    {
        return $this->mission;
    }

    public function setMission(?string $mission): self
    {
        $this->mission = $mission;

        return $this;
    }

    public function getObjective(): ?string
    {
        return $this->objective;
    }

    public function setObjective(?string $objective): self
    {
        $this->objective = $objective;

        return $this;
    }

    /**
     * @return Collection|ThematicArea[]
     */
    public function getThematicAreas(): Collection
    {
        return $this->thematicAreas;
    }

    public function addThematicArea(ThematicArea $thematicArea): self
    {
        if (!$this->thematicAreas->contains($thematicArea)) {
            $this->thematicAreas[] = $thematicArea;
            $thematicArea->setCollege($this);
        }

        return $this;
    }

    public function removeThematicArea(ThematicArea $thematicArea): self
    {
        if ($this->thematicAreas->removeElement($thematicArea)) {
            // set the owning side to null (unless already changed)
            if ($thematicArea->getCollege() === $this) {
                $thematicArea->setCollege(null);
            }
        }

        return $this;
    }
    /**
     * @return Collection|Submission[]
     */
    public function getSubmissions(): Collection
    {
        return $this->submissions;
    }
    // /**
    //  * @return Collection|Submission[]
    //  */
    // public function getSubmissions(): Collection
    // {
    //     return $this->thematicAreas;
    // }
    /**
     * @return Collection|Submission[]
     */
    public function getCollegeSubmissions(): Collection
    {
        return $this->submissions;
    }

    public function addSubmission(Submission $submission): self
    {
        if (!$this->submissions->contains($submission)) {
            $this->submissions[] = $submission;
            $submission->setCollege($this);
        }

        return $this;
    }

    public function removeSubmission(Submission $submission): self
    {
        if ($this->submissions->removeElement($submission)) {
            // set the owning side to null (unless already changed)
            if ($submission->getCollege() === $this) {
                $submission->setCollege(null);
            }
        }

        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): self
    {
        $this->prefix = $prefix;

        return $this;
    }

 

    public function getGuidelineForReviewer(): ?GuidelineForReviewer
    {
        return $this->guidelineForReviewer;
    }

    public function setGuidelineForReviewer(GuidelineForReviewer $guidelineForReviewer): self
    {
        // set the owning side of the relation if necessary
        if ($guidelineForReviewer->getCollege() !== $this) {
            $guidelineForReviewer->setCollege($this);
        }

        $this->guidelineForReviewer = $guidelineForReviewer;

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

    /**
     * @return Collection|Guidelines[]
     */
    public function getGuidelines(): Collection
    {
        return $this->guidelines;
    }

    public function addGuideline(Guidelines $guideline): self
    {
        if (!$this->guidelines->contains($guideline)) {
            $this->guidelines[] = $guideline;
            $guideline->setCollege($this);
        }

        return $this;
    }

    public function removeGuideline(Guidelines $guideline): self
    {
        if ($this->guidelines->removeElement($guideline)) {
            // set the owning side to null (unless already changed)
            if ($guideline->getCollege() === $this) {
                $guideline->setCollege(null);
            }
        }

        return $this;
    }
 
    /**
     * @return Collection|CallForTraining[]
     */
    public function getCallForTrainings(): Collection
    {
        return $this->callForTrainings;
    }

    public function addCallForTraining(CallForTraining $callForTraining): self
    {
        if (!$this->callForTrainings->contains($callForTraining)) {
            $this->callForTrainings[] = $callForTraining;
            $callForTraining->setCollege($this);
        }

        return $this;
    }

    public function removeCallForTraining(CallForTraining $callForTraining): self
    {
        if ($this->callForTrainings->removeElement($callForTraining)) {
            // set the owning side to null (unless already changed)
            if ($callForTraining->getCollege() === $this) {
                $callForTraining->setCollege(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|BoardMember[]
     */
    public function getBoardMembers(): Collection
    {
        return $this->boardMembers;
    }

    public function addBoardMember(BoardMember $boardMember): self
    {
        if (!$this->boardMembers->contains($boardMember)) {
            $this->boardMembers[] = $boardMember;
            $boardMember->setCollege($this);
        }

        return $this;
    }

    public function removeBoardMember(BoardMember $boardMember): self
    {
        if ($this->boardMembers->removeElement($boardMember)) {
            // set the owning side to null (unless already changed)
            if ($boardMember->getCollege() === $this) {
                $boardMember->setCollege(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|IrbReviewAtachement[]
     */
    public function getIrbReviewAtachements(): Collection
    {
        return $this->irbReviewAtachements;
    }

    public function addIrbReviewAtachement(\App\Entity\IRB\IrbReviewAtachement $irbReviewAtachement): self
    {
        if (!$this->irbReviewAtachements->contains($irbReviewAtachement)) {
            $this->irbReviewAtachements[] = $irbReviewAtachement;
            $irbReviewAtachement->setCollege($this);
        }

        return $this;
    }

    public function removeIrbReviewAtachement(\App\Entity\IRB\IrbReviewAtachement  $irbReviewAtachement): self
    {
        if ($this->irbReviewAtachements->removeElement($irbReviewAtachement)) {
            // set the owning side to null (unless already changed)
            if ($irbReviewAtachement->getCollege() === $this) {
                $irbReviewAtachement->setCollege(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CallCategory>
     */
    public function getCallCategories(): Collection
    {
        return $this->callCategories;
    }

    public function addCallCategory(CallCategory $callCategory): self
    {
        if (!$this->callCategories->contains($callCategory)) {
            $this->callCategories[] = $callCategory;
            $callCategory->setCollege($this);
        }

        return $this;
    }

    public function removeCallCategory(CallCategory $callCategory): self
    {
        if ($this->callCategories->removeElement($callCategory)) {
            // set the owning side to null (unless already changed)
            if ($callCategory->getCollege() === $this) {
                $callCategory->setCollege(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CollegeThematicArea>
     */
    public function getCollegeThematicAreas(): Collection
    {
        return $this->collegeThematicAreas;
    }

    public function addCollegeThematicArea(CollegeThematicArea $collegeThematicArea): self
    {
        if (!$this->collegeThematicAreas->contains($collegeThematicArea)) {
            $this->collegeThematicAreas[] = $collegeThematicArea;
            $collegeThematicArea->setCollege($this);
        }

        return $this;
    }

    public function removeCollegeThematicArea(CollegeThematicArea $collegeThematicArea): self
    {
        if ($this->collegeThematicAreas->removeElement($collegeThematicArea)) {
            // set the owning side to null (unless already changed)
            if ($collegeThematicArea->getCollege() === $this) {
                $collegeThematicArea->setCollege(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, CollaborativeResearchProject>
     */
    public function getCollaborativeResearchProjects(): Collection
    {
        return $this->collaborativeResearchProjects;
    }

    public function addCollaborativeResearchProject(\App\Entity\CRP\CollaborativeResearchProject $collaborativeResearchProject): self
    {
        if (!$this->collaborativeResearchProjects->contains($collaborativeResearchProject)) {
            $this->collaborativeResearchProjects[] = $collaborativeResearchProject;
            $collaborativeResearchProject->setResponsiblePrimaryInstitute($this);
        }

        return $this;
    }

    public function removeCollaborativeResearchProject(\App\Entity\CRP\CollaborativeResearchProject $collaborativeResearchProject): self
    {
        if ($this->collaborativeResearchProjects->removeElement($collaborativeResearchProject)) {
            // set the owning side to null (unless already changed)
            if ($collaborativeResearchProject->getResponsiblePrimaryInstitute() === $this) {
                $collaborativeResearchProject->setResponsiblePrimaryInstitute(null);
            }
        }

        return $this;
    }

       

  
}
