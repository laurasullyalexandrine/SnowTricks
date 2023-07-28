<?php

// src/Service/FileUploader.php
namespace App\Service;

use App\Entity\Trick;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        private string $targetDirectoryPicture,
        private string $targetDirectoryVideo,
        private SluggerInterface $slugger,
    ) {
    }

    public function upload(?UploadedFile $file, string $targetFolder, $prefix = ''): ?string
    {
        $fileName = null;

        if (null !== $file) {
            // Créer le nom du fichier
            $fileName = $prefix . '-' . uniqid() . '.' . $file->guessExtension();
            
            $file->move(
                $targetFolder,
                $fileName,
            );
        }
        return $fileName;
    }

    public function getTargetDirectoryPicture(?UploadedFile $file, Trick $trick): string
    {
        $pictureName = $this->upload($file, $this->targetDirectoryPicture, 'trick-picture');
        if ($pictureName !== null) {
            $trick->setPicture($pictureName);
        } else {
            $pictureName = 'snowboard-home.png';
            $trick->setPicture($pictureName);
        }
        return $this->targetDirectoryPicture;
    }

    public function getTargetDirectoryVideo(?UploadedFile $file, Trick $trick): string
    {        
        $videoName = $this->upload($file, $this->targetDirectoryVideo, 'trick-video');
        if ($videoName !== null) {
            $trick->setvideo($videoName);
        } else {
            $videoName = __DIR__ . '/../public/image/snowboard-home.png';
  
        }
        return $this->targetDirectoryVideo;
    }
}

