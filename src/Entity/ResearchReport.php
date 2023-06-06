<?php

namespace App\Entity;

use App\Repository\ResearchReportRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResearchReportRepository::class)
 */
class ResearchReport
{

    const STATUS_CREATED=0;
    const STATUS_REJECTED=1;
    const STATUS_AGREED=2;
    const STATUS_NOT_AGREED=3;
    const STATUS_APPROVED=4;


    const TYPE_ORIGINAL=1;
    const TYPE_AMMENDED=2;

    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Submission::class, inversedBy="researchReports")
     * @ORM\JoinColumn(nullable=false)
     */
    private $submission;
 

    /**
     * @ORM\Column(type="text")
     */
    private $file;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $fileType;

    /**
     * @ORM\Column(type="datetime")
     */
    private $submittedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $submissionStatus;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $submittedBy;


    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\OneToMany(targetEntity=ResearchReportReview::class, mappedBy="researchReport", orphanRemoval=true)
     */
    private $researchReportReviews;

    

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $financialClearance;

    /**
     * @ORM\OneToMany(targetEntity=ResearchReportComment::class, mappedBy="report", orphanRemoval=true)
     */
    private $researchReportComments;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $approvedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $approvedAt;

    /**
     * @ORM\Column(type="integer")
     */
    private $type;

    /**
     * @ORM\ManyToOne(targetEntity=ResearchReport::class, inversedBy="researchReports")
     */
    private $parentReport;

    /**
     * @ORM\OneToMany(targetEntity=ResearchReport::class, mappedBy="parentReport")
     */
    private $researchReports;

    /**
     * @ORM\OneToMany(targetEntity=ResearchReportChallenge::class, mappedBy="report", orphanRemoval=true,cascade={"persist", "remove" })
     */
    private $researchReportChallenges;

   
    public function __construct()
    {
        $this->submissionStatus=self::STATUS_CREATED;
        $this->type=self::TYPE_ORIGINAL;
        $this->submittedAt=new \DateTime('now');
        $this->researchReportReviews = new ArrayCollection();
        $this->researchReportComments = new ArrayCollection();
        $this->researchReports = new ArrayCollection();
        $this->researchReportChallenges = new ArrayCollection();
    }

    public function approveResearchReport(){
        
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
   
        return $this->submission;
    }

    public function getSubmission(): ?Submission
    {
        return $this->submission;
    }

    public function setSubmission(?Submission $submission): self
    {
        $this->submission = $submission;

        return $this;
    }

    

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
    }

    public function getFileType(): ?string
    {
        return $this->fileType;
    }

    public function setFileType(string $fileType): self
    {
        $this->fileType = $fileType;

        return $this;
    }

    public function getSubmittedAt(): ?\DateTimeInterface
    {
        return $this->submittedAt;
    }

    public function setSubmittedAt(\DateTimeInterface $submittedAt): self
    {
        $this->submittedAt = $submittedAt;

        return $this;
    }

    public function getSubmissionStatus(): ?int
    {
        return $this->submissionStatus;
    }

    public function setSubmissionStatus(int $submissionStatus): self
    {
        $this->submissionStatus = $submissionStatus;

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
    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    /**
     * @return Collection|ResearchReportReview[]
     */
    public function getResearchReportReviews(): Collection
    {
        return $this->researchReportReviews;
    }

    public function addResearchReportReview(ResearchReportReview $researchReportReview): self
    {
        if (!$this->researchReportReviews->contains($researchReportReview)) {
            $this->researchReportReviews[] = $researchReportReview;
            $researchReportReview->setResearchReport($this);
        }

        return $this;
    }

    public function removeResearchReportReview(ResearchReportReview $researchReportReview): self
    {
        if ($this->researchReportReviews->removeElement($researchReportReview)) {
            // set the owning side to null (unless already changed)
            if ($researchReportReview->getResearchReport() === $this) {
                $researchReportReview->setResearchReport(null);
            }
        }

        return $this;
    }

    

    public function getFinancialClearance(): ?string
    {
        return $this->financialClearance;
    }

    public function setFinancialClearance(?string $financialClearance): self
    {
        $this->financialClearance = $financialClearance;

        return $this;
    }

    /**
     * @return Collection|ResearchReportComment[]
     */
    public function getResearchReportComments(): Collection
    {
        return $this->researchReportComments;
    }

    public function addResearchReportComment(ResearchReportComment $researchReportComment): self
    {
        if (!$this->researchReportComments->contains($researchReportComment)) {
            $this->researchReportComments[] = $researchReportComment;
            $researchReportComment->setReport($this);
        }

        return $this;
    }

    public function removeResearchReportComment(ResearchReportComment $researchReportComment): self
    {
        if ($this->researchReportComments->removeElement($researchReportComment)) {
            // set the owning side to null (unless already changed)
            if ($researchReportComment->getReport() === $this) {
                $researchReportComment->setReport(null);
            }
        }

        return $this;
    }

    public function getApprovedBy(): ?User
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(?User $approvedBy): self
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }

    public function getApprovedAt(): ?\DateTimeInterface
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(?\DateTimeInterface $approvedAt): self
    {
        $this->approvedAt = $approvedAt;

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

    public function getParentReport(): ?self
    {
        return $this->parentReport;
    }

    public function setParentReport(?self $parentReport): self
    {
        $this->parentReport = $parentReport;

        return $this;
    }

    /**
     * @return Collection|self[]
     */
    public function getResearchReports(): Collection
    {
        return $this->researchReports;
    }

    public function addResearchReport(self $researchReport): self
    {
        if (!$this->researchReports->contains($researchReport)) {
            $this->researchReports[] = $researchReport;
            $researchReport->setParentReport($this);
        }

        return $this;
    }

    public function removeResearchReport(self $researchReport): self
    {
        if ($this->researchReports->removeElement($researchReport)) {
            // set the owning side to null (unless already changed)
            if ($researchReport->getParentReport() === $this) {
                $researchReport->setParentReport(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|ResearchReportChallenge[]
     */
    public function getResearchReportChallenges(): Collection
    {
        return $this->researchReportChallenges;
    }

    public function addResearchReportChallenge(ResearchReportChallenge $researchReportChallenge): self
    {
        if (!$this->researchReportChallenges->contains($researchReportChallenge)) {
            $this->researchReportChallenges[] = $researchReportChallenge;
            $researchReportChallenge->setReport($this);
        }

        return $this;
    }

    public function removeResearchReportChallenge(ResearchReportChallenge $researchReportChallenge): self
    {
        if ($this->researchReportChallenges->removeElement($researchReportChallenge)) {
            // set the owning side to null (unless already changed)
            if ($researchReportChallenge->getReport() === $this) {
                $researchReportChallenge->setReport(null);
            }
        }

        return $this;
    }

    
}
