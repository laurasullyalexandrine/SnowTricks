<?php

// src/Service/FileUploader.php
namespace App\Service;

use App\Entity\User;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        private string $targetDirectoryAvatar,
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

    public function getTargetDirectoryPicture(?UploadedFile $file, User $user): string
    {
        $pictureName = $this->upload($file, $this->targetDirectoryAvatar, 'user-avatar');
        if ($pictureName !== null) {
            $user->setAvatar($pictureName);
        } 
        
        return $this->targetDirectoryAvatar;
    }
}