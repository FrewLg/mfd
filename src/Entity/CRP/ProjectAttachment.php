<?php

namespace App\Entity\CRP;

use App\Repository\CRP\ProjectAttachmentRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectAttachmentRepository::class)
 */
class ProjectAttachment
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
    private $file;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dataAttached;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $dateUpdated;

    /**
     * @ORM\ManyToOne(targetEntity=CollaborativeResearchProject::class, inversedBy="projectAttachments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $project;

    
  

    /**
     * @ORM\ManyToOne(targetEntity=ProjectAttachmentType::class, inversedBy="projectAttachments")
     */
    private $attachmentType;

  

    public function __construct()
    {
         $this->attachementType = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(?string $file): self
    {
        $this->file = $file;

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

    public function getDataAttached(): ?\DateTimeInterface
    {
        return $this->dataAttached;
    }

    public function setDataAttached(?\DateTimeInterface $dataAttached): self
    {
        $this->dataAttached = $dataAttached;

        return $this;
    }

    public function getDateUpdated(): ?\DateTimeInterface
    {
        return $this->dateUpdated;
    }

    public function setDateUpdated(?\DateTimeInterface $dateUpdated): self
    {
        $this->dateUpdated = $dateUpdated;

        return $this;
    }

    public function getProject(): ?CollaborativeResearchProject
    {
        return $this->project;
    }

    public function setProject(?CollaborativeResearchProject $project): self
    {
        $this->project = $project;

        return $this;
    }

    

    public function getAttachmentType(): ?ProjectAttachmentType
    {
        return $this->attachmentType;
    }

    public function setAttachmentType(?ProjectAttachmentType $attachmentType): self
    {
        $this->attachmentType = $attachmentType;

        return $this;
    }

    
}
