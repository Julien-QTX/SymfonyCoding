<?php

namespace App\Controller;

use App\Form\ProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Import Doctrine's EntityManager

class ProfileController extends AbstractController
{
    private $entityManager; // Declare the EntityManager property

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; // Inject the EntityManager
    }

    #[Route('/profile', name: 'app_profile')]
    public function index()
    {
        // Récupérez les informations de l'utilisateur depuis la base de données
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'page' => 'profile'
        ]);
    }

    // Modifier le profil
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher)
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        // Créez un formulaire
        $form = $this->createForm(ProfilType::class, $user);

        // Traitez la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez les modifications dans la base de données
            $encodedPassword = $userPasswordHasher->hashPassword(
                $user,
                $form->get('plainPassword')->getData()
            );

            $user->setPassword($encodedPassword);
            $this->entityManager->persist($user); // Use the injected EntityManager
            $this->entityManager->flush(); // Use the injected EntityManager

            // Redirigez l'utilisateur vers sa page de profil
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}