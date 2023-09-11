<?php

namespace App\Controller\Front;

use App\Form\RequestPasswordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ResetPasswordController extends AbstractController
{
    #[Route('/reset/password', name: 'reset_password')]
    public function reset(Request $request): Response
    {
        $form = $this->createForm(RequestPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Créer un service pour :
                // Vérifier qu'il existe des utilisateurs en bdd
                // Vérifier le nom de l'utilisateur existe en bdd

                // Si il existe envoyer l'email
                    // Ajouter le service pour envoyer l'email de réinitialisation.
        }

        return $this->render('front/reset_password/reset.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
