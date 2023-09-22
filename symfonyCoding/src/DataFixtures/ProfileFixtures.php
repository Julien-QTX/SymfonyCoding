<?php
namespace App\DataFixtures;

use App\Entity\Users;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class ProfileFixtures extends Fixture
{
    private $userPasswordHasher;

    public function __construct(UserPasswordHasherInterface $userPasswordHasher)
    {
        $this->userPasswordHasher = $userPasswordHasher;
    }
    public function load(ObjectManager $manager)
{
    $faker = Factory::create('fr_FR'); // créer un générateur de fausses données pour le français
    $usersArray = []; // tableau pour stocker les informations des utilisateurs

    for ($i = 0; $i < 1000; $i++) {
        $user = new Users();
        
        $email = $faker->email();
        $password = $faker->password(8, 20);
        $username = $faker->userName();
        
        $user->setEmail($email);
        $user->setPassword($this->userPasswordHasher->hashPassword($user, $password));
        $user->setUsername($username);
        
        $manager->persist($user);

        // Ajouter l'utilisateur au tableau sous forme de tableau associatif
        $usersArray[] = [
            'email' => $email,
            'password' => $password, // stocker le mot de passe en clair pour l'export, à ne pas faire dans un environnement de production
            'username' => $username,
        ];
    }
    
    $manager->flush();

    // Convertir le tableau d'utilisateurs en JSON et l'écrire dans un fichier
    file_put_contents('users.json', json_encode($usersArray, JSON_PRETTY_PRINT));
}

}
