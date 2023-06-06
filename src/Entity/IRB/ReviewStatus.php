<?php

namespace App\Entity\IRB;

use App\Repository\IRB\ReviewStatusRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewStatusRepository::class)
 * @ORM\Table(name="irb_review_status")
 */
class ReviewStatus
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
     * @ORM\ManyToOne(targetEntity=ReviewStatusGroup::class, inversedBy="reviewStatuses")
     * @ORM\JoinColumn(nullable=false)
     */
    private $reviewGroup;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    private $description;

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

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getReviewGroup(): ?ReviewStatusGroup
    {
        return $this->reviewGroup;
    }

    public function setReviewGroup(?ReviewStatusGroup $reviewGroup): self
    {
        $this->reviewGroup = $reviewGroup;

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
}
