<?php

namespace App\Entity\IRB;

use App\Repository\IRB\IRBReviewAssignmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;  

/**
 * @ORM\Entity(repositoryClass=IRBReviewAssignmentRepository::class)
 */
class IRBReviewAssignment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="iRBReviewAssignments")
     */
    private $application;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\User::class, inversedBy="iRBReviewAssignments")
     */
    private $irbreviewer; 
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $duedate;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $invitation_sent_at;
    // ^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@\ju.edu.et

    /**
     * @ORM\Column(type="string", length=255, nullable=true)     
     */
    private $external_irbreviewer_email;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $external_irbreviewer_name;

     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $middle_name;


     /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $last_name;


    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $Declined;




    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $reassigned;



    


    /**
     * @ORM\Column(type="date" , nullable=true)
     */
    private $invitationDueDate;

    /**
     * @ORM\Column(type="integer" , nullable=true)
     */
    private $status;

    

   

    /**
     * @ORM\OneToMany(targetEntity=IRBReview::class, mappedBy="iRBReviewAssignment", orphanRemoval=true)
     */
    private $irbreviews;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $closed;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allowToView;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $inactive_assignment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $token;

    /**
     * @ORM\OneToMany(targetEntity=ReviewerResponse::class, mappedBy="reviewAssignment")
     */
    private $reviewerResponses;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $waiver;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $riskLevel;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $recommendation;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $howOftenStudyReviewed;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $reviewedAt;

    public $assigned_count;

    public function __construct()
    {
        $this->irbreviews = new ArrayCollection();
        $this->reviewerResponses = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function getAssignedCount(){

        $this->assigned_count= rand();
        return $this->assigned_count;
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

    public function getIrbreviewer() 
    {
        return $this->irbreviewer;
    }

    public function setIrbreviewer(?\App\Entity\User $irbreviewer): self
    {
        $this->irbreviewer = $irbreviewer;

        return $this;
    }

    public function getDuedate(): ?\DateTimeInterface
    {
        return $this->duedate;
    }

    public function setDuedate(?\DateTimeInterface $duedate): self
    {
        $this->duedate = $duedate;

        return $this;
    }

    public function getInvitationSentAt(): ?\DateTimeInterface
    {
        return $this->invitation_sent_at;
    }

    public function setInvitationSentAt(?\DateTimeInterface $invitation_sent_at): self
    {
        $this->invitation_sent_at = $invitation_sent_at;

        return $this;
    }



    public function getReassigned(): ?string
    {
        return $this->reassigned;
    }
    public function setReassigned(?string $reassigned): self
    {
        $this->reassigned = $reassigned;

        return $this;
    }


    
  
    

    public function getExternalirbrevieweremail(): ?string
    {
        return $this->external_irbreviewer_email;
    }
    public function setExternalirbrevieweremail(?string $external_irbreviewer_email): self
    {
        $this->external_irbreviewer_email = $external_irbreviewer_email;

        return $this;
    }

 
    
    public function getExternalirbreviewerName(): ?string
    {
        return $this->external_irbreviewer_name;
    }
    
    public function setExternalirbreviewerName(?string $external_irbreviewer_name): self
    {
        $this->external_irbreviewer_name = $external_irbreviewer_name;

        return $this;
    }

    
    
    public function getMiddleName(): ?string
    {
        return $this->middle_name;
    }
    
    public function setMiddleName(?string $middle_name): self
    {
        $this->middle_name = $middle_name;

        return $this;
    }
    


    
    public function getLastName(): ?string
    {
        return $this->last_name;
    }
    
    public function setLastName(?string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }
    

    public function getInvitationDueDate(): ?\DateTimeInterface
    {
        return $this->invitationDueDate;
    }

    public function setInvitationDueDate(\DateTimeInterface $invitationDueDate): self
    {
        $this->invitationDueDate = $invitationDueDate;

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

    

   
    /**
     * @return Collection|Review[]
     */
    public function getIrbreviews(): Collection
    {
        return $this->irbreviews;
    }

    public function addReview(\App\Entity\IRB\IRBReview $review): self
    {
        if (!$this->irbreviews->contains($review)) {
            $this->irbreviews[] = $review;
            $review->setIRBReviewAssignment($this);
        }

        return $this;
    }

    public function removeReview(\App\Entity\IRB\IRBReview $review): self
    {
        if ($this->irbreviews->removeElement($review)) {
            // set the owning side to null (unless already changed)
            if ($review->getIRBReviewAssignment() === $this) {
                $review->setIRBReviewAssignment(null);
            }
        }

        return $this;
    }

    public function getClosed(): ?bool
    {
        return $this->closed;
    }

    public function setClosed(?bool $closed): self
    {
        $this->closed = $closed;

        return $this;
    }
    public function getAllowToView(): ?bool
    {
        return $this->allowToView;
    }

    public function setAllowToView(?bool $allowToView): self
    {
        $this->allowToView = $allowToView;

        return $this;
    }

    public function getInactiveAssignment(): ?bool
    {
        return $this->inactive_assignment;
    }

    public function setInactiveAssignment(?bool $inactive_assignment): self
    {
        $this->inactive_assignment = $inactive_assignment;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    /**
     * @return Collection|ReviewerResponse[]
     */
    public function getReviewerResponses(): Collection
    {
        return $this->reviewerResponses;
    }

    public function addReviewerResponse(ReviewerResponse $reviewerResponse): self
    {
        if (!$this->reviewerResponses->contains($reviewerResponse)) {
            $this->reviewerResponses[] = $reviewerResponse;
            $reviewerResponse->setReviewAssignment($this);
        }

        return $this;
    }

    public function removeReviewerResponse(ReviewerResponse $reviewerResponse): self
    {
        if ($this->reviewerResponses->removeElement($reviewerResponse)) {
            // set the owning side to null (unless already changed)
            if ($reviewerResponse->getReviewAssignment() === $this) {
                $reviewerResponse->setReviewAssignment(null);
            }
        }

        return $this;
    }

    public function getWaiver(): ?string
    {
        return $this->waiver;
    }

    public function setWaiver(string $waiver): self
    {
        $this->waiver = $waiver;

        return $this;
    }

    public function getRiskLevel(): ?string
    {
        return $this->riskLevel;
    }

    public function setRiskLevel(string $riskLevel): self
    {
        $this->riskLevel = $riskLevel;

        return $this;
    }

    public function getRecommendation(): ?string
    {
        return $this->recommendation;
    }

    public function setRecommendation(string $recommendation): self
    {
        $this->recommendation = $recommendation;

        return $this;
    }

    public function getHowOftenStudyReviewed(): ?string
    {
        return $this->howOftenStudyReviewed;
    }

    public function setHowOftenStudyReviewed(string $howOftenStudyReviewed): self
    {
        $this->howOftenStudyReviewed = $howOftenStudyReviewed;

        return $this;
    }

    public function getReviewedAt(): ?\DateTimeInterface
    {
        return $this->reviewedAt;
    }

    public function setReviewedAt(?\DateTimeInterface $reviewedAt): self
    {
        $this->reviewedAt = $reviewedAt;

        return $this;
    }
    
}
