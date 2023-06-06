<?php

namespace App\Entity\IRB;

use App\Entity\College;
use App\Repository\IRB\MitigationStrategyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=MitigationStrategyRepository::class)
 * @ORM\Table(name="irb_mitigation_strategy")
 */
class MitigationStrategy
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
     * @ORM\ManyToOne(targetEntity=College::class, inversedBy="mitigationStrategies")
     */
    private $college;

    /**
     * @ORM\ManyToOne(targetEntity=MitigationStrategyGroup::class, inversedBy="mitigationStrategies")
     */
    private $formGroup;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $type;

    public function __toString()
    {
        return $this->name;
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

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

    public function getFormGroup(): ?MitigationStrategyGroup
    {
        return $this->formGroup;
    }

    public function setFormGroup(?MitigationStrategyGroup $formGroup): self
    {
        $this->formGroup = $formGroup;

        return $this;
    }

    public function getType(): ?int
    {
        return $this->type;
    }

    public function setType(?int $type): self
    {
        $this->type = $type;

        return $this;
    }
}
