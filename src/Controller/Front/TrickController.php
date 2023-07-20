<?php

namespace App\Controller\Front;

use App\Entity\Trick;
use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    public function __construct(private TrickRepository $trickRepository)
    {}

    #[Route('/figure/{slug}', name: 'trick_slug', methods:['GET'])]
    public function read(
        Trick $trick): Response
    {
        return $this->render('front/trick/read.html.twig', [
            'trick' => $trick,
            'slug' => $trick->getSlug(),
        ]);
    }
}
