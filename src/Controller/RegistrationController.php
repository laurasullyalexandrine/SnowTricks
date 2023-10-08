<?php

namespace App\Controller;

use App\Entity\User;
use DateTimeImmutable;
use App\Service\SendMailService;
use App\Form\RegistrationFormType;
use App\Security\LoginFormAuthenticator;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Attribute\MapEntity;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private SendMailService $mail,
        private TokenGeneratorInterface $tokenGenerator,
        private EntityManagerInterface $manager)
    {}

    #[Route('/inscription', name: 'register')]
    public function register(
        Request $request, 
        UserPasswordHasherInterface $userPasswordHasher,
        FileUploader $fileUploader): Response
    {
        $user = new User();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // encode the plain password
            $avatar = $form->get('avatar')->getData();
    
            $fileUploader->getTargetDirectoryAvatar($avatar, $user);
            
            $user->setPassword(
                $userPasswordHasher->hashPassword(
                    $user,
                    $form->get('plainPassword')->getData()
                )
            );

            $token = $this->tokenGenerator->generateToken();
      
            $user->setToken($token)
                ->setTokenCreatedAt(new DateTimeImmutable());

            $this->manager->persist($user);
            $this->manager->flush();

           $verifyUrl = $this->generateUrl('verify_email', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
       
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
        return $this->redirectToRoute('home');
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
        $token = $this->tokenGenerator->generateToken();
      
        $user->setToken($token)
            ->setTokenCreatedAt(new \DateTimeImmutable());

        $this->manager->persist($user);
        $this->manager->flush();

        // Create email confirmation url
        $verifyUrl = $this->generateUrl('verify_email', ['token' => $token], UrlGeneratorInterface::ABSOLUTE_URL);
   
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
