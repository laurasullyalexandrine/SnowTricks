<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\User;
use App\Entity\Image;
use App\Entity\Trick;
use App\Entity\Trickgroup;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\Finder\Finder;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class AppFixtures extends Fixture
{
    public function __construct(
        private SluggerInterface $slugger,
        private UserPasswordHasherInterface $hasher,
        private TokenGeneratorInterface $tokenGenerator,
    ) {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

        $finder = new Finder();
        $tempImage = iterator_to_array($finder->in(__DIR__ . '/../../var/temp'));
 

        // TricksGroup array
        $tricksGroupsArray = [
            'Butter Trick',
            'Grabs',
            'Flips',
            'Rail/Boxes',
            'Rail',
            'Corks',
            'Spins',
        ];

        //  Tricks array
        $tricksArray = [
            1 => [
                'name' => 'Ollie Nollie',
                'description' => "Nous avons parlé du Ollie ci-dessus, le Nollie c'est l'inverse. Accroupis-toi, déplace ton poids vers l'avant, puis utilise le nez de ta planche pour sauter.",
            ],
            2 => [
                'name' => 'Tail Press',
                'description' => "Le Tail Press est initié en déplaçant ton poids vers l'arrière de ta planche tout en restant droit et en soulevant le Nose de la neige.",
            ],
            3 => [
                'name' => 'Indy',
                'description' => 'Attrape le carre des orteils de ta planche, entre les fixations, avec ta main arrière.',
            ],
            4 => [
                'name' => 'Stalefish',
                'description' => 'Passe la main derrière ton genou arrière et attrape le carre de ta planche entre les fixations, côté talon, avec ta main arrière.',
            ],
            5 => [
                'name' => 'Tail',
                'description' => "Attrape le talon de ta planche avec ta main arrière (juste à l'extrémité, pas sur les côtés).",
            ],
            6 => [
                'name' => 'Wildcat',
                'description' => 'Un Wildcat est un Backflip qui garde la planche parallèle à la trajectoire, tu fais donc une sorte de Flip "latéral" sans perte de vitesse.',
            ],
            7 => [
                'name' => 'Tamedog',
                'description' => "L'exact opposé d'un Wildcat est un Tamedog. C'est un Frontflip qui garde la planche parallèle à la trajectoire. Un hard Nollie utilise le nez comme tremplin pour amorcer la rotation.",
            ],
            8 => [
                'name' => '50-50',
                'description' => "Il s'agit de chevaucher un rail ou un box avec ta planche en ligne droite sur la structure.",
            ],
            9 => [
                'name' => 'Frontside Boardslide',
                'description' => "Il s'agit de glisser jusqu'au rail sur ton côté arrière, puis de sauter dessus avec le nez de la planche au-dessus du rail. Tu atterris avec le rail entre tes fixations, ta planche perpendiculaire à la structure.",
            ],
            10 => [
                'name' => 'Frontside Lipslide',
                'description' => " Identique à la figure précédente, mais tu te diriges vers le rail en le positionnant sur ton côté avant. Tu sautes ensuite avec le talon de la planche au-dessus du rail et tu atterris dessus avec le rail entre tes fixations.",
            ],
        ];

        $emailData = [
            'sophie@sfr.fr',
            'pierre@orange.fr',
            'julie@bouygues.com',
            'nicolas@hotmail.com',
            'maryse@soch.net',
            'dominique@outlook.fr',
            'capucine@gmail.com',
            'auguste@voila.fr',
            'anais@likos.net',
            'joseph@gmail.com',
        ];

        // Tricks Group
        $trickGroupsToAdd = [];
        foreach ($tricksGroupsArray as $trickGroupRow) {
            $trickGroup = new Trickgroup();
            $trickGroup->setName($trickGroupRow);

            $trickGroupsToAdd[] = $trickGroup;

            $manager->persist($trickGroup);
        }

        $tricksToAdd = [];
        for ($t = 0; $t < 10; $t++) {

            $nameData = explode('@',$emailData[$t]);
     
            $user = new User();
            $user->setName($nameData[0])
                ->setEmail($emailData[$t])
                ->setRoles(['ROLE_USER'])
                ->setIsVerified(true)
                ->setToken($this->tokenGenerator->generateToken())
                ->setTokenCreatedAt(new \DateTimeImmutable())
                ->setPassword(
                    $this->hasher->hashPassword(
                        $user,
                        'userdatafixtures'
                    )
                );
            $manager->persist($user);
            shuffle($trickGroupsToAdd);

            $trickArray = $tricksArray[$t + 1]; // Adjust the index to start from 1

            $trick = new Trick();
            $trick->setName($trickArray['name'])
                ->setDescription($trickArray['description'])
                ->setUser($user);

            $nbGroupsToAdd = rand(0, count($trickGroupsToAdd)); // Random number of groups to add

            $groupsAdded = [];
            for ($g = 0; $g < $nbGroupsToAdd; $g++) {
                do {
                    $trickGroup = $trickGroupsToAdd[rand(0, count($trickGroupsToAdd) - 1)];
                } while (in_array($trickGroup->getName(), $groupsAdded));

                $groupsAdded[] = $trickGroup->getName();
                $trick->setTrickGroup($trickGroup);
            }

            $manager->persist($trick);
            $tricksToAdd[] = $trick;
        }

        // Image
        for ($i = 0; $i < 10; $i++) {

            // Créer le nom du fichier
            $tempFile = __DIR__ . '/' .  uniqid() . '.jpg';
            // Choisir un fichier aléatoirement
            $file = array_rand($tempImage);

            // Copier ce fichier avant de la déplacer dans le dossier upload
            copy($file, $tempFile);
            $image = new Image();
            $image->setName($tempFile)
                ->setTrick($tricksToAdd[$i]);

            $manager->persist($image);
        }

        $manager->flush();
    }
}
