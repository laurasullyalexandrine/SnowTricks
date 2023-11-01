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
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    public function __construct(
        private TrickRepository $trickRepository,
        private CommentRepository $commentRepository,
        private EntityManagerInterface $manager,
        private MediaRepository $imageRepository
    ) {
    }

    #[Route('/figure-de-snowboard/{slug}', name: 'trick_slug', methods: ['GET', 'POST'])]
    public function trick(
        Trick $trick,
        Request $request
    ): Response {
        // Find page number from url
        $page = $request->query->getInt('page', 1);
        // Retrieve figure comments
        $comments = $this->commentRepository->findCommentsPaginated($trick, $page);

        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                // Check if user is logged in ...
                if (!$this->getUser()) {
                    // ... Redirect to login page
                    throw $this->createNotFoundException('Merci de te connecter.');
                    return $this->redirectToRoute('login');
                }

                $content = $form->get('content')->getData();
                $comment->setContent($content)
                    ->setStatus(Comment::STATUS_WAITING)
                    ->setCreatedAt(new \DateTimeImmutable())
                    ->setUser($this->getUser())
                    ->setTrick($trick);

                $this->manager->persist($comment);
                $this->manager->flush();

                $this->addFlash('success', 'Ton commentaire est enregistré. Il est en cours de validation.');
                return $this->redirectToRoute('home');
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Ta commentaire n\'a pas pu être enregistré. <br>' . $e->getMessage());
            }
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->render('front/trick/trick.html.twig', [
            'trick' => $trick,
            'slug' => $trick->getSlug(),
            'form' => $form->createView(),
            'comments' => $comments,
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
                // Save the user connected to the figure being created
                $trick->setUser($this->getUser());

                // Retrieve images submitted from the form
                $medias = $form->get('medias')->getData();

                // Add the images
                foreach ($medias as $media) {
                    $media->setTrick($trick);
                    $this->manager->persist($media);
                }

                $this->manager->persist($trick);

                $this->manager->flush();

                $this->addFlash('success', 'Ta figure a été créée.');
                return $this->redirectToRoute('home');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Une erreur est survenue lors de la création de ta figure erreur : ' . $e->getMessage());
                return $this->redirect($request->headers->get('referer'));
            }
        }
        return $this->render('front/trick/create.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }


    #[Route('/modification-figure-de-snowboard/{slug}', name: 'trick_edit', methods: ['GET', 'POST'])]
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

        if ($form->isSubmitted() && $form->isValid()) {
            dd($_FILES);
            try {
                $trick = $form->getData();
                $trick->setUpdatedAt(new \DateTimeImmutable());
                
                $this->manager->persist($trick);
                $this->manager->flush();

                $this->addFlash('success', 'Ta figure ' . $trick->getName() . ' a été modifiée.');
                return $this->redirectToRoute('trick_slug', [
                    'slug' => $trick->getSlug(),
                ]);
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Une erreur s\'est produite lors de la modification de ta figure de snowboard ' . $trick->getName() . ' ' . $e->getMessage());
            }
        }

        return $this->render('front/trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }


    #[Route('/suppression-media/{slug}/{media_id}', name: 'trick_media_delete', methods: ['GET', 'POST'])]
    public function deleteMedia(
        #[MapEntity(mapping: ['media_id' => 'id'])] Media $media,
        Trick $trick,
        Request $request
    ): Response {
        try {
            $trick->removeMedia($media);
    
            $this->manager->persist($trick);
            $this->manager->flush();
    
            $this->addFlash('success', 'Ton media ' . $media->getId() . ' été supprimé.');
        } catch (\Exception $e) {
            $this->addFlash('warning', 'Une erreur s\'est produite lors de la suppression du media de ta figure de snowboard ' . $trick->getName() . ' ' . $e->getMessage());
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->redirect($request->headers->get('referer'));
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

        try {
            if ($this->isCsrfTokenValid('delete', $request->request->get('_token'))) {
                $this->manager->remove($trick);
                $this->manager->flush();

                $this->addFlash('success', 'Ta figure de snowboard ' . $trick->getName() . ' a été supprimée.');
                return $this->redirectToRoute('home');
            }
        } catch (\Exception $e) {
            $this->addFlash('warning', 'Une erreur s\'est produite lors de la suppression de ta figure de snowboard ' . $trick->getName() . ' ' . $e->getMessage());
            return $this->redirect($request->headers->get('referer'));
        }
    }
}
