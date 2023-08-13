<?php

namespace App\DataFixtures;

use App\Entity\Image;
use Faker\Factory;
use App\Entity\Trick;
use App\Entity\Trickgroup;
use Doctrine\Persistence\ObjectManager;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{
    public function __construct(private SluggerInterface $slugger)
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');

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

        // Trick image array
        $imageNames = [
            'https://media.istockphoto.com/id/1092719712/fr/photo/snowboardeur-sautant-dans-les-airs-avec-le-bleu-intense-du-ciel-%C3%A0-larri%C3%A8re-plan.jpg?s=612x612&w=0&k=20&c=aT-Zm-FYNBTa5hjzlfp5x_06K1SZRVydHLN3GDTulpo=',
            'https://img.freepik.com/premium-photo/active-snowboarder-board-slides-down-snowy-mountain-illustration_305419-2169.jpg?w=2000',
            'https://img.freepik.com/premium-photo/skier-is-flying-through-air-front-snowy-mountain_868783-347.jpg',
            'https://img.freepik.com/photos-premium/snowboarder-saute-montagne-generative-ai_851394-273.jpg?w=2000',
            'https://img.freepik.com/photos-premium/snowboarder-volant-montagnes-sports-hiver-extremes-ai-generative_391052-12657.jpg?w=2000',
            'https://img.freepik.com/photos-premium/snowboard-glace-image-art-du-generateur-ai_848845-146.jpg?w=2000',
            'https://img.freepik.com/photos-premium/homme-faisant-du-snowboard-montagne-ciel-nuageux-derriere-lui_779834-3731.jpg?w=2000',
            'https://us.123rf.com/450wm/storyimage/storyimage2302/storyimage230201381/198798887-snowboarder-freeride-on-the-slope-in-snow-mountain-generative-ai-high-quality-illustration.jpg?ver=6',
            'https://us.123rf.com/450wm/olegganko/olegganko2304/olegganko230404220/203081067-homme-de-snowboard-illustration-g%C3%A9n%C3%A9rative-ai.jpg?ver=6',
            'snowboard-home.png',
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
            shuffle($trickGroupsToAdd);

            $trickArray = $tricksArray[$t + 1]; // Adjust the index to start from 1

            $trick = new Trick();
            $trick->setName($trickArray['name'])
                ->setDescription($trickArray['description']);

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
            $image = new Image();
            $image->setName($imageNames[$i])
                ->setTrick($tricksToAdd[$i]);
        
            $manager->persist($image);
       }

    $manager->flush();
    }
}

