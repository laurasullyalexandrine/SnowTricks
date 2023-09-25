<?php

namespace App\Entity;

use App\Entity\Trait\MediaTrait;
use App\Repository\ImageRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;

#[ORM\Entity(repositoryClass: ImageRepository::class)]
#[ORM\HasLifecycleCallbacks]
class Image
{
    use MediaTrait;

    /**
     * Function to save the recorded file
     */
    #[ORM\PostPersist]
    public function saveFile(): void
    {
        $this->uploadedFile->move(__DIR__ . '/../../public/upload/image', $this->name);
    }
}
