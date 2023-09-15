<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\ResetPasswordType;
use App\Service\SendMailService;
use App\Form\RequestPasswordType;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class ResetPasswordController extends AbstractController
{
    public function __construct(
        private UserRepository $userRepository,
        private SendMailService $mail,
        private TokenGeneratorInterface $tokenGenerator,
        private EntityManagerInterface $manager,
        private UserPasswordHasherInterface $hasher,
    ) {
    }

    #[Route('/demande-reinitialisation-mot-de-passe', name: 'request_password', methods: ['GET', 'POST'])]
    public function requestPassword(Request $request): Response
    {
        $form = $this->createForm(RequestPasswordType::class);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Vérifier qu'il existe des utilisateurs en bdd
            $name = $form->get('name')->getData();

            // Vérifier le nom de l'utilisateur existe en bdd
            $user = $this->userRepository->findOneByName($name);

            try {
                if (!$user instanceof User) {
                    throw new \Exception("Cet utilisateur n'existe pas!");
                }

                // Créer le token 
                $token = $this->tokenGenerator->generateToken();
       
                $user->setToken($token)
                    ->setTokenCreatedAt(new \DateTimeImmutable());

                $this->manager->persist($user);
                $this->manager->flush();

                // Générer l'url de réinitialisation
                $url = $this->generateUrl('reset_password', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);

                // Si il existe envoyer l'email
                // Ajouter le service pour envoyer l'email de réinitialisation.
                $this->mail->send(
                    'no-reply@snowtricks.fr',
                    $user->getEmail(),
                    'Réinitialisation du mot de passe',
                    'mail_reset_password',
                    compact('user', 'url')
                );
                $this->addFlash('success', "L'email de réinitialisation a été envoyé.");
            } catch (\Exception $e) {
                if ($e->getCode() !== 0) {
                    $this->addFlash('danger', "L'email n'a pas été envoyé. Merci de refaire une demande. Si tu as reçu le premier n'en tiens pas compte.");
                } else {
                    $this->addFlash('danger', $e->getMessage());
                }
            }
        }

        return $this->render('front/password/request_reset_password.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    #[Route('/reinitialisation-mot-de-passe/{token}', name: 'reset_password', methods: ['GET', 'POST'])]
    public function resetPassword(
        string $token,
        Request $request,
    ): Response {
        // On vérifie si le token existe en base de données
        $user = $this->userRepository->findOneByToken($token);

        if ($user) {
            $form = $this->createForm(ResetPasswordType::class);
            $form->handleRequest($request);

            if ($form->isSubmitted() && $form->isValid()) {
                $user->setToken('')
                    ->setPassword(
                        $this->hasher->hashPassword(
                            $user,
                            $form->get('password')->getData()
                        )
                    );
                $this->manager->persist($user);
                $this->manager->flush();

                $this->addFlash('success', "Ton mot de passe a été modifié");
                return $this->redirectToRoute('login');
            }

            return $this->render('front/password/password_reset.html.twig', [
                'form' => $form->createView()
            ]);
        }

        // Si le token n'existe pas informer et renvoyer à la page de connexion
        $this->addFlash('danger', 'Token invalide');
        return $this->redirectToRoute('login');
    }
}
