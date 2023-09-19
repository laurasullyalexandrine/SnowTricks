<?php

namespace App\Controller\Front;

use App\Entity\Image;
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

            try {
                // Enregistrer le user connecter à la figure en cours de création
                $trick->setUser($this->getUser());
                
                // Récupérer les images soumises depuis le formulaire
                $images = $form->get('images')->getData();

                // Si tableau d'image est vide créer l'image par défaut
                if (empty($images)) {
                    $image = new Image();

                    $publicDirectory = realpath($this->getParameter('kernel.project_dir') . '/public');
                  
                    $defaultFile = $publicDirectory . '/image/snowboard-home.png';

                    $tempFile = realpath($this->getParameter('kernel.project_dir') . '/var/temp') . '/' .  uniqid() . '.jpg';
           
                    copy($defaultFile, $tempFile);

                    $image->setName($tempFile)
                        ->setTrick($trick);
    
                        // dd($image);
                    $this->manager->persist($image);
                }

                foreach ($images as $image) {
                    $image->setTrick($trick);
    
                    $this->manager->persist($image);
                }
                $this->manager->persist($trick);
    
                $this->manager->flush();
    
                $this->addFlash('success', 'Ta figure a été créée.');
                return $this->redirectToRoute('home');

            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création de la figure erreur : ' . $e->getMessage());
            }
        }
        return $this->render('front/trick/create.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
