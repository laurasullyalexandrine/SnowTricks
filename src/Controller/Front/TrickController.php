<?php

namespace App\Controller\Front;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    public function __construct(private TrickRepository $trickRepository)
    {}

    #[Route('/figures', name: 'app_trick')]
    public function allTrick(): Response
    {
        return $this->render('front/trick/tricks.html.twig', [
            'tricks' => $this->trickRepository->findAll(),
        ]);
    }
}
