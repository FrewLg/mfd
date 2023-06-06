<?php

namespace App\Entity\IRB;

use App\Repository\IRB\ReviewStatusGroupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=ReviewStatusGroupRepository::class)
 * @ORM\Table(name="irb_review_status_group")
 */
class ReviewStatusGroup
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
     * @ORM\OneToMany(targetEntity=ReviewStatus::class, mappedBy="reviewGroup")
     */
    private $reviewStatuses;

    public function __construct()
    {
        $this->reviewStatuses = new ArrayCollection();
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
     * @return Collection|ReviewStatus[]
     */
    public function getReviewStatuses(): Collection
    {
        return $this->reviewStatuses;
    }

    public function addReviewStatus(ReviewStatus $reviewStatus): self
    {
        if (!$this->reviewStatuses->contains($reviewStatus)) {
            $this->reviewStatuses[] = $reviewStatus;
            $reviewStatus->setReviewGroup($this);
        }

        return $this;
    }

    public function removeReviewStatus(ReviewStatus $reviewStatus): self
    {
        if ($this->reviewStatuses->removeElement($reviewStatus)) {
            // set the owning side to null (unless already changed)
            if ($reviewStatus->getReviewGroup() === $this) {
                $reviewStatus->setReviewGroup(null);
            }
        }

        return $this;
    }
}
