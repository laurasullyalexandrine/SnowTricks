<?php


namespace App\Service;


use App\Entity\Trick;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\String\Slugger\SluggerInterface;


class Slugger
{
    public function __construct(
        private EntityManagerInterface $manager,
        private SluggerInterface $slugger
    ) {}


    public function slugify($string)
    {
        return $this->slugger->slug(str_replace(' ', '-', strtolower($string)));
    }


    public function slugifyTrick(Trick $trick)
    {
        $sluggedName = $this->slugify($trick->getName());
        dd($sluggedName);


        $trick->setSlug($sluggedName);
        $this->manager->flush();


        return $trick;
    }
}
