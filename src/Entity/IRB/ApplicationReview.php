<?php

namespace App\Entity\IRB;

use App\Repository\IRB\ApplicationReviewRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApplicationReviewRepository::class)
 * @ORM\Table(name="irb_application_review")
 */
class ApplicationReview
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="applicationReviews",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $application;

    /**
     * @ORM\ManyToOne(targetEntity=ReviewStatus::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $review;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checked;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getReview(): ?ReviewStatus
    {
        return $this->review;
    }

    public function setReview(?ReviewStatus $review): self
    {
        $this->review = $review;

        return $this;
    }

    public function getChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }
}
