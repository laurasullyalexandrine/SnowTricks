<?php
// Cette class qui n'en ai pas une intégre la notion de 'trait' 
// Cette notion permet d'alléger le code en mutualisant les propriétés communes
// aux entités (elle ne peut pas être instanciée et n'a pas de méthode __construct)
namespace App\Entity\Trait;

use Doctrine\ORM\Mapping as ORM;
use App\Entity\Trick;

use Symfony\Component\HttpFoundation\File\UploadedFile;


trait MediaTrait
{
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
     * Function to save the recorded file
     */
    #[ORM\PostPersist]
    public function saveFile(): void
    {
        $this->uploadedFile->move(__DIR__ . '/../../../public/upload/image', $this->name);
    }

}