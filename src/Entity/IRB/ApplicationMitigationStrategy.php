<?php

namespace App\Entity\IRB;

use App\Repository\IRB\ApplicationMitigationStrategyRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ApplicationMitigationStrategyRepository::class)
 * @ORM\Table(name="irb_application_mitigation_strategy")
 */
class ApplicationMitigationStrategy
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

	    /**
     * @ORM\ManyToOne(targetEntity=MitigationStrategy::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $strategy;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="applicationMitigationStrategies",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $application;

    /**
     * @ORM\Column(type="text"  ,  nullable=true)
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checked;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getStrategy(): ?MitigationStrategy
    {
        return $this->strategy;
    }

    public function setStrategy(?MitigationStrategy $strategy): self
    {
        $this->strategy = $strategy;

        return $this;
    }

    public function getApplication(): ?Application
    {
        return $this->application;
    }

    public function setApplication(?Application $application): self
    {
        $this->application = $application;

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

    public function getChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }
}
