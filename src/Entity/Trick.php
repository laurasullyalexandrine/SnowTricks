<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\TrickRepository;
use Gedmo\Mapping\Annotation as Gedmo;
use Doctrine\Common\Collections\Collection;
use Doctrine\Common\Collections\ArrayCollection;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: TrickRepository::class)]
#[UniqueEntity(fields: ['name'], message:"il existe déjà une figure avec ce nom.")]
class Trick
{   
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    /**
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide.")
     */
    #[ORM\Column(length: 64, unique: true)]
    private ?string $name = null;

    /**
     * @Gedmo\Slug(fields={"name"})
     *
     * @var string|null
     */
    #[ORM\Column(length: 128)]
    private ?string $slug = null;

    /**
     * @Assert\NotBlank(message="Ce champ ne peut pas être vide.")
     */
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

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class)]
    private Collection $comments;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
        $this->medias = new ArrayCollection();
        $this->comments = new ArrayCollection();
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
    public function getMedias(bool $excludeMainImage = false): Collection
    {
        $medias = clone $this->medias;
        if ($excludeMainImage) {
            $mainImage = $this->getMainImage();
            if ($mainImage) {
                $medias->removeElement($mainImage);
            }
        }
        return $medias;
    }

    public function addMedia(Media $media): static
    {
        if (!$this->medias->contains($media)) {
            $this->medias->add($media);
            $media->setTrick($this);
        }

        return $this;
    }

    public function removeMedia(Media $media): static
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
     * Allows you to display only one image or the default image
     *
     * @return Media
     */
    public function getMainImage(): Media
    {
        $defaultMedia = new Media();
        $defaultMedia->setName(Media::DEFAULT_IMAGE);
        if (!$this->medias) {
            return $defaultMedia;
        }
        
        foreach ($this->medias as $media) {
            if ($media->getType() === Media::TYPE_IMAGE) {
                return $media;
            }
        }
        return $defaultMedia;
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

    /**
     * @return Collection<int, Comment>
     */
    public function getComments(): Collection
    {
        return $this->comments;
    }

    public function addComment(Comment $comment): static
    {
        if (!$this->comments->contains($comment)) {
            $this->comments->add($comment);
            $comment->setTrick($this);
        }

        return $this;
    }

    public function removeComment(Comment $comment): static
    {
        if ($this->comments->removeElement($comment)) {
            // set the owning side to null (unless already changed)
            if ($comment->getTrick() === $this) {
                $comment->setTrick(null);
            }
        }

        return $this;
    }
}
