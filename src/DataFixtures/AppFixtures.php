<?php

namespace App\DataFixtures;

use Faker\Factory;
use App\Entity\Trick;
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
        //  Trick array
        $tricks = [
            1 => [
                'name' => 'Ollie Nollie',
                'description' => "Nous avons parlé du Ollie ci-dessus, le Nollie c'est l'inverse. Accroupis-toi, déplace ton poids vers l'avant, puis utilise le nez de ta planche pour sauter.",
                'trick_group' => 'Butter Trick',
                'picture' => 'https://img.freepik.com/photos-premium/personne-faisant-du-snowboard-devant-montagne_771335-49889.jpg'
            ],
            2 => [
                'name' => 'Tail Press',
                'description' => "Le Tail Press est initié en déplaçant ton poids vers l'arrière de ta planche tout en restant droit et en soulevant le Nose de la neige.",
                'trick_group' => 'Butter Trick',
                'picture' => 'https://media.istockphoto.com/id/1092719712/fr/photo/snowboardeur-sautant-dans-les-airs-avec-le-bleu-intense-du-ciel-%C3%A0-larri%C3%A8re-plan.jpg?s=612x612&w=0&k=20&c=aT-Zm-FYNBTa5hjzlfp5x_06K1SZRVydHLN3GDTulpo='
            ],
            3 => [
                'name' => 'Indy',
                'description' => 'Attrape le carre des orteils de ta planche, entre les fixations, avec ta main arrière.',
                'trick_group' => 'Grabs',
                'picture' => 'https://media.istockphoto.com/id/1304125483/fr/photo/sensation-forte-dans-la-neige.jpg?s=612x612&w=0&k=20&c=TG_EiLaJnmvu9p-JM9lBaPLr-2sg-u0BS-b8WYi0NJo='
            ],
            4 => [
                'name' => 'Stalefish',
                'description' => 'Passe la main derrière ton genou arrière et attrape le carre de ta planche entre les fixations, côté talon, avec ta main arrière.',
                'trick_group' => 'Grabs',
                'picture' => 'https://img.freepik.com/premium-photo/active-snowboarder-board-slides-down-snowy-mountain-illustration_305419-2169.jpg?w=2000'
            ],
            5 => [
                'name' => 'Tail',
                'description' => "Attrape le talon de ta planche avec ta main arrière (juste à l'extrémité, pas sur les côtés).",
                'trick_group' => 'Grabs',
                'picture' => 'https://img.freepik.com/premium-photo/skier-is-flying-through-air-front-snowy-mountain_868783-347.jpg'
            ],
            6 => [
                'name' => 'Wildcat',
                'description' => 'Un Wildcat est un Backflip qui garde la planche parallèle à la trajectoire, tu fais donc une sorte de Flip "latéral" sans perte de vitesse.',
                'trick_group' => 'Grabs',
                'picture' => 'https://us.123rf.com/450wm/storyimage/storyimage2302/storyimage230201453/198799123-snowboarder-freeride-sur-la-pente-de-la-montagne-enneig%C3%A9e-ia-g%C3%A9n%C3%A9rative-illustrations-de-haute.jpg?ver=6'
            ],
            7 => [
                'name' => 'Tamedog',
                'description' => "L'exact opposé d'un Wildcat est un Tamedog. C'est un Frontflip qui garde la planche parallèle à la trajectoire. Un hard Nollie utilise le nez comme tremplin pour amorcer la rotation.",
                'trick_group' => 'Flips',
                'picture' => 'https://img.freepik.com/photos-premium/snowboarder-saute-montagne-generative-ai_851394-273.jpg?w=2000'
            ],
            8 => [
                'name' => '50-50',
                'description' => "Il s'agit de chevaucher un rail ou un box avec ta planche en ligne droite sur la structure.",
                'trick_group' => 'Rail/Boxes',
                'picture' => 'https://img.freepik.com/photos-premium/snowboarder-volant-montagnes-sports-hiver-extremes-ai-generative_391052-12657.jpg?w=2000'
            ],
            9 => [
                'name' => 'Frontside Boardslide',
                'description' => "Il s'agit de glisser jusqu'au rail sur ton côté arrière, puis de sauter dessus avec le nez de la planche au-dessus du rail. Tu atterris avec le rail entre tes fixations, ta planche perpendiculaire à la structure.",
                'trick_group' => 'Rail',
                'picture' => 'https://img.freepik.com/photos-premium/personne-faisant-du-snowboard-devant-montagne_771335-49889.jpg'
            ],
            10 => [
                'name' => 'Frontside Lipslide',
                'description' => " Identique à la figure précédente, mais tu te diriges vers le rail en le positionnant sur ton côté avant. Tu sautes ensuite avec le talon de la planche au-dessus du rail et tu atterris dessus avec le rail entre tes fixations.",
                'trick_group' => 'Rail',
                'picture' => 'https://img.freepik.com/photos-premium/snowboard-glace-image-art-du-generateur-ai_848845-146.jpg?w=2000'
            ],
        ];

        foreach ($tricks as $trick) {
            $newTrick = new Trick();
            $newTrick->setName($trick['name'])
                ->setSlug($this->slugger->slug(str_replace(' ', '-', $newTrick->getName()))->lower())
                ->setDescription($trick['description'])
                ->setTrickGroup($trick['trick_group'])
                ->setPicture($trick['picture']);

            $manager->persist($newTrick);
        }

        $manager->flush();
    }
}