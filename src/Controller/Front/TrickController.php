<?php

namespace App\Controller\Front;

use App\Entity\Trick;
use App\Entity\Comment;
use App\Form\TrickType;
use App\Form\CommentType;
use App\Security\Voter\TrickVoter;
use App\Repository\ImageRepository;
use App\Repository\TrickRepository;
use App\Repository\CommentRepository;
use App\Repository\VideoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TrickController extends AbstractController
{
    public function __construct(
        private TrickRepository $trickRepository,
        private CommentRepository $commentRepository,
        private EntityManagerInterface $manager,
        private ImageRepository $imageRepository,

    ) {
    }

    #[IsGranted('ROLE_USER')]
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
                $images = $form->get('images')->getData();
                // Add the images
                foreach ($images as $image) {
                    $image->setTrick($trick);
                    $this->manager->persist($image);
                }

                // Retrieve videos submitted from the form
                $videos = $form->get('videos')->getData();

                // Add the videos
                foreach ($videos as $video) {
                    $video->setTrick($trick);
                    $this->manager->persist($video);
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

    #[IsGranted('ROLE_USER')]
    #[Route('/modification-figure-de-snowboard/{slug}', name: 'trick_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Trick $trick
    ): Response {

        if (!$this->getUser()) {
            throw $this->createNotFoundException('Aucun utilisateur connecté.');
            return $this->redirectToRoute('login');
        }

        $this->denyAccessUnlessGranted(TrickVoter::EDIT, $trick);

        $form = $this->createForm(TrickType::class, $trick);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            try {
                $datasRequest = $request->request->all();

                // Retrieve the form's uploadeFile object
                $files = $request->files;
                // Retrieve submitted files
                $uploadedFiles = array_filter($files->all());

                /** @var Trick $trick */
                $trick = $form->getData();
                $trick->setUpdatedAt(new \DateTimeImmutable());

                // Compare the ID of the video and that in the database
                foreach ($datasRequest as $keys => $data) {
                    $videoId = (int) str_replace('video_edit_', '', $keys);
                    foreach ($trick->getVideos() as $video) {
                        if ($videoId === $video->getId()) {

                            if ((!preg_match('/^<iframe/', $data) && (!preg_match('/^<embed/', $data)))) {
                                $message = 'Ce lien n’est pas valide ' . '"'. $data . '"' . '. Merci de vérifier qu’il commence bien par <embed ou <iframe.';
                                $this->addFlash('danger', $message);
                                return $this->redirect($request->headers->get('referer'));
                            }
                            $video->setName($data);
                            break;
                        }
                    }
                }

                // Compare the ID of the uploaded image and that in the database
                foreach ($uploadedFiles as $key => $uploadedFile) {
                    $imageId = (int) str_replace('image_edit_', '', $key);
                    $imageFound = false;
                    foreach ($trick->getImages() as $image) {
                        if ($imageId === $image->getId()) {
                            $imageFound = true;
                            $image->update($uploadedFile);
                            break;
                        }
                    }
                    if (!$imageFound) {
                        throw new \Exception('Media introuvable.');
                    }
                }

                $this->manager->persist($trick);
                $this->manager->flush();

                $this->addFlash('success', 'Ta figure ' . $trick->getName() . ' a été modifiée.');
                return $this->redirect($request->headers->get('referer'));
            } catch (\Exception $e) {
                $this->addFlash('warning', 'Une erreur s\'est produite lors de la modification de ta figure de snowboard ' . $trick->getName() . ' ' . $e->getMessage());
            }
        }

        return $this->render('front/trick/edit.html.twig', [
            'form' => $form->createView(),
            'trick' => $trick,
        ]);
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/suppression-media/{slug}/{type}/{media_id}', name: 'trick_media_delete', methods: ['GET', 'POST'])]
    public function deleteMedia(
        string $type,
        int $media_id,
        ImageRepository $imageRepository,
        VideoRepository $videoRepository,
        Trick $trick,
        Request $request
    ): Response {
        try {
            if ($type === 'image') {
                $media = $imageRepository->findOneById($media_id);
                $trick->removeImage($media);
            } elseif ($type === 'video') {
                $media = $videoRepository->findOneById($media_id);
                $trick->removeVideo($media);
            }

            $this->manager->persist($trick);
            $this->manager->flush();

            $this->addFlash('success', 'Ton media ' . $media->getId() . ' a été supprimé.');
        } catch (\Exception $e) {
            $this->addFlash('warning', 'Une erreur s\'est produite lors de la suppression du image de ta figure de snowboard ' . $trick->getName() . ' ' . $e->getMessage());
            return $this->redirect($request->headers->get('referer'));
        }

        return $this->redirect($request->headers->get('referer'));
    }


    #[IsGranted('ROLE_USER')]
    #[Route('/supprimer-la-figure-de-snowboard/{slug}', name: 'trick_delete', methods: ['POST', 'DELETE'])]
    public function delete(
        Request $request,
        Trick $trick
    ): Response {
        if (!$this->getUser()) {
            throw $this->createNotFoundException('Aucun utilisateur connecté.');
            return $this->redirectToRoute('login');
        }

        $this->denyAccessUnlessGranted(TrickVoter::DELETE, $trick);

        try {
            if (!$trick) {
                throw $this->createNotFoundException('Figure non trouvée.');
            }

            $this->manager->remove($trick);
            $this->manager->flush();

            $this->addFlash('success', 'Ta figure de snowboard ' . $trick->getName() . ' a été supprimée.');
            return $this->redirectToRoute('home');
        } catch (\Exception $e) {
            $this->addFlash('warning', 'Une erreur s\'est produite lors de la suppression de ta figure de snowboard ' . $trick->getName() . ' ' . $e->getMessage());
            return $this->redirect($request->headers->get('referer'));
        }
    }
}
