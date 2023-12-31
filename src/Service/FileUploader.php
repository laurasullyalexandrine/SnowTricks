<?php

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

    public function upload(?UploadedFile $file, string $targetFolder, string $prefix = ''): ?string
    {
        $fileName = null;

        if (null !== $file) {
            // Create file name
            $fileName = $prefix . '-' . uniqid() . '.' . $file->guessExtension();
            
            $file->move(
                $targetFolder,
                $fileName,
            );
        }
        return $fileName;
    }

    public function getTargetDirectoryAvatar(?UploadedFile $file, User $user): string
    {
        $avatarName = $this->upload($file, $this->targetDirectoryAvatar, 'user-avatar');
        if ($avatarName !== null) {
            $user->setAvatar($avatarName);
        } 

        return $this->targetDirectoryAvatar;
    }
}
