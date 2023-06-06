<?php

namespace App\Entity\IRB;

use App\Entity\IRB\Application;
use App\Repository\IRB\AmendmentRepository;
use DateTime;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=AmendmentRepository::class)
 */
class Amendment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="amendments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $application;

    /**
     * @ORM\Column(type="text")
     */
    private $purpose;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $changes;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $recruitment;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $procedures;

    /**
     * @ORM\OneToMany(targetEntity=AmendmentAttachment::class, mappedBy="amendment", orphanRemoval=true)
     */
    private $amendmentAttachments;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updateAt;

    /**
     * @ORM\ManyToOne(targetEntity=IRBStatus::class)
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $statusText;

    public function __construct()
    {
        $this->createdAt=new DateTime();
        $this->amendmentAttachments = new ArrayCollection();
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

    public function getPurpose(): ?string
    {
        return $this->purpose;
    }

    public function setPurpose(string $purpose): self
    {
        $this->purpose = $purpose;

        return $this;
    }

    public function getChanges(): ?string
    {
        return $this->changes;
    }

    public function setChanges(?string $changes): self
    {
        $this->changes = $changes;

        return $this;
    }

    public function getRecruitment(): ?string
    {
        return $this->recruitment;
    }

    public function setRecruitment(?string $recruitment): self
    {
        $this->recruitment = $recruitment;

        return $this;
    }

    public function getProcedures(): ?string
    {
        return $this->procedures;
    }

    public function setProcedures(?string $procedures): self
    {
        $this->procedures = $procedures;

        return $this;
    }

    /**
     * @return Collection|AmendmentAttachment[]
     */
    public function getAmendmentAttachments(): Collection
    {
        return $this->amendmentAttachments;
    }

    public function addAmendmentAttachment(AmendmentAttachment $amendmentAttachment): self
    {
        if (!$this->amendmentAttachments->contains($amendmentAttachment)) {
            $this->amendmentAttachments[] = $amendmentAttachment;
            $amendmentAttachment->setAmendment($this);
        }

        return $this;
    }

    public function removeAmendmentAttachment(AmendmentAttachment $amendmentAttachment): self
    {
        if ($this->amendmentAttachments->removeElement($amendmentAttachment)) {
            // set the owning side to null (unless already changed)
            if ($amendmentAttachment->getAmendment() === $this) {
                $amendmentAttachment->setAmendment(null);
            }
        }

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

    public function getUpdateAt(): ?\DateTimeInterface
    {
        return $this->updateAt;
    }

    public function setUpdateAt(?\DateTimeInterface $updateAt): self
    {
        $this->updateAt = $updateAt;

        return $this;
    }

    public function getStatus(): ?IRBStatus
    {
        return $this->status;
    }

    public function setStatus(?IRBStatus $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getStatusText(): ?string
    {
        return $this->statusText;
    }

    public function setStatusText(?string $statusText): self
    {
        $this->statusText = $statusText;

        return $this;
    }
}
