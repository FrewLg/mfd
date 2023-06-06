<?php

namespace App\Entity\IRB;

use App\Repository\IRB\IrbReviewAtachementRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IrbReviewAtachementRepository::class)
 */
class IrbReviewAtachement
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
    private $attachement;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $cteatedAt;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $updatedAt;

    /**
     * @ORM\ManyToOne(targetEntity=App\Entity\College::class, inversedBy="irbReviewAtachements")
     */
    private $college;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attachmentName;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAttachement(): ?string
    {
        return $this->attachement;
    }

    public function setAttachement(?string $attachement): self
    {
        $this->attachement = $attachement;

        return $this;
    }

    public function getCteatedAt(): ?\DateTimeInterface
    {
        return $this->cteatedAt;
    }

    public function setCreatedAt(?\DateTimeInterface $cteatedAt): self
    {
        $this->cteatedAt = $cteatedAt;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeInterface $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getCollege(): ?\App\Entity\College
    {
        return $this->college;
    }

    public function setCollege(?\App\Entity\College $college): self
    {
        $this->college = $college;

        return $this;
    }

    public function getAttachmentName(): ?string
    {
        return $this->attachmentName;
    }

    public function setAttachmentName(?string $attachmentName): self
    {
        $this->attachmentName = $attachmentName;

        return $this;
    }
}
