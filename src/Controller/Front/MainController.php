<?php

namespace App\Controller\Front;

use App\Repository\TrickRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class MainController extends AbstractController
{
    public function __construct(private TrickRepository $trickRepository)
    {}
    #[Route('/', name: 'home', methods:['GET'])]
    public function home(): Response
    {
        return $this->render('front/main/home.html.twig', [
            'tricks' => $this->trickRepository->getAllTricksByOrderAsc(),
        ]);
    }
}
