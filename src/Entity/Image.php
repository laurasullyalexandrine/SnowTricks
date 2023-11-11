<?php

namespace App\Entity;

use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: imageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Image
{
    const BASE_PATH = 'upload/image';
    const DEFAULT_IMAGE = 'default.png';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\ManyToOne(inversedBy: 'images', cascade: ['persist'])]
    private ?Trick $trick = null;

    private ?UploadedFile $uploadedFile = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
    }

    public function __toString()
    {
        return self::BASE_PATH . '/' . $this->name;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->created_at;
    }

    public function setCreatedAt(\DateTimeImmutable $created_at): static
    {
        $this->created_at = $created_at;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

        return $this;
    }

    public function getTrick(): ?Trick
    {
        return $this->trick;
    }

    public function setTrick(?Trick $trick): static
    {
        $this->trick = $trick;

        return $this;
    }

    /**
     * Function to edit name of image upload
     */
    #[ORM\PrePersist]
    public function setFileName(): void
    {
        $this->uploadedFile = new UploadedFile($this->name, 'test', null, null, true); 
        $this->name = uniqid() . '.' . $this->uploadedFile->guessExtension();
    }

    /**
     * Function to save and to update the recorded file
     *
     * @return void
     */
    #[ORM\PostPersist, ORM\PostUpdate]
    public function saveFile(): void
    {
        $this->uploadedFile->move(__DIR__ . '/../../public/' . self::BASE_PATH, $this->name);
    }

    /**
     * Function that allows you to update uploaded files
     *
     * @param UploadedFile $uploadedFile
     * @return void
     */
    public function update(UploadedFile $uploadedFile): void
    {
        $this->uploadedFile = $uploadedFile;
        $this->name = uniqid() . '.' . $this->uploadedFile->guessExtension();
    }
}
