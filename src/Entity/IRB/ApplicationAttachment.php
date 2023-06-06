<?php

namespace App\Entity\IRB;

use App\Repository\IRB\ApplicationAttachmentRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=ApplicationAttachmentRepository::class)
 * @ORM\Table(name="irb_application_attachment")
 * @Vich\Uploadable
 */
class ApplicationAttachment
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=Application::class, inversedBy="applicationAttachments",cascade={"persist"})
     * @ORM\JoinColumn(nullable=false)
     */
    private $application;

    /**
     * @ORM\ManyToOne(targetEntity=AttachmentType::class)
     * @ORM\JoinColumn(nullable=false)
     */
    private $type;

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
    private $file;

    /**
     * @ORM\Column(type="boolean")
     */
    private $checked;

    public function getId(): ?int
    {
        return $this->id;
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
    public function __toString()
    {
       return $this->type;
    }

    public function getUploadFile(): ?File
    {
        return $this->uploadFile;
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

    public function getType(): ?AttachmentType
    {
        return $this->type;
    }

    public function setType(?AttachmentType $type): self
    {
        $this->type = $type;

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
}
