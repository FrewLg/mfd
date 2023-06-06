<?php

namespace App\Entity\CRP;

use App\Repository\CRP\ProjectTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectTypeRepository::class)
 * @ORM\Table(name="crp_project_type")
 *  */
class ProjectType
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
     * @ORM\OneToMany(targetEntity=CollaborativeResearchProject::class, mappedBy="ProjectType", orphanRemoval=true)
     */
    private $collaborativeResearchProjects;

    public function __construct()
    {
        $this->collaborativeResearchProjects = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }
    public function __toString(): string
    {
        return  $this->name;
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
            $collaborativeResearchProject->setProjectType($this);
        }

        return $this;
    }

    public function removeCollaborativeResearchProject(CollaborativeResearchProject $collaborativeResearchProject): self
    {
        if ($this->collaborativeResearchProjects->removeElement($collaborativeResearchProject)) {
            // set the owning side to null (unless already changed)
            if ($collaborativeResearchProject->getProjectType() === $this) {
                $collaborativeResearchProject->setProjectType(null);
            }
        }

        return $this;
    }
}
