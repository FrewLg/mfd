<?php

namespace App\Entity\IRB;

use App\Entity\IRB\Application ;
use App\Repository\ApplicationFeedbackRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApplicationFeedbackRepository::class)
 */
class ApplicationFeedback
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;
 
    /**
     * @ORM\ManyToOne(targetEntity=\App\Entity\User::class, inversedBy="applicationFeedback")
     */
    private $feedbackFrom;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $createdAt;

      /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $sendMail;
      /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    private $allowWrite;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $attachment;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="applicationFeedbacks")
     */
    private $application;

    // /**
    //  * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="applicationFeedback")
    //  * @ORM\JoinColumn(nullable=false)
    //  */
    // private $application;

    // public function __construct()
    // {
    //     $this->application = new ArrayCollection();
    // }

    public function getId(): ?int
    {
        return $this->id;
    }


    public function getSendMail(): ?bool
    {
        return $this->sendMail;
    }

    public function setSendMail(?bool $sendMail): self
    {
        $this->sendMail = $sendMail;

        return $this;
    }

    public function getAllowWrite(): ?bool
    {
        return $this->allowWrite;
    }

    public function setAllowWrite(?bool $allowWrite): self
    {
        $this->allowWrite = $allowWrite;

        return $this;
    }


    
    public function getFeedbackFrom(): ?\App\Entity\User
    {
        return $this->feedbackFrom;
    }

    public function setFeedbackFrom(?\App\Entity\User $feedbackFrom): self
    {
        $this->feedbackFrom = $feedbackFrom;

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

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeInterface $createdAt): self
    {
        $this->createdAt = $createdAt;

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

    // public function getApplication(): ?\App\Entity\IRB\Application
    // {
    //     return $this->application;
    // }

    // public function setApplication(?Application $application): self
    // {
    //     $this->application = $application;

    //     return $this;
    // }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

        return $this;
    }
}
