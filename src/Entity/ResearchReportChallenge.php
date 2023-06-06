<?php

namespace App\Entity;

use App\Repository\ResearchReportChallengeRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ResearchReportChallengeRepository::class)
 */
class ResearchReportChallenge
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=ResearchReport::class, inversedBy="researchReportChallenges")
     * @ORM\JoinColumn(nullable=false)
     */
    private $report;

    /**
     * @ORM\ManyToOne(targetEntity=ResearchReportChallengesCategory::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $challengeCategory;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString()
    {
        
 return $this->description;
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

    public function getChallengeCategory(): ?ResearchReportChallengesCategory
    {
        return $this->challengeCategory;
    }

    public function setChallengeCategory(?ResearchReportChallengesCategory $challengeCategory): self
    {
        $this->challengeCategory = $challengeCategory;

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
}
