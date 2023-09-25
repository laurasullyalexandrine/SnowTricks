<?php

namespace App\Entity;

use App\Entity\Trait\MediaTrait;
use App\Repository\VideoRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VideoRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Video
{
    use MediaTrait;
}
