<?php

namespace App\Entity\IRB;

use App\Entity\College;
use App\Entity\User;
use App\Repository\IRB\BoardMemberRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @UniqueEntity( fields={"user"},

 *     message="This user is already board member."
 * )
 * @ORM\Entity(repositoryClass=BoardMemberRepository::class)
 */
class BoardMember
{
    public   $statuses = [
        1 => "Active",
        2 => "Deactivated"
    ];
    const ROLE_CHAIR = 'ROLE_CHAIR';
    const ROLE_VICE_CHAIR = 'ROLE_VICE_CHAIR';
    const ROLE_SECRETARY = 'ROLE_SECRETARY';
    const ROLE_COORDINATOR = 'ROLE_COORDINATOR';
    const ROLE_MEMBER = 'ROLE_MEMBER';


    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\OneToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=true, onDelete="SET NULL")
     */
    private $user;

    /**
     * @ORM\Column(type="datetime")
     */
    private $assignedAt;

    /**
     * @ORM\ManyToOne(targetEntity=User::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $assignedBy;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=College::class, inversedBy="boardMembers")
     */
    private $college;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    private $role;

    /**
     * @ORM\ManyToMany(targetEntity=Meeting::class, mappedBy="attendee")
     */
    private $meetings;



    public function __construct()
    {
        $this->assignedAt = new \DateTime('now');
        $this->status = 1;
        $this->meetings = new ArrayCollection();
    }

    public function __toString()
    {

        return $this->user;
    }

    public function getStatusText()
    {
        return $this->statuses[$this->status];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getAssignedAt(): ?\DateTimeInterface
    {
        return $this->assignedAt;
    }

    public function setAssignedAt(\DateTimeInterface $assignedAt): self
    {
        $this->assignedAt = $assignedAt;

        return $this;
    }

    public function getAssignedBy(): ?User
    {
        return $this->assignedBy;
    }

    public function setAssignedBy(?User $assignedBy): self
    {
        $this->assignedBy = $assignedBy;

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

    public function getCollege(): ?College
    {
        return $this->college;
    }

    public function setCollege(?College $college): self
    {
        $this->college = $college;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(?string $role): self
    {
        $this->role = $role;

        return $this;
    }

    /**
     * @return Collection|Meeting[]
     */
    public function getMeetings(): Collection
    {
        return $this->meetings;
    }

    public function addMeeting(Meeting $meeting): self
    {
        if (!$this->meetings->contains($meeting)) {
            $this->meetings[] = $meeting;
            $meeting->addAttendee($this);
        }

        return $this;
    }

    public function removeMeeting(Meeting $meeting): self
    {
        if ($this->meetings->removeElement($meeting)) {
            $meeting->removeAttendee($this);
        }

        return $this;
    }
}
