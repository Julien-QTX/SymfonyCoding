<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $tagNames = ['Technologie', 'Sport', 'Musique', 'Art', 'Cuisine', 'Voyage', 'Santé', 'Science', 'Entreprise'];

        foreach ($tagNames as $tagName) {
            $tag = new Tags();
            $tag->setName($tagName);
            $manager->persist($tag);
        }

        $manager->flush();
    }
}

