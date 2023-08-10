<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\SendMailService;
use App\Form\RegistrationFormType;
use App\Repository\UserRepository;
use App\Security\LoginFormAuthenticator;
use App\Service\GenerateToken;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Http\Authentication\UserAuthenticatorInterface;

class RegistrationController extends AbstractController
{
    public function __construct(
        private SendMailService $mail,
        private GenerateToken $generateToken,
        private EntityManagerInterface $manager)
    {
        
    }

    #[Route('/inscription', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, UserAuthenticatorInterface $userAuthenticator, LoginFormAuthenticator $authenticator, EntityManagerInterface $entityManager): Response
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
      
            $user->setToken($token);

            $entityManager->persist($user);
            $entityManager->flush();

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

            return $userAuthenticator->authenticateUser(
                $user,
                $authenticator,
                $request
            );
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }

    #[Route('/verification-email/{token}', name: 'verify_email')]
    public function verifyEmail(string $token)
    {
        try {
            $now = date_create();
            $user = $this->manager->getRepository(User::class)->findOneByToken($token);
    
            if (!$user) {
                throw new \Exception('Aucun utilisateur associè à ce token');
            }
    
            $tokenDate = $user->getTokenCreatedAt();
    
            $tokenExpirationDate = $tokenDate->modify('+3 hour');
    
            if ($now > $tokenExpirationDate) {
                throw new \Exception('Ce token a expiré');
            }
    
            $user->setIsVerify(true);
        } catch (\Exception $e) {
            // TODO: Message erreur 
            dd($e);
        }

    }

}
