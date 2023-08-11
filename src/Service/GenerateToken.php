<?php

namespace App\Service;

use DateTimeImmutable;
use App\Repository\UserRepository;

class GenerateToken
{
    public function __construct(
        private UserRepository $userRepository
    ) {}

    public function generateToken(): string
    {
        $token =  bin2hex(random_bytes(32));

        return $token;
    }
}
