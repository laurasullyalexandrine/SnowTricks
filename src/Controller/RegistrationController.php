<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use App\Service\GenerateToken;
use App\Service\SendMailService;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private SendMailService $mail,
        private GenerateToken $generateToken,
        private EntityManagerInterface $manager)
    {}

    #[Route('/inscription', name: 'app_register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );
            $token = $this->generateToken->generateToken();
      
            $user->setToken($token)
                ->setTokenCreatedAt(new DateTimeImmutable());

            $this->manager->persist($user);
            $this->manager->flush();

            // Create email confirmation url
            $host = $request->server->get("HTTP_HOST");
            $scheme = array_key_exists("HTTPS", $_SERVER) ? "https" : "http";
            $verifyUrl = "$scheme://$host/verification-email/$token";
       
            // do anything else you need here, like send an email
            $this->mail->send(
                'no-reply@snowtricks.fr',
                $user->getEmail(),
                'Activation de compte',
                'activation-account',
                compact('user', 'verifyUrl')
            );

            return $this->redirectToRoute('confirmation_mail_sent');
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/mail-de-confirmation-envoye', name: 'confirmation_mail_sent')]
    public function mailSent(): Response 
    {
        $this->addFlash('primary', "Ton compte n'est pas encore activé. Vérifies tes mails et utilise le lien pour validé ton email.");
        return $this->render('registration/mail_sent.html.twig');
    }

    #[Route('/verification-email/{token}', name: 'verify_email')]
    public function verifyEmail(
        string $token,
        Request $request,
        UserAuthenticatorInterface $userAuthenticator, 
        LoginFormAuthenticator $authenticator): Response
    {
        try {
            // Stocker la date du jour et l'heure de l'activation de l'url de vérification
            $now = date_create();
            // Retrouver le user depuis le token générer lors de l'inscription
            $user = $this->manager->getRepository(User::class)->findOneByToken($token);
    
            if (!$user) {
                throw new \Exception('Aucun utilisateur associè à ce token! Essayes de te connecter.');
            }
    
            // Récupérer la date de création du token
            $tokenDate = $user->getTokenCreatedAt();
    
            // Créer la date d'expiration du token
            $tokenExpirationDate = $tokenDate->modify('+3 hour');
          
            // Si la date de création du token est suppérieur à la date de validité
            if ($now > $tokenExpirationDate) {
                throw new \Exception('Ce token a expiré! Cliques sur lien pour recevoir un nouvel email de validation.');
                $this->resendMail(
                    $user,
                    $request
                );
            }
    
            $user->setIsVerified(true);
            $this->manager->persist($user);
            $this->manager->flush();

            $this->addFlash('success', 'Ton compte est maintenant validé. Bienvenue sur SnowTricks!');
            return $this->redirectToRoute('home',[
                $userAuthenticator->authenticateUser(
                    $user,
                    $authenticator,
                    $request
                )
            ]);

        } catch (\Exception $e) {
            $this->addFlash('danger', $e->getMessage());
        }

        return $this->render('/registration/registration_error.html.twig', [
            'user' => $user
        ]);
    }

    #[Route('renvoyer-mail-validation/{user_name}', name: 'resend_mail')]
    public function resendMail(
        #[MapEntity(mapping: ['user_name' => 'name'])] User $user,
        Request $request)
    {
        // dd($request);
        $token = $this->generateToken->generateToken();
      
        $user->setToken($token)
            ->setTokenCreatedAt(new DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();

        // Create email confirmation url
        $host = $request->server->get("HTTP_HOST");
        $scheme = array_key_exists("HTTPS", $_SERVER) ? "https" : "http";
        $verifyUrl = "$scheme://$host/verification-email/$token";
   
        // do anything else you need here, like send an email
        $this->mail->send(
            'no-reply@snowtricks.fr',
            $user->getEmail(),
            'Activation de compte',
            'activation-account',
            compact('user', 'verifyUrl')
        );

        return $this->redirectToRoute('confirmation_mail_sent');
    }
}
