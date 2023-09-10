<?php

namespace App\Controller\Front;

use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\ImageRepository;
use App\Service\FileUploader;
use App\Repository\TrickRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    public function __construct(
        private TrickRepository $trickRepository,
        private EntityManagerInterface $manager,
        private FileUploader $fileUploader,
        private ImageRepository $imageRepository
    ) {
    }

    #[Route('/figure/{slug}', name: 'trick_slug', methods: ['GET'])]
    public function read(
        Trick $trick
    ): Response {

        return $this->render('front/trick/read.html.twig', [
            'trick' => $trick,
            'slug' => $trick->getSlug(),
        ]);
    }

    #[Route('/nouvelle-figure', name: 'trick_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        $trick = new Trick();
        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
     
            $trick->setUser($this->getUser());

            $images = $form->get('images')->getData();
           
            foreach ($images as $image){
               $this->manager->persist($image);
;            }
           

            $this->manager->flush();

            $this->addFlash('success', 'Ta figure a été créée.');
            return $this->redirectToRoute('home');
        }
        return $this->render('front/trick/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
