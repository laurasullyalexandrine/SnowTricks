<?php

namespace App\Service;

use DateTimeImmutable;
use App\Repository\UserRepository;

class GenerateToken
{
    public function __construct(
        private UserRepository $userRepository
    ) {
    }

    public function generateToken(): string
    {
        $token =  bin2hex(random_bytes(32));

        return $token;
    }

    public function isValid(string $token): bool
    {
        return preg_match(
            '/^[a-zA-Z0-9\-\_\=]+$/', $token) === 1;
    }

    public function isExpired(string $token)
    {
        $arrayToken = explode('=', $token);
        $validity = $arrayToken[1];
 
        $now = new DateTimeImmutable();
        
        return $validity < $now->getTimestamp();
    }

}
