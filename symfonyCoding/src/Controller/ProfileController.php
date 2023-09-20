<?php

namespace App\Controller;

use App\Form\ProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @method getDoctrine()
 */
class ProfileController extends AbstractController
{
    #[Route('/profile', name: 'app_profile')]
    public function index()
    {
        // Récupérez les informations de l'utilisateur depuis la base de données
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', ['user' => $user]);
    }

// Modifier le profil
    public function edit(Request $request)
    {
        // Récupérez l'utilisateur connecté
        $user = $this->getUser();

        // Créez un formulaire
        $form = $this->createForm(ProfilType::class, $user);

        // Traitez la soumission du formulaire
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // Enregistrez les modifications dans la base de données
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            // Redirigez l'utilisateur vers sa page de profil
            return $this->redirectToRoute('profile');
        }

        return $this->render('profile/edit.html.twig', [
            'form' => $form->createView(),
        ]);
    }

}

