<?php

// src/Service/FileUploader.php
namespace App\Service;

use App\Entity\Image;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;

class FileUploader
{
    public function __construct(
        private string $targetDirectoryImage,
        private string $targetDirectoryVideo,
        private EntityManagerInterface $manager
    ) {
    }

    public function upload(?UploadedFile $file, string $targetFolder, $prefix = ''): ?string
    {
        $fileName = null;
  
        // Create the name file
        $fileName = $prefix . '-' . uniqid() . '.' . $file->guessExtension();
 
        $file->move(
                $targetFolder,
                $fileName,
        );
        
        return $fileName;
    }

    public function getTargetDirectoryImage(?UploadedFile $file, Image $image): void
    {
        if ($file !== null) {

            $imageName = $this->upload($file, $this->targetDirectoryImage, 'trick-image');
           
            if ($imageName !== null) {
                $image->setName($imageName);
                $this->manager->persist($image);
            }
        }
    }
}

