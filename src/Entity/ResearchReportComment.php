<?php

namespace App\Entity;

use App\Repository\ResearchReportCommentRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResearchReportCommentRepository::class)
 */
class ResearchReportComment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ResearchReport::class, inversedBy="researchReportComments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $report;

    /**
     * @ORM\Column(type="boolean")
     */
    private $wasAgreed;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $rejectionReason;

    /**
     * @ORM\Column(type="datetime")
     */
    private $commentedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $commentedBy;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $PIResponse;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $PIRespondedAt;

    public function __construct() {
        $this->commentedAt = new \DateTime();
    }
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReport(): ?ResearchReport
    {
        return $this->report;
    }

    public function setReport(?ResearchReport $report): self
    {
        $this->report = $report;

        return $this;
    }

    public function getWasAgreed(): ?bool
    {
        return $this->wasAgreed;
    }

    public function setWasAgreed(bool $wasAgreed): self
    {
        $this->wasAgreed = $wasAgreed;

        return $this;
    }

    public function getRejectionReason(): ?string
    {
        return $this->rejectionReason;
    }

    public function setRejectionReason(?string $rejectionReason): self
    {
        $this->rejectionReason = $rejectionReason;

        return $this;
    }

    public function getCommentedAt(): ?\DateTimeInterface
    {
        return $this->commentedAt;
    }

    public function setCommentedAt(\DateTimeInterface $commentedAt): self
    {
        $this->commentedAt = $commentedAt;

        return $this;
    }

    public function getCommentedBy(): ?User
    {
        return $this->commentedBy;
    }

    public function setCommentedBy(?User $commentedBy): self
    {
        $this->commentedBy = $commentedBy;

        return $this;
    }

    public function getPIResponse(): ?string
    {
        return $this->PIResponse;
    }

    public function setPIResponse(?string $PIResponse): self
    {
        $this->PIResponse = $PIResponse;

        return $this;
    }

    public function getPIRespondedAt(): ?\DateTimeInterface
    {
        return $this->PIRespondedAt;
    }

    public function setPIRespondedAt(?\DateTimeInterface $PIRespondedAt): self
    {
        $this->PIRespondedAt = $PIRespondedAt;

        return $this;
    }
}
