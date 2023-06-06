<?php

namespace App\Entity\IRB;

use App\Entity\IRB\ApplicationFeedback;
use App\Entity\IRB\Amendment;
use App\Entity\IrbCertificate;
use App\Entity\User;
use App\Entity\IRB\CoAuthor as CoAuthor;
use App\Repository\IRB\ApplicationRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Table(name="irb_application")
 * @ORM\Entity(repositoryClass=ApplicationRepository::class)
 * @Vich\Uploadable
 */
class Application
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
    private $title;

    /**
     * @ORM\Column(type="date")
     */
    private $startDate;

    /**
     * @ORM\Column(type="date")
     */
    private $endDate;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $location;

    /**
     * @ORM\Column(type="text")
     */
    private $description;

    /**
     * @ORM\ManyToOne(targetEntity=ProjectType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $projectType;

    /**
     * @ORM\OneToMany(targetEntity=ApplicationResearchSubject::class, mappedBy="application",cascade={"persist"})
     */
    private $applicationResearchSubjects;

    /**
     * @ORM\OneToMany(targetEntity=ApplicationMitigationStrategy::class, mappedBy="application",cascade={"persist"})
     */
    private $applicationMitigationStrategies;

    /**
     * @ORM\OneToMany(targetEntity=ApplicationReview::class, mappedBy="application",cascade={"persist"})
     */
    private $applicationReviews;

    //  /**
    //  * @ORM\OneToMany(targetEntity=Review::class, mappedBy="submission" , orphanRemoval=true,cascade={"persist"})
    //  */
    // private $irbreviews;


    /**
     * @ORM\OneToMany(targetEntity=ApplicationAttachment::class, mappedBy="application",cascade={"persist"})
     */
    private $applicationAttachments;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $additionalAttachmet;

    /**
     * 
     * @Vich\UploadableField(mapping="application_file", fileNameProperty="additionalAttachmet")
     * 
     * @var File|null
     */
    private $uploadFile;

    /**
     * @ORM\OneToMany(targetEntity=CoAuthor::class, mappedBy="application",cascade={"persist"})
     */
    private $members;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="applications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $submittedBy;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="projects")
     */
    private $pi;


    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime_immutable", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=Amendment::class, mappedBy="application", orphanRemoval=true)
     */
    private $amendments;

    /**
     * @ORM\ManyToOne(targetEntity=IRBStatus::class)
     */
    private $status;




    /**
     * @ORM\OneToMany(targetEntity=IRBReviewAssignment::class, mappedBy="irbreviewer" , orphanRemoval=true,cascade={"persist"})
     */
    private $iRBReviewAssignments;

    /**
     * @ORM\ManyToOne(targetEntity=ApplicationType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $applicationType;

    /**
     * @ORM\OneToMany(targetEntity=RenewalRequest::class, mappedBy="application")
     */
    private $renewalRequests;

    /**
     * @ORM\OneToMany(targetEntity=Revision::class, mappedBy="application", orphanRemoval=true)
     */
    private $revisions;

   
    /**
     * @ORM\OneToMany(targetEntity=IrbCertificate::class, mappedBy="irbApplication", orphanRemoval=true)
     */
    private $irbCertificates;

    


     /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\College::class, inversedBy="applications")
     */
    private $college;

    /**
     * @ORM\ManyToOne(targetEntity=Meeting::class, inversedBy="applications")
     */
    private $meeting;

    // /**
    //  * @ORM\OneToMany(targetEntity=ApplicationFeedback::class, mappedBy="application", orphanRemoval=true)
    //  * @ORM\JoinColumn(nullable=true)
    //    */
    // private $applicationFeedback;

    /**
     * @ORM\OneToMany(targetEntity=ApplicationFeedback::class, mappedBy="application")
     */
    private $applicationFeedbacks;

   
    
   


    public function __construct()
    {
        $this->createdAt = new DateTime();
        $this->applicationResearchSubjects = new ArrayCollection();
        $this->applicationMitigationStrategies = new ArrayCollection();
        $this->applicationReviews = new ArrayCollection();
        $this->applicationAttachments = new ArrayCollection();
        $this->members = new ArrayCollection();
        $this->amendments = new ArrayCollection();
        $this->IRBreviewAssignments = new ArrayCollection();
        $this->renewalRequests = new ArrayCollection();
        $this->revisions = new ArrayCollection();
        $this->iRBReviewAssignments = new ArrayCollection();
        $this->irbCertificates = new ArrayCollection();
        // $this->applicationFeedback = new ArrayCollection();
        $this->applicationFeedbacks = new ArrayCollection();
     }

    public function setUploadFile(?File $imageFile = null): void
    {
        $this->uploadFile = $imageFile;

        // if (null !== $imageFile) {
        //     // It is required that at least one field changes if you are using doctrine
        //     // otherwise the event listeners won't be called and the file is lost
        //     $this->updatedAt = new \DateTimeImmutable();
        // }
    }

    public function __toString()
    {
   
    return $this->title;
     
    }
    public function getHasToRenew()
    {
   
        return (new \DateTime('now')) > $this->createdAt->modify('+1 year') ;
    }

    public function getCollege(): ?\App\Entity\College
    {
        return $this->college;
    }

    public function setCollege(?\App\Entity\College $college): self
    {
        $this->college = $college;

        return $this;
    }

    /**
     * @return Collection|iRBReviewAssignments[]
     */
    public function getIRBReviewAssignments(): Collection
    {
        return $this->iRBReviewAssignments;
    }

    public function addIRBReviewAssignment(\App\Entity\IRB\IRBReviewAssignment $iRBReviewAssignments): self
    {
        if (!$this->iRBReviewAssignments->contains($iRBReviewAssignments)) {
            $this->iRBReviewAssignments[] = $iRBReviewAssignments;
            $iRBReviewAssignments->setApplication($this);
        }

        return $this;
    }

    public function removeIRBReviewAssignment(\App\Entity\IRB\IRBReviewAssignment $iRBReviewAssignments): self
    {
        if ($this->iRBReviewAssignments->removeElement($iRBReviewAssignments)) {
            // set the owning side to null (unless already changed)
            if ($iRBReviewAssignments->getApplication() === $this) {
                $iRBReviewAssignments->setApplication(null);
            }
        }

        return $this;
    }

    public function getUploadFile(): ?File
    {
        return $this->uploadFile;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getStartDate(): ?\DateTimeInterface
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTimeInterface $startDate): self
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTimeInterface
    {
        return $this->endDate;
    }

    public function setEndDate(\DateTimeInterface $endDate): self
    {
        $this->endDate = $endDate;

        return $this;
    }

    public function getLocation(): ?string
    {
        return $this->location;
    }

    public function setLocation(string $location): self
    {
        $this->location = $location;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getProjectType(): ?ProjectType
    {
        return $this->projectType;
    }

    public function setProjectType(?ProjectType $projectType): self
    {
        $this->projectType = $projectType;

        return $this;
    }

    /**
     * @return Collection|ApplicationResearchSubject[]
     */
    public function getApplicationResearchSubjects(): Collection
    {
        return $this->applicationResearchSubjects;
    }

    public function addApplicationResearchSubject(ApplicationResearchSubject $applicationResearchSubject): self
    {
        if (!$this->applicationResearchSubjects->contains($applicationResearchSubject)) {
            $this->applicationResearchSubjects[] = $applicationResearchSubject;
            $applicationResearchSubject->setApplication($this);
        }

        return $this;
    }

    public function removeApplicationResearchSubject(ApplicationResearchSubject $applicationResearchSubject): self
    {
        if ($this->applicationResearchSubjects->removeElement($applicationResearchSubject)) {
            // set the owning side to null (unless already changed)
            if ($applicationResearchSubject->getApplication() === $this) {
                $applicationResearchSubject->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ApplicationMitigationStrategy[]
     */
    public function getApplicationMitigationStrategies(): Collection
    {
        return $this->applicationMitigationStrategies;
    }

    public function addApplicationMitigationStrategy(ApplicationMitigationStrategy $applicationMitigationStrategy): self
    {
        if (!$this->applicationMitigationStrategies->contains($applicationMitigationStrategy)) {
            $this->applicationMitigationStrategies[] = $applicationMitigationStrategy;
            $applicationMitigationStrategy->setApplication($this);
        }

        return $this;
    }

    public function removeApplicationMitigationStrategy(ApplicationMitigationStrategy $applicationMitigationStrategy): self
    {
        if ($this->applicationMitigationStrategies->removeElement($applicationMitigationStrategy)) {
            // set the owning side to null (unless already changed)
            if ($applicationMitigationStrategy->getApplication() === $this) {
                $applicationMitigationStrategy->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ApplicationReview[]
     */
    public function getApplicationReviews(): Collection
    {
        return $this->applicationReviews;
    }

    public function addApplicationReview(ApplicationReview $applicationReview): self
    {
        if (!$this->applicationReviews->contains($applicationReview)) {
            $this->applicationReviews[] = $applicationReview;
            $applicationReview->setApplication($this);
        }

        return $this;
    }

    public function removeApplicationReview(ApplicationReview $applicationReview): self
    {
        if ($this->applicationReviews->removeElement($applicationReview)) {
            // set the owning side to null (unless already changed)
            if ($applicationReview->getApplication() === $this) {
                $applicationReview->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ApplicationAttachment[]
     */
    public function getApplicationAttachments(): Collection
    {
        return $this->applicationAttachments;
    }

    public function addApplicationAttachment(ApplicationAttachment $applicationAttachment): self
    {
        if (!$this->applicationAttachments->contains($applicationAttachment)) {
            $this->applicationAttachments[] = $applicationAttachment;
            $applicationAttachment->setApplication($this);
        }

        return $this;
    }

    public function removeApplicationAttachment(ApplicationAttachment $applicationAttachment): self
    {
        if ($this->applicationAttachments->removeElement($applicationAttachment)) {
            // set the owning side to null (unless already changed)
            if ($applicationAttachment->getApplication() === $this) {
                $applicationAttachment->setApplication(null);
            }
        }

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(int $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getAdditionalAttachmet(): ?string
    {
        return $this->additionalAttachmet;
    }

    public function setAdditionalAttachmet(?string $additionalAttachmet): self
    {
        $this->additionalAttachmet = $additionalAttachmet;

        return $this;
    }

    /**
     * @return Collection|CoAuthor[]
     */
    public function getmembers(): Collection
    {
        return $this->members;
    }

    public function addMemeber(CoAuthor $memeber): self
    {
        if (!$this->members->contains($memeber)) {
            $this->members[] = $memeber;
            $memeber->setApplication($this);
        }

        return $this;
    }

    public function removeMemeber(CoAuthor $memeber): self
    {
        if ($this->members->removeElement($memeber)) {
            // set the owning side to null (unless already changed)
            if ($memeber->getApplication() === $this) {
                $memeber->setApplication(null);
            }
        }

        return $this;
    }

    public function getSubmittedBy(): ?User
    {
        return $this->submittedBy;
    }

    public function setSubmittedBy(?User $submittedBy): self
    {
        $this->submittedBy = $submittedBy;

        return $this;
    }

    public function getPi(): ?User
    {
        return $this->pi;
    }

    public function setPi(?User $pi): self
    {
        $this->pi = $pi;
        return $this;
    }


    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function addMember(CoAuthor $member): self
    {
        if (!$this->members->contains($member)) {
            $this->members[] = $member;
            $member->setApplication($this);
        }

        return $this;
    }

    public function removeMember(CoAuthor $member): self
    {
        if ($this->members->removeElement($member)) {
            // set the owning side to null (unless already changed)
            if ($member->getApplication() === $this) {
                $member->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Amendment[]
     */
    public function getAmendments(): Collection
    {
        return $this->amendments;
    }

    public function addAmendment(Amendment $amendment): self
    {
        if (!$this->amendments->contains($amendment)) {
            $this->amendments[] = $amendment;
            $amendment->setApplication($this);
        }

        return $this;
    }

    public function removeAmendment(Amendment $amendment): self
    {
        if ($this->amendments->removeElement($amendment)) {
            // set the owning side to null (unless already changed)
            if ($amendment->getApplication() === $this) {
                $amendment->setApplication(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?IRBStatus
    {
        return $this->status;
    }

    public function setStatus(?IRBStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getApplicationType(): ?ApplicationType
    {
        return $this->applicationType;
    }

    public function setApplicationType(?ApplicationType $applicationType): self
    {
        $this->applicationType = $applicationType;

        return $this;
    }

    /**
     * @return Collection|RenewalRequest[]
     */
    public function getRenewalRequests(): Collection
    {
        return $this->renewalRequests;
    }

    public function addRenewalRequest(RenewalRequest $renewalRequest): self
    {
        if (!$this->renewalRequests->contains($renewalRequest)) {
            $this->renewalRequests[] = $renewalRequest;
            $renewalRequest->setApplication($this);
        }

        return $this;
    }

    public function removeRenewalRequest(RenewalRequest $renewalRequest): self
    {
        if ($this->renewalRequests->removeElement($renewalRequest)) {
            // set the owning side to null (unless already changed)
            if ($renewalRequest->getApplication() === $this) {
                $renewalRequest->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|Revision[]
     */
    public function getRevisions(): Collection
    {
        return $this->revisions;
    }

    public function addRevision(Revision $revision): self
    {
        if (!$this->revisions->contains($revision)) {
            $this->revisions[] = $revision;
            $revision->setApplication($this);
        }

        return $this;
    }

    public function removeRevision(Revision $revision): self
    {
        if ($this->revisions->removeElement($revision)) {
            // set the owning side to null (unless already changed)
            if ($revision->getApplication() === $this) {
                $revision->setApplication(null);
            }
        }

        return $this;
    }

     
 

    /**
     * @return Collection|IrbCertificate[]
     */
    public function getIrbCertificates(): Collection
    {
        return $this->irbCertificates;
    }

    public function addIrbCertificate(IrbCertificate $irbCertificate): self
    {
        if (!$this->irbCertificates->contains($irbCertificate)) {
            $this->irbCertificates[] = $irbCertificate;
            $irbCertificate->setIrbApplication($this);
        }

        return $this;
    }

    public function removeIrbCertificate(IrbCertificate $irbCertificate): self
    {
        if ($this->irbCertificates->removeElement($irbCertificate)) {
            // set the owning side to null (unless already changed)
            if ($irbCertificate->getIrbApplication() === $this) {
                $irbCertificate->setIrbApplication(null);
            }
        }

        return $this;
    }

    public function getMeeting(): ?Meeting
    {
        return $this->meeting;
    }

    public function setMeeting(?Meeting $meeting): self
    {
        $this->meeting = $meeting;

        return $this;
    }

    /**
     * @return Collection<int, ApplicationFeedback>
     */
    public function getApplicationFeedback(): Collection
    {
        return $this->applicationFeedback;
    }

    public function addApplicationFeedback(ApplicationFeedback $applicationFeedback): self
    {
        if (!$this->applicationFeedback->contains($applicationFeedback)) {
            $this->applicationFeedback[] = $applicationFeedback;
            $applicationFeedback->setApplication($this);
        }

        return $this;
    }

    public function removeApplicationFeedback(ApplicationFeedback $applicationFeedback): self
    {
        if ($this->applicationFeedback->removeElement($applicationFeedback)) {
            // set the owning side to null (unless already changed)
            if ($applicationFeedback->getApplication() === $this) {
                $applicationFeedback->setApplication(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, ApplicationFeedback>
     */
    public function getApplicationFeedbacks(): Collection
    {
        return $this->applicationFeedbacks;
    }

     
 
   
}
