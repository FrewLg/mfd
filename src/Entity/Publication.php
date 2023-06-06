<?php

namespace App\Entity;

use App\Repository\PublicationRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=PublicationRepository::class)
 */
class Publication
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="publications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $author;

    /**
     * @ORM\Column(type="string",    length=255 , nullable=true)
     
     */
    private $doi;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $journal_name;

    /**
     * @ORM\Column(type="string", length=355)
     */
    private $title;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $impact_factor;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $citation_score;

    /**
     * @ORM\Column(type="date")
     */
    private $published_at;
 /**
     * @ORM\Column(type="datetime")
     */
    private $created_at;
    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    private $article_document;

    /**
     * @ORM\ManyToOne(targetEntity=MemberRole::class, inversedBy="publications")
     * @ORM\JoinColumn(nullable=false)
     */
    private $member_role;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAuthor(): ?User
    {
        return $this->author;
    }

    public function setAuthor(?User $author): self
    {
        $this->author = $author;

        return $this;
    }

    public function getDoi(): ?string
    {
        return $this->doi;
    }

    public function setDoi(string $doi): self
    {
        $this->doi = $doi;

        return $this;
    }

    public function getCreatedAt(): ?\DateTime
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTime  $created_at): self
    {
        $this->created_at = $created_at;

        return $this;
    }
    public function getJournalName(): ?string
    {
        return $this->journal_name;
    }

    public function setJournalName(string $journal_name): self
    {
        $this->journal_name = $journal_name;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getImpactFactor(): ?string
    {
        return $this->impact_factor;
    }

    public function setImpactFactor(?string $impact_factor): self
    {
        $this->impact_factor = $impact_factor;

        return $this;
    }

    public function getCitationScore(): ?string
    {
        return $this->citation_score;
    }

    public function setCitationScore(?string $citation_score): self
    {
        $this->citation_score = $citation_score;

        return $this;
    }

    public function getPublishedAt(): ?\DateTimeInterface
    {
        return $this->published_at;
    }

    public function setPublishedAt(\DateTimeInterface $published_at): self
    {
        $this->published_at = $published_at;

        return $this;
    }

    public function getArticleDocument(): ?string
    {
        return $this->article_document;
    }

    public function setArticleDocument(?string $article_document): self
    {
        $this->article_document = $article_document;

        return $this;
    }

    public function getMemberRole(): ?MemberRole
    {
        return $this->member_role;
    }

    public function setMemberRole(?MemberRole $member_role): self
    {
        $this->member_role = $member_role;

        return $this;
    }
}
