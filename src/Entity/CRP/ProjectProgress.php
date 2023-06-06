<?php

namespace App\Entity\CRP;

use App\Repository\CRP\ProjectProgressRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ProjectProgressRepository::class)
 */
class ProjectProgress
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
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $progress;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $progressColor;

    /**
     * @ORM\OneToMany(targetEntity=CollaborativeResearchProject::class, mappedBy="projectProgress")
     */
    private $project;

    public function __construct()
    {
        $this->project = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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
    public function __toString(): string
    {
        return  $this->name;
    }
    public function getProgress(): ?string
    {
        return $this->progress;
    }

    public function setProgress(?string $progress): self
    {
        $this->progress = $progress;

        return $this;
    }

    public function getProgressColor(): ?string
    {
        return $this->progressColor;
    }

    public function setProgressColor(?string $progressColor): self
    {
        $this->progressColor = $progressColor;

        return $this;
    }

    /**
     * @return Collection<int, CollaborativeResearchProject>
     */
    public function getProject(): Collection
    {
        return $this->project;
    }

    public function addProject(CollaborativeResearchProject $project): self
    {
        if (!$this->project->contains($project)) {
            $this->project[] = $project;
            $project->setProjectProgress($this);
        }

        return $this;
    }

    public function removeProject(CollaborativeResearchProject $project): self
    {
        if ($this->project->removeElement($project)) {
            // set the owning side to null (unless already changed)
            if ($project->getProjectProgress() === $this) {
                $project->setProjectProgress(null);
            }
        }

        return $this;
    }
}
