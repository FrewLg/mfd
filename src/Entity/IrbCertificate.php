<?php

namespace App\Entity;

use App\Entity\IRB\Application;
use App\Repository\IrbCertificateRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=IrbCertificateRepository::class)
 */
class IrbCertificate
{ 
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

  
    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $approvedAt;

    /**
     * @ORM\Column(type="string", length=255)
     */
            private $certificateCode;

    /**
     * @ORM\Column(type="date")
     */
    private $validUntil;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $irbApplication;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $renewed;

     /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\User::class, inversedBy="certs")
     * @ORM\JoinColumn(nullable=true)
     */
    private $approvedBy;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $renewedAt;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getApprovedBy() 
    {
        return $this->approvedBy;
    }

    public function setApprovedBy(?\App\Entity\User $approvedBy): self
    {
        $this->approvedBy = $approvedBy;

        return $this;
    }
 

    public function getApprovedAt(): ?\DateTimeInterface
    {
        return $this->approvedAt;
    }

    public function setApprovedAt(\DateTimeInterface $approvedAt): self
    {
        $this->approvedAt = $approvedAt;

        return $this;
    }

    public function getCertificateCode(): ?string
    {
        return $this->certificateCode;
    }

    public function setCertificateCode(string $certificateCode): self
    {
        $this->certificateCode = $certificateCode;

        return $this;
    }

    public function getValidUntil(): ?\DateTimeInterface
    {
        return $this->validUntil;
    }

    public function setValidUntil(\DateTimeInterface $validUntil): self
    {
        $this->validUntil = $validUntil;

        return $this;
    }

    public function getIrbApplication(): ?Application
    {
        return $this->irbApplication;
    }

    public function setIrbApplication(?Application $irbApplication): self
    {
        $this->irbApplication = $irbApplication;

        return $this;
    }

    public function getRenewed(): ?bool
    {
        return $this->renewed;
    }

    public function setRenewed(?bool $renewed): self
    {
        $this->renewed = $renewed;

        return $this;
    }

    public function getRenewedAt(): ?\DateTimeInterface
    {
        return $this->renewedAt;
    }

    public function setRenewedAt(?\DateTimeInterface $renewedAt): self
    {
        $this->renewedAt = $renewedAt;

        return $this;
    }
}
