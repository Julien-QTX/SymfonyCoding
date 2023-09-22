<?php
// src/DataFixtures/ArticlesFixtures.php
namespace App\DataFixtures;

use App\Entity\Articles;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use App\Service\Slugger;

class ArticlesFixtures extends Fixture
{
    private Slugger $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }
    public function load(ObjectManager $manager)
    {
        $faker = Factory::create('fr_FR'); // créer un générateur de fausses données pour le français

        for ($i = 0; $i < 1000; $i++) {
            $article = new Articles();

            $title = $faker->sentence();
            $slug = $this->slugger->generateSlug($title);

            $article->setTitle($title);
            $article->setDescription($faker->text(5000)); // génère un texte de 5000 caractères
            $article->setDate($faker->dateTimeThisYear);
            $article->setImage($faker->imageUrl());
            $article->setSlug($slug);

            $manager->persist($article);
        }

        $manager->flush();
    }
}