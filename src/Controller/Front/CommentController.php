<?php

namespace App\Controller\Front;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CommentController extends AbstractController
{
    #[Route('/les-commentaires', name: 'comments', methods: ['GET'])]
    public function comments(): Response
    {
        return $this->render('comment/comments.html.twig');
    }

    #[Route('/ajouter-un-commentaire', name: 'comment_new', methods: ['GET', 'POST'])]
    public function new(): Response
    {
        return $this->render('comment/new-edit.html.twig');
    }

    #[Route('/commentaire/{id}', name: 'comment', methods: ['GET'])]
    public function comment(): Response
    {
        return $this->render('comment/comment.html.twig');
    }

    #[Route('/modifier-un-commentaire', name: 'comment_edit', methods: ['GET', 'POST'])]
    public function edit(): Response
    {
        return $this->render('comment/new-edit.html.twig');
    }

    #[Route('/supprimer-un-commentaire', name: 'comment_delete', methods: ['POST', 'DELETE'])]
    public function delete(): Response
    {
        return $this->render('comment/new-edit.html.twig');
    }
}
