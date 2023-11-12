<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\VideoRepository;
use App\Validator as MyConstraints;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
class Video
{
    const TYPE_VIDEO = "video";
    const URL_TYPE_UNDEFINED = 0;
    const URL_TYPE_YOUTUBE = 1;
    const URL_TYPE_DAILYMOTION = 2;

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[MyConstraints\VideoTag()]
    private ?string $name = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $created_at = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updated_at = null;


    #[ORM\ManyToOne(inversedBy: 'videos', cascade: ['persist'])]
    private ?Trick $trick = null;

    public function __construct()
    {
        $this->created_at = new \DateTimeImmutable();
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

    public function getUrlType(): int 
    {
        if (str_contains($this->name, 'youtube')) {
            return self::URL_TYPE_YOUTUBE;
        } elseif (str_contains($this->name, 'dailymotion')) {
            return self::URL_TYPE_DAILYMOTION;
        } else {
            return self::URL_TYPE_UNDEFINED;
        }
    }
}
