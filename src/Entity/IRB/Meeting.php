<?php

namespace App\Entity\IRB;

use App\Entity\College;
use App\Entity\User;
use App\Repository\IRB\MeetingRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MeetingRepository::class)
 */
class Meeting
{
    const STATUS_ACTIVE=1;
    const STATUS_CLOSED=2;
    const STATUS_SCHEDULED=3;
    const messages=[
        self::STATUS_ACTIVE=>"Active",
        self::STATUS_CLOSED=>"Closed",
        self::STATUS_SCHEDULED=>"Scheduled",
    ];
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255,unique=true)
     */
    private $number;

    /**
     * @ORM\Column(type="datetime")
     */
    private $heldAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $createdBy;

    /**
     * @ORM\ManyToOne(targetEntity=College::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $college;

    /**
     * @ORM\ManyToMany(targetEntity=BoardMember::class, inversedBy="meetings")
     */
    private $attendee;

    

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $note;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private $minuteTakenAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     */
    private $minuteTakenBy;

    /**
     * @ORM\OneToMany(targetEntity=Application::class, mappedBy="meeting")
     */
    private $applications;


   

    public function __construct()
    {
        $this->attendee = new ArrayCollection();
        $this->applications = new ArrayCollection();
      
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatusText(): ?string
    {
        return self::messages[$this->status];
    }
    public function getNumber(): ?string
    {
        return $this->number;
    }

    public function setNumber(string $number): self
    {
        $this->number = $number;

        return $this;
    }

    public function getHeldAt(): ?\DateTimeInterface
    {
        return $this->heldAt;
    }

    public function setHeldAt(\DateTimeInterface $heldAt): self
    {
        $this->heldAt = $heldAt;

        return $this;
    }

    public function getCreatedBy(): ?User
    {
        return $this->createdBy;
    }

    public function setCreatedBy(?User $createdBy): self
    {
        $this->createdBy = $createdBy;

        return $this;
    }

 

    public function getCollege(): ?College
    {
        return $this->college;
    }

    public function setCollege(?College $college): self
    {
        $this->college = $college;

        return $this;
    }

    /**
     * @return Collection|BoardMember[]
     */
    public function getAttendee(): Collection
    {
        return $this->attendee;
    }

    public function addAttendee(BoardMember $attendee): self
    {
        if (!$this->attendee->contains($attendee)) {
            $this->attendee[] = $attendee;
        }

        return $this;
    }

    public function removeAttendee(BoardMember $attendee): self
    {
        $this->attendee->removeElement($attendee);

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

    public function getNote(): ?string
    {
        return $this->note;
    }

    public function setNote(?string $note): self
    {
        $this->note = $note;

        return $this;
    }

    public function getMinuteTakenAt(): ?\DateTimeInterface
    {
        return $this->minuteTakenAt;
    }

    public function setMinuteTakenAt(?\DateTimeInterface $minuteTakenAt): self
    {
        $this->minuteTakenAt = $minuteTakenAt;

        return $this;
    }

    public function getMinuteTakenBy(): ?User
    {
        return $this->minuteTakenBy;
    }

    public function setMinuteTakenBy(?User $minuteTakenBy): self
    {
        $this->minuteTakenBy = $minuteTakenBy;

        return $this;
    }

    /**
     * @return Collection<int, Application>
     */
    public function getApplications(): Collection
    {
        return $this->applications;
    }

    public function addApplication(Application $application): self
    {
        if (!$this->applications->contains($application)) {
            $this->applications[] = $application;
            $application->setMeeting($this);
        }

        return $this;
    }

    public function removeApplication(Application $application): self
    {
        if ($this->applications->removeElement($application)) {
            // set the owning side to null (unless already changed)
            if ($application->getMeeting() === $this) {
                $application->setMeeting(null);
            }
        }

        return $this;
    }

   

  
}
