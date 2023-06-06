<?php

namespace App\Entity\IRB;

use App\Repository\IRB\RevisionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=RevisionRepository::class)
 */
class Revision
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="revisions")
     * @ORM\JoinColumn(nullable=false)
     */
    private $application;

    /**
     * @ORM\OneToMany(targetEntity=RevisionAttachment::class, mappedBy="revision", orphanRemoval=true,cascade={"all"})
     */
    private $revisionAttachments;

    public function __construct()
    {
        $this->revisionAttachments = new ArrayCollection();
    }

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

    /**
     * @return Collection|RevisionAttachment[]
     */
    public function getRevisionAttachments(): Collection
    {
        return $this->revisionAttachments;
    }

    public function addRevisionAttachments(RevisionAttachment $revisionAttachment): self
    {
        if (!$this->revisionAttachments->contains($revisionAttachment)) {
            $this->revisionAttachments[] = $revisionAttachment;
            $revisionAttachment->setRevision($this);
        }

        return $this;
    }

    public function removeRevisionAttachments(RevisionAttachment $revisionAttachment): self
    {
        if ($this->revisionAttachments->removeElement($revisionAttachment)) {
            // set the owning side to null (unless already changed)
            if ($revisionAttachment->getRevision() === $this) {
                $revisionAttachment->setRevision(null);
            }
        }

        return $this;
    }

    public function addRevisionAttachment(RevisionAttachment $revisionAttachment): self
    {
        if (!$this->revisionAttachments->contains($revisionAttachment)) {
            $this->revisionAttachments[] = $revisionAttachment;
            $revisionAttachment->setRevision($this);
        }

        return $this;
    }

    public function removeRevisionAttachment(RevisionAttachment $revisionAttachment): self
    {
        if ($this->revisionAttachments->removeElement($revisionAttachment)) {
            // set the owning side to null (unless already changed)
            if ($revisionAttachment->getRevision() === $this) {
                $revisionAttachment->setRevision(null);
            }
        }

        return $this;
    }
}
