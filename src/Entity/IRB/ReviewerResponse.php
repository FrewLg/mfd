<?php

namespace App\Entity\IRB;

use App\Repository\IRB\ReviewerResponseRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewerResponseRepository::class)
 */
class ReviewerResponse
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=IRBReviewAssignment::class, inversedBy="reviewerResponses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reviewAssignment;

    /**
     * @ORM\ManyToOne(targetEntity=ReviewChecklist::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $checklist;

    /**
     * @ORM\Column(type="string", length=255,nullable=true)
     */
    private $answer;

    public function __toString()
    {
       return $this->checklist;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getReviewAssignment(): ?IRBReviewAssignment
    {
        return $this->reviewAssignment;
    }

    public function setReviewAssignment(?IRBReviewAssignment $reviewAssignment): self
    {
        $this->reviewAssignment = $reviewAssignment;

        return $this;
    }

    public function getChecklist(): ?ReviewChecklist
    {
        return $this->checklist;
    }

    public function setChecklist(?ReviewChecklist $checklist): self
    {
        $this->checklist = $checklist;

        return $this;
    }

    public function getAnswer(): ?string
    {
        return $this->answer;
    }

    public function setAnswer(string $answer): self
    {
        $this->answer = $answer;

        return $this;
    }
}
