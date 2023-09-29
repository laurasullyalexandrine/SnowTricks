<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TrickRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Gedmo\Mapping\Annotation as Gedmo;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[UniqueEntity(fields: ['name'], message:"il existe déjà une figure avec ce nom.")]
class Trick
{   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64, unique: true)]
    private ?string $name = null;

    /**
     * @Gedmo\Slug(fields={"name"})
     *
     * @var string|null
     */
    #[ORM\Column(length: 128)]
    private ?string $slug = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $description = null;

    #[ORM\ManyToOne(inversedBy: 'tricks', cascade: ['persist'])]
    private ?Trickgroup $trick_group = null;

    #[ORM\Column(options: ["default" => "CURRENT_TIMESTAMP"])]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Media::class, cascade: ['persist'], orphanRemoval: true)]
    private Collection $medias;

    #[ORM\ManyToOne(inversedBy: 'tricks', cascade: ['persist'])]
    private ?User $user = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->medias = new ArrayCollection();
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

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    public function getTrickGroup(): ?Trickgroup
    {
        return $this->trick_group;
    }

    public function setTrickGroup(?Trickgroup $trick_group): static
    {
        $this->trick_group = $trick_group;

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

    /**
     * @return Collection<int, Media>
     */
    public function getMedias(): Collection
    {
        return $this->medias;
    }

    public function addImage(Media $media): static
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->setTrick($this);
        }

        return $this;
    }

    public function removeImage(Media $media): static
    {
        if ($this->medias->removeElement($media)) {
            // set the owning side to null (unless already changed)
            if ($media->getTrick() === $this) {
                $media->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * Permet d'afficher qu'une seule image
     *
     * @return Media|null
     */
    public function getMainImage(): ?Media
    {
        if (!$this->medias) {
            return null;
        } 

        return $this->medias[0] ?? null;
    }

    public function getTags(): array
    {
        $tags = [];
        $tags = [
            'author' => $this->user,
            'createdAt' => $this->created_at,
            'updatedAt' => ($this->updated_at === null) ? '' : $this->updated_at,
            'trickGroup' => $this->trick_group,
        ];

        return $tags;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): static
    {
        $this->user = $user;

        return $this;
    }
}
