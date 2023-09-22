<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ProfilType;
use App\Form\RegistrationFormType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $entityManager): Response
    {
        $user = new Users();
        $form = $this->createForm(RegistrationFormType::class, $user);
        $form->handleRequest($request);// Replace "User" with your entity class name

        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode and set the plain password
            $plainPassword = $form->get('plainPassword')->getData();
            $HasherPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($HasherPassword);
            $username = $form->get('username')->getData();
            $user->setUsername($username);

            $entityManager->getManager()->persist($user);
            $entityManager->getManager()->flush();

             return $this->redirectToRoute('app_login'); // Redirect to profile page
        }

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
            'page' => 'register'
        ]);
    }
}
