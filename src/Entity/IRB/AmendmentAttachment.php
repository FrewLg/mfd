<?php

namespace App\Entity\IRB;

use App\Repository\AmendmentAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
/**
 * @ORM\Entity(repositoryClass=AmendmentAttachmentRepository::class)
 * @Vich\Uploadable
 */
class AmendmentAttachment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Amendment::class, inversedBy="amendmentAttachments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $amendment;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $file;

    /**
     * 
     * @Vich\UploadableField(mapping="application_file", fileNameProperty="file")
     * 
     * @var File|null
     */
    private $uploadFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $name;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAmendment(): ?Amendment
    {
        return $this->amendment;
    }

    public function setAmendment(?Amendment $amendment): self
    {
        $this->amendment = $amendment;

        return $this;
    }

    public function getFile(): ?string
    {
        return $this->file;
    }

    public function setFile(string $file): self
    {
        $this->file = $file;

        return $this;
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

    public function getUploadFile(): ?File
    {
        return $this->uploadFile;
    }
    public function setUploadFile(?File $imageFile = null): void
    {
        $this->uploadFile = $imageFile;

        // if (null !== $imageFile) {
        //     // It is required that at least one field changes if you are using doctrine
        //     // otherwise the event listeners won't be called and the file is lost
        //     $this->updatedAt = new \DateTimeImmutable();
        // }
    }
}
