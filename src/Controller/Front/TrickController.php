<?php

namespace App\Controller\Front;

use App\Entity\Image;
use App\Entity\Trick;
use App\Form\TrickType;
use App\Repository\ImageRepository;
use App\Service\FileUploader;
use App\Repository\TrickRepository;
use App\Security\Voter\TrickVoter;
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

    #[Route('/figure-de-snowboard/{slug}', name: 'trick_slug', methods: ['GET'])]
    public function read(
        Trick $trick
    ): Response {

        return $this->render('front/trick/read.html.twig', [
            'trick' => $trick,
            'slug' => $trick->getSlug(),
        ]);
    }

    #[Route('/nouvelle-figure-de-snowboard', name: 'trick_create', methods: ['GET', 'POST'])]
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

                // Récupérer les videos soumises depuis le formulaire
                $videos = $form->get('videos')->getData();

                // Si tableau d'image est vide créer l'image par défaut
                if (empty($images)) {
                    $image = new Image();

                    $publicDirectory = realpath($this->getParameter('kernel.project_dir') . '/public');

                    $defaultFile = $publicDirectory . '/image/snowboard-home.png';

                    $tempFile = realpath($this->getParameter('kernel.project_dir') . '/var/temp') . '/' .  uniqid() . '.jpg';

                    copy($defaultFile, $tempFile);

                    $image->setName($tempFile)
                        ->setTrick($trick);

                    $this->manager->persist($image);
                }

                foreach ($images as $image) {
                    $image->setTrick($trick);

                    $this->manager->persist($image);
                }

                foreach ($videos as $video) {
                    $video->setTrick($trick);

                    $this->manager->persist($video);
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

    #[Route('/suppression-de-la-figure-de-snowboard/{slug}', name: 'trick_delete', methods: ['POST', 'DELETE'])]
    public function delete(
        Request $request,
        Trick $trick): Response
    {
        if (!$this->getUser()) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas.');
            return $this->redirectToRoute('login');
        }
        // TODO: voter
        $this->denyAccessUnlessGranted(TrickVoter::DELETE, $trick);

        if ($this->isCsrfTokenValid('delete' . $trick->getSlug(), $request->request->get('_token'))) {
            $this->manager->remove($trick);
            $this->manager->flush();
        }

        $this->addFlash('success', 'Ta figure de snowboard ' . $trick->getName() . ' a été supprimée.');
        return $this->redirectToRoute('home');
    }
}
