<?php

namespace App\Controller;

use App\Form\ProfilType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasher;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface; // Import Doctrine's EntityManager
use Doctrine\Persistence\ManagerRegistry;
use App\Entity\Logs;
use App\Entity\Users;
class ProfileController extends AbstractController
{
    private $entityManager; // Declare the EntityManager property

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager; // Inject the EntityManager
    }

    //#[Route('/profile', name: 'app_profile')]
    public function index(ManagerRegistry $entityManager, Request $request)
    {
        //$langue = $request->getLocale();
        $user = $this->getUser();

        if ($user) {
            $userId = $user->getId();
            $userEntity = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
            $log = new Logs();
            $log->setIdUser($userEntity);
            $log->setPage('show profile ' . $user->getUsername());
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        } else {
            $log = new Logs();
            $log->setPage('show profile');
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        }

        // Récupérez les informations de l'utilisateur depuis la base de données
        $user = $this->getUser();

        return $this->render('profile/index.html.twig', [
            'user' => $user,
            'page' => 'profile',
            //'langue' => $langue,
        ]);
    }

    // Modifier le profil
    public function edit(Request $request, UserPasswordHasherInterface $userPasswordHasher, ManagerRegistry $entityManager)
    {
       // $langue = $request->getLocale();
        $user = $this->getUser();

        if ($user) {
            $userId = $user->getId();
            $userEntity = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
            $log = new Logs();
            $log->setIdUser($userEntity);
            $log->setPage('edit profile ' . $user->getUsername());
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        } else {
            $log = new Logs();
            $log->setPage('edit profile');
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        }

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
            'page' => 'edit profile',
           // 'langue' => $langue,
        ]);
    }
}