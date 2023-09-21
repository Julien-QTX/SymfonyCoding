<?php

namespace App\Controller;

use App\Entity\Users;
use App\Form\ProfilType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class RegistrationController extends AbstractController
{
    #[Route('/register', name: 'app_register')]
    public function register(Request $request, UserPasswordHasherInterface $userPasswordHasher, EntityManagerInterface $entityManager): Response
    {
        $user = new Users();
        #$form = $this->createForm(RegistrationFormType::class, $user);
        #$form->handleRequest($request);
        #$user = new User(); // Replace "User" with your entity class name

        $form = $this->createForm(ProfilType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // Encode and set the plain password
            $plainPassword = $form->get('plainPassword')->getData();
            $HasherPassword = $userPasswordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($HasherPassword);

            // Other entity fields and saving logic here

            return $this->redirectToRoute('profile'); // Redirect to profile page
        }

        #if ($form->isSubmitted() && $form->isValid()) {
        #    // encode the plain password
        #    $user->setPassword(
        #        $userPasswordHasher->hashPassword(
        #            $user,
        #            $form->get('plainPassword')->getData()
        #        )
        #    );

        #    $entityManager->persist($user);
        #    $entityManager->flush();
        #    // do anything else you need here, like send an email

        #    return $this->redirectToRoute('app_login');
        #}

        return $this->render('registration/register.html.twig', [
            'registrationForm' => $form->createView(),
        ]);
    }
}
