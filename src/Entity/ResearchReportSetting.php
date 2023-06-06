<?php

namespace App\Entity;

use App\Repository\ResearchReportSettingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResearchReportSettingRepository::class)
 */
class ResearchReportSetting
{
    const CREATED=0;
    const APPROVED=1;
    const ALLOWED_TO_EDIT=2;
    const UNEDITABLE=3;
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=Submission::class, inversedBy="researchReportSetting", cascade={"persist", "remove"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $submission;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isAltered;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    private $isApproved;

    /**
     * @ORM\OneToMany(targetEntity=ResearchReportSubmissionSetting::class, mappedBy="setting", orphanRemoval=true)
     */
    private $submissionSettings;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function __construct()
    {
        $this->submissionSettings = new ArrayCollection();
        $this->status = self::CREATED;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getSubmission(): ?Submission
    {
        return $this->submission;
    }

    public function setSubmission(Submission $submission): self
    {
        $this->submission = $submission;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

    public function getIsAltered(): ?bool
    {
        return $this->isAltered;
    }

    public function setIsAltered(bool $isAltered): self
    {
        $this->isAltered = $isAltered;

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

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getIsApproved(): ?bool
    {
        return $this->isApproved;
    }

    public function setIsApproved(bool $isApproved): self
    {
        $this->isApproved = $isApproved;

        return $this;
    }

    /**
     * @return Collection<int, ResearchReportSubmissionSetting>
     */
    public function getSubmissionSettings(): Collection
    {
        return $this->submissionSettings;
    }

    public function addSubmissionSetting(ResearchReportSubmissionSetting $submissionSetting): self
    {
        if (!$this->submissionSettings->contains($submissionSetting)) {
            $this->submissionSettings[] = $submissionSetting;
            $submissionSetting->setSetting($this);
        }

        return $this;
    }

    public function removeSubmissionSetting(ResearchReportSubmissionSetting $submissionSetting): self
    {
        if ($this->submissionSettings->removeElement($submissionSetting)) {
            // set the owning side to null (unless already changed)
            if ($submissionSetting->getSetting() === $this) {
                $submissionSetting->setSetting(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
