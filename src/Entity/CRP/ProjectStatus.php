<?php

namespace App\Entity\CRP;

use App\Repository\CRP\ProjectStatusRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectStatusRepository::class)
 * @ORM\Table(name="crp_project_status")
 * 
 */
class ProjectStatus
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
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $description;

    /**
     *@ORM\OneToMany(targetEntity=CollaborativeResearchProject::class, mappedBy="ProjectStatus", orphanRemoval=true)
     */
    private $collaborativeResearchProjects;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $statusColor;

    public function __construct()
    {
        $this->collaborativeResearchProjects = new ArrayCollection();
    }

    public function __toString(): string
    {
        return  $this->status;
    }
    
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(?string $status): self
    {
        $this->status = $status;

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
     * @return Collection<int, CollaborativeResearchProject>
     */
    public function getCollaborativeResearchProjects(): Collection
    {
        return $this->collaborativeResearchProjects;
    }

    public function addCollaborativeResearchProject(CollaborativeResearchProject $collaborativeResearchProject): self
    {
        if (!$this->collaborativeResearchProjects->contains($collaborativeResearchProject)) {
            $this->collaborativeResearchProjects[] = $collaborativeResearchProject;
            $collaborativeResearchProject->setProjectStatus($this);
        }

        return $this;
    }

    public function removeCollaborativeResearchProject(CollaborativeResearchProject $collaborativeResearchProject): self
    {
        if ($this->collaborativeResearchProjects->removeElement($collaborativeResearchProject)) {
            // set the owning side to null (unless already changed)
            if ($collaborativeResearchProject->getProjectStatus() === $this) {
                $collaborativeResearchProject->setProjectStatus(null);
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
}
