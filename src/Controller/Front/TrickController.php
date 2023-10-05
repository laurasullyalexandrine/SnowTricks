<?php

namespace App\Controller\Front;

use App\Entity\Media;
use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Security\Voter\TrickVoter;
use App\Repository\MediaRepository;
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
        private MediaRepository $imageRepository
    ) {
    }

    #[Route('/figure-de-snowboard/{slug}', name: 'trick_slug', methods: ['GET', 'POST'])]
    public function trickNewComment(
        Trick $trick,
        Request $request
    ): Response {

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            // Contrôler si utilisateur connecté ...
            if (!$this->getUser()) {
                // ... Rediriger vers la page de connexion
                throw $this->createNotFoundException('Merci de te connecter.');
                return $this->redirectToRoute('login');
            }

            $content = $form->get('content')->getData();
            $comment->setContent($content)
                ->setStatus(Comment::STATUS_WAITING)
                ->setCreatedAt(new \DateTimeImmutable())
                ->setUsers($this->getUser())
                ->setTrick($trick);

            // dd($comment);
            $this->manager->persist($comment);
            $this->manager->flush();

            if (!$comment) {
                $this->addFlash('warning', 'Ton commenaitre n\'a pas pu être enregistré.');
                return $this->redirect($request->headers->get('referer'));
            } else {
                $this->addFlash('success', 'Ton commentaire est enregistré. Il est en cours de validation.');
                return $this->redirect($request->headers->get('referer'));
            }
        }

        return $this->render('front/trick/trick.html.twig', [
            'trick' => $trick,
            'slug' => $trick->getSlug(),
            'form' => $form->createView(),
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
                $medias = $form->get('medias')->getData();

                // Récupérer les videos soumises depuis le formulaire
                // $videos = $form->get('videos')->getData();

                // Si tableau d'image est vide créer l'image par défaut
                if (empty($medias)) {
                    $media = new Media();
                    // Récupérer le chemin racine du projet et descendre au dossier public
                    $publicDirectory = realpath($this->getParameter('kernel.project_dir') . '/public');

                    // Récupérer chemin de l'image par défaut
                    $defaultFile = $publicDirectory . '/image/snowboard-home.png';

                    // Faire une copie du fichier dans le dossier temporaire
                    $tempFile = realpath($this->getParameter('kernel.project_dir') . '/var/temp') . '/' .  uniqid() . '.jpg';
                    copy($defaultFile, $tempFile);

                    $media->setName($tempFile)
                        ->setTrick($trick);

                    $this->manager->persist($media);
                }

                // Ajouter les images
                foreach ($medias as $media) {
                    $media->setTrick($trick);
                    $this->manager->persist($media);
                }

                // Ajouter les images
                // foreach ($videos as $video) {
                //     $video->setTrick($trick);
                //     $this->manager->persist($video);
                // }

                $this->manager->persist($trick);

                $this->manager->flush();

                $this->addFlash('success', 'Ta figure a été créée.');
                return $this->redirectToRoute('home');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création de la figure erreur : ' . $e->getMessage());
            }
        }
        return $this->render('front/trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    #[Route('/edition-figure-de-snowboard/{slug}', name: 'trick_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Trick $trick
    ): Response {
        if (!$this->getUser()) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas.');
            return $this->redirectToRoute('login');
        }

        $this->denyAccessUnlessGranted(TrickVoter::EDIT, $trick);

        $form = $this->createForm(TrickType::class, $trick);

        $form->handleRequest($request);
        return $this->render('front/trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }

    #[Route('/supprimer-la-figure-de-snowboard/{slug}', name: 'trick_delete', methods: ['POST', 'DELETE'])]
    public function delete(
        Request $request,
        Trick $trick
    ): Response {
        if (!$this->getUser()) {
            throw $this->createNotFoundException('Cet utilisateur n\'existe pas.');
            return $this->redirectToRoute('login');
        }

        $this->denyAccessUnlessGranted(TrickVoter::DELETE, $trick);

        if ($this->isCsrfTokenValid('delete' . $trick->getSlug(), $request->request->get('_token'))) {
            $this->manager->remove($trick);
            $this->manager->flush();
        }

        $this->addFlash('success', 'Ta figure de snowboard ' . $trick->getName() . ' a été supprimée.');
        return $this->redirectToRoute('home');
    }
}
