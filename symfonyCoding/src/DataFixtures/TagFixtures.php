<?php

namespace App\DataFixtures;

use App\Entity\Tags;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;

class TagFixtures extends Fixture
{
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR'); 
        $tagNames = ['Technologie', 'Sport', 'Musique', 'Art', 'Cuisine', 'Voyage', 'Santé', 'Science', 'Entreprise'];
        for ($i = 0; $i < 1000; $i++) {
            $tagNames[] = $faker->word();
        }

        foreach ($tagNames as $tagName) {
            $tag = new Tags();
            $tag->setName($tagName);
            $manager->persist($tag);
        }

        $manager->flush();
    }
}

