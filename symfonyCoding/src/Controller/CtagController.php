<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class CtagController extends AbstractController
{
    #[Route('/ctag', name: 'app_ctag')]
    public function index(): Response
    {
        return $this->render('ctag/index.html.twig', [
            'controller_name' => 'CtagController',
        ]);
    }
}
