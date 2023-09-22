<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use App\Entity\Logs;
use App\Entity\Users;
use Symfony\Component\HttpFoundation\Request;

class LoginController extends AbstractController
{
    //#[Route('/login', name: 'app_login')]
    public function index(AuthenticationUtils $authenticationUtils, ManagerRegistry $entityManager, Request $request): Response
    {
        //$langue = $request->getLocale();
        $user = $this->getUser();

        if ($user) {
            $userId = $user->getId();
            $userEntity = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
            $log = new Logs();
            $log->setIdUser($userEntity);
            $log->setPage('login');
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        } else {
            $log = new Logs();
            $log->setPage('login');
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        }
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();

        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        
        // Check if the user is already authenticated
        if ($this->getUser()) {
            // User is authenticated, perform redirection here
            return $this->redirectToRoute('app_profile');
        }

        return $this->render('login/login.html.twig', [
            'last_username' => $lastUsername,
            'error' => $error,
            'page' => 'login',
            //'langue' => $langue,
        ]);
    }
}
