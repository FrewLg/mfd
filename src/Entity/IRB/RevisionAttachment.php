<?php

namespace App\Entity\IRB;

use App\Repository\IRB\RevisionAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;


/**
 * @ORM\Entity(repositoryClass=RevisionAttachmentRepository::class)
 * @Vich\Uploadable
 */
class RevisionAttachment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Revision::class, inversedBy="revisionAttachments",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $revision;

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
     * @ORM\Column(type="boolean")
     */
    private $checked;

    /**
     * @ORM\ManyToOne(targetEntity=AttachmentType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRevision(): ?Revision
    {
        return $this->revision;
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
    public function getUploadFile()
    {
     return  $this->uploadFile;

        // if (null !== $imageFile) {
        //     // It is required that at least one field changes if you are using doctrine
        //     // otherwise the event listeners won't be called and the file is lost
        //     $this->updatedAt = new \DateTimeImmutable();
        // }
    }

    public function setRevision(?Revision $revision): self
    {
        $this->revision = $revision;

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

    public function getChecked(): ?bool
    {
        return $this->checked;
    }

    public function setChecked(bool $checked): self
    {
        $this->checked = $checked;

        return $this;
    }

    public function getType(): ?AttachmentType
    {
        return $this->type;
    }

    public function setType(?AttachmentType $type): self
    {
        $this->type = $type;

        return $this;
    }
}
