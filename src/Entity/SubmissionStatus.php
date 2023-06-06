<?php

namespace App\Entity;

use App\Repository\SubmissionStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=SubmissionStatusRepository::class)
 */
class SubmissionStatus
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
    private $name;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\OneToMany(targetEntity=Submission::class, mappedBy="submissionStatus")
     */
    private $submission;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statusColor;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $statusChangedAt;

    public function __construct()
    {
        $this->submission = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

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

    /**
     * @return Collection<int, Submission>
     */
    public function getSubmission(): Collection
    {
        return $this->submission;
    }

    public function addSubmission(Submission $submission): self
    {
        if (!$this->submission->contains($submission)) {
            $this->submission[] = $submission;
            $submission->setSubmissionStatus($this);
        }

        return $this;
    }

    public function removeSubmission(Submission $submission): self
    {
        if ($this->submission->removeElement($submission)) {
            // set the owning side to null (unless already changed)
            if ($submission->getSubmissionStatus() === $this) {
                $submission->setSubmissionStatus(null);
            }
        }

        return $this;
    }

    public function getStatusColor(): ?string
    {
        return $this->statusColor;
    }

    public function setStatusColor(?string $statusColor): self
    {
        $this->statusColor = $statusColor;

        return $this;
    }

    public function getStatusChangedAt(): ?\DateTimeInterface
    {
        return $this->statusChangedAt;
    }

    public function setStatusChangedAt(?\DateTimeInterface $statusChangedAt): self
    {
        $this->statusChangedAt = $statusChangedAt;

        return $this;
    }
}
