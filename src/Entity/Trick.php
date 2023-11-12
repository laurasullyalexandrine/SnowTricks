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

    #[ORM\ManyToOne(inversedBy: 'tricks', cascade: ['persist'])]
    private ?User $user = null;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Comment::class, cascade: ['remove'])]
    private Collection $comments;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Image::class)]
    private Collection $images;

    #[ORM\OneToMany(mappedBy: 'trick', targetEntity: Video::class)]
    private Collection $videos;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
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

    // /**
    //  * @return Collection<int, Image>
    //  */
    // public function getImages(bool $excludeMainImage = false): Collection
    // {
    //     $images = clone $this->images;
    //     if ($excludeMainImage) {
    //         $mainImage = $this->getMainImage();
    //         if ($mainImage) {
    //             $images->removeElement($mainImage);
    //         }
    //     }
    //     return $images;
    // }

    // public function addImage(Image $image): static
    // {
    //     if (!$this->images->contains($image)) {
    //         $this->images->add($image);
    //         $image->setTrick($this);
    //     }

    //     return $this;
    // }

    // public function removeImage(Image $image): static
    // {
    //     if ($this->images->removeElement($image)) {
    //         // set the owning side to null (unless already changed)
    //         if ($image->getTrick() === $this) {
    //             $image->setTrick(null);
    //         }
    //     }

    //     return $this;
    // }

    // /**
    //  * Allows you to display only one image or the default image
    //  *
    //  * @return Image
    //  */
    // public function getMainImage(): Image
    // {
    //     $defaultImage = new Image();
    //     $defaultImage->setName(Image::DEFAULT_IMAGE);
        
    //     foreach ($this->images as $image) {
    //             return $image;
    //     }
 
    //     return $defaultImage;
    // }

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

    // /**
    //  * @return Collection<int, Video>
    //  */
    // public function getVideos(): Collection
    // {
    //     return $this->videos;
    // }

    // public function addVideo(Video $video): static
    // {
    //     if (!$this->videos->contains($video)) {
    //         $this->videos->add($video);
    //         $video->setTrick($this);
    //     }

    //     return $this;
    // }

    // public function removeVideo(Video $video): static
    // {
    //     if ($this->videos->removeElement($video)) {
    //         // set the owning side to null (unless already changed)
    //         if ($video->getTrick() === $this) {
    //             $video->setTrick(null);
    //         }
    //     }

    //     return $this;
    // }

    /**
     * @return Collection<int, Image>
     */
    public function getImages(): Collection
    {
        return $this->images;
    }

    public function addImage(Image $image): static
    {
        if (!$this->images->contains($image)) {
            $this->images->add($image);
            $image->setTrick($this);
        }

        return $this;
    }

    public function removeImage(Image $image): static
    {
        if ($this->images->removeElement($image)) {
            // set the owning side to null (unless already changed)
            if ($image->getTrick() === $this) {
                $image->setTrick(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Video>
     */
    public function getVideos(): Collection
    {
        return $this->videos;
    }

    public function addVideo(Video $video): static
    {
        if (!$this->videos->contains($video)) {
            $this->videos->add($video);
            $video->setTrick($this);
        }

        return $this;
    }

    public function removeVideo(Video $video): static
    {
        if ($this->videos->removeElement($video)) {
            // set the owning side to null (unless already changed)
            if ($video->getTrick() === $this) {
                $video->setTrick(null);
            }
        }

        return $this;
    }
}
