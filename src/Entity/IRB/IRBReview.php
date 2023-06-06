<?php

namespace App\Entity\IRB;

use App\Repository\IRB\IRBReviewRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IRBReviewRepository::class)
 */
class IRBReview
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
    private $attachment;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $evaluation_attachment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $comment;

    /**
     * @ORM\ManyToOne(targetEntity=IRBReviewAssignment::class, inversedBy="irbreviews"  )
     * @ORM\JoinColumn(nullable=true)
     */
    private $iRBReviewAssignment;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;


    /**
     * @ORM\Column(type="datetime" , nullable=true)
     */
    private $allowed_at;


    /**
     * @ORM\Column(type="string", length=255 , nullable=true)
     */
    private $remark;


    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\User::class, inversedBy="irbreviews")
     */
    private $reviewed_by;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="irbreviews")
     * @ORM\JoinColumn(nullable=false)
     */
    private $application;


    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allow_to_view;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $from_director;


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRemarkText()
    {

        $statuses = [
            1 => "Declined",
            2 => "Accepted with condition",
            3 => "Accepted",
        ];
        return $statuses[$this->remark];
    }
    public function getRemarkColor()
    {

        $colors = [
            1 => "danger",
            2 => "warning",
            3 => "success",
        ];
        return $colors[$this->remark];
    }
   

    public function getReviewedBy()
    {
        return $this->reviewed_by;
    }

    public function setReviewedBy(?\App\Entity\User $reviewed_by): self
    {
        $this->reviewed_by = $reviewed_by;

        return $this;
    }
    public function getAttachment(): ?string
    {
        return $this->attachment;
    }

    public function setAttachment(?string $attachment): self
    {
        $this->attachment = $attachment;

        return $this;
    }

    public function getEvaluationAttachment(): ?string
    {
        return $this->evaluation_attachment;
    }

    public function setEvaluationAttachment(?string $evaluation_attachment): self
    {
        $this->evaluation_attachment = $evaluation_attachment;

        return $this;
    }




    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function setComment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getIRBReviewAssignment(): ?\App\Entity\IRB\IRBReviewAssignment
    {
        return $this->iRBReviewAssignment;
    }

    public function setIRBReviewAssignment(?\App\Entity\IRB\IRBReviewAssignment $iRBReviewAssignment): self
    {
        $this->iRBReviewAssignment = $iRBReviewAssignment;

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

    public function getAllowedAt(): ?\DateTimeInterface
    {
        return $this->allowed_at;
    }

    public function setAllowedAt(\DateTimeInterface $allowed_at): self
    {
        $this->allowed_at = $allowed_at;

        return $this;
    }



    public function getRemark(): ?string
    {
        return $this->remark;
    }

    public function setRemark(string $remark): self
    {
        $this->remark = $remark;

        return $this;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

        return $this;
    }

    public function getAllowToView(): ?bool
    {
        return $this->allow_to_view;
    }

    public function setAllowToView(?bool $allow_to_view): self
    {
        $this->allow_to_view = $allow_to_view;

        return $this;
    }

    public function getFromDirector(): ?bool
    {
        return $this->from_director;
    }

    public function setFromDirector(?bool $from_director): self
    {
        $this->from_director = $from_director;

        return $this;
    }
}
