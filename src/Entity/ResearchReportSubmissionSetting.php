<?php

namespace App\Entity;

use App\Repository\ResearchReportSubmissionSettingRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResearchReportSubmissionSettingRepository::class)
 */
class ResearchReportSubmissionSetting
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Submission::class, inversedBy="researchReportSubmissionSettings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $submission;

    /**
     * @ORM\Column(type="integer")
     */
    private $phase;

    /**
     * @ORM\Column(type="date")
     */
    private $submissionDate;

    /**
     * @ORM\Column(type="boolean" , nullable=true)
     */
    private $isSubmitted;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $wasAllCOPIComfirmed;

    /**
     * @ORM\ManyToOne(targetEntity=ResearchReportSetting::class, inversedBy="submissionSettings")
     * @ORM\JoinColumn(nullable=false)
     */
    private $setting;

   

    public function getId(): ?int
    {
        return $this->id;
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

    public function getPhase(): ?int
    {
        return $this->phase;
    }

    public function setPhase(int $phase): self
    {
        $this->phase = $phase;

        return $this;
    }

    public function getSubmissionDate(): ?\DateTimeInterface
    {
        return $this->submissionDate;
    }

    public function setSubmissionDate(\DateTimeInterface $submissionDate): self
    {
        $this->submissionDate = $submissionDate;

        return $this;
    }

    public function getIsSubmitted(): ?bool
    {
        return $this->isSubmitted;
    }

    public function setIsSubmitted(bool $isSubmitted): self
    {
        $this->isSubmitted = $isSubmitted;

        return $this;
    }

    public function getWasAllCOPIComfirmed(): ?bool
    {
        return $this->wasAllCOPIComfirmed;
    }

    public function setWasAllCOPIComfirmed(?bool $wasAllCOPIComfirmed): self
    {
        $this->wasAllCOPIComfirmed = $wasAllCOPIComfirmed;

        return $this;
    }

    public function getSetting(): ?ResearchReportSetting
    {
        return $this->setting;
    }

    public function setSetting(?ResearchReportSetting $setting): self
    {
        $this->setting = $setting;

        return $this;
    }

   

   
}
