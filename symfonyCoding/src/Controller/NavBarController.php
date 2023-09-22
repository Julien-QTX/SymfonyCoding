<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class NavBarController extends AbstractController
{
    //#[Route('/navbar', name: 'app_navbar')]
    public function index(): Response
    {
        if ($this->getUser()) {
            $user = $this->getUser()->getUsername();
            $user = ucfirst($user);
            return $this->render('navbar/index.html.twig', [
                'controller_name' => 'NavBarController',
                'user' => $user,
            ]);
        }
        return $this->render('navbar/index.html.twig', [
            'controller_name' => 'NavBarController',
        ]);
    }
}
