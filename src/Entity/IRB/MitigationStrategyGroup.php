<?php

namespace App\Entity\IRB;

use App\Repository\IRB\MitigationStrategyGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MitigationStrategyGroupRepository::class)
 * @ORM\Table(name="irb_mitigation_strategy_group")
 */
class MitigationStrategyGroup
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    /**
     * @ORM\OneToMany(targetEntity=MitigationStrategy::class, mappedBy="formGroup")
     */
    private $mitigationStrategies;

    public function __construct()
    {
        $this->mitigationStrategies = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    /**
     * @return Collection|MitigationStrategy[]
     */
    public function getMitigationStrategies(): Collection
    {
        return $this->mitigationStrategies;
    }

    public function addMitigationStrategy(MitigationStrategy $mitigationStrategy): self
    {
        if (!$this->mitigationStrategies->contains($mitigationStrategy)) {
            $this->mitigationStrategies[] = $mitigationStrategy;
            $mitigationStrategy->setFormGroup($this);
        }

        return $this;
    }

    public function removeMitigationStrategy(MitigationStrategy $mitigationStrategy): self
    {
        if ($this->mitigationStrategies->removeElement($mitigationStrategy)) {
            // set the owning side to null (unless already changed)
            if ($mitigationStrategy->getFormGroup() === $this) {
                $mitigationStrategy->setFormGroup(null);
            }
        }

        return $this;
    }
}
