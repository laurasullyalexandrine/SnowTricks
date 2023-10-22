<?php

namespace App\Twig\Runtime;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Twig\Extension\RuntimeExtensionInterface;

class AppExtensionRuntime implements RuntimeExtensionInterface
{
    public function __construct(private TrickRepository $trickRepository)
    {
        // Inject dependencies if needed
    }

    public function oneTrick(): Trick
    {
        $tricks = $this->trickRepository->findAll();

        foreach ($tricks as $trick)
        
        return $trick;
    }
}
