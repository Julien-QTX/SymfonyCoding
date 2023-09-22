<?php

namespace App\Controller;

use App\Entity\Tags;
use App\Form\TagType;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/ctag')]
class CtagController extends AbstractController
{
    private $managerRegistry;

    public function __construct(ManagerRegistry $managerRegistry)
    {
        $this->managerRegistry = $managerRegistry;
    }

    //#[Route('/ctags', name: 'app_ctag_index', methods: ['GET'])]
    public function index(Request $request): Response
    {
        //$langue = $request->getLocale();
        $tags = $this->managerRegistry->getRepository(Tags::class)->findAll();
        return $this->render('ctag/index.html.twig', [
            'tags' => $tags,
            //'langue' => $langue
        ]);
    }

    //#[Route('/create', name: 'app_ctag_create', methods: ['GET', 'POST'])]
    public function create(Request $request): Response
    {
        //$langue = $request->getLocale();
        $tag = new Tags();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->persist($tag);
            $entityManager->flush();

            return $this->redirectToRoute('app_ctag_index');
        }

        return $this->render('ctag/create.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
            //'langue' => $langue
        ]);
    }

    //#[Route('/edit/{id}', name: 'app_ctag_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Tags $tag): Response
    {
        //$langue = $request->getLocale();
        $form = $this->createForm(TagType::class, $tag);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->flush();

            return $this->redirectToRoute('app_ctag_index');
        }

        return $this->render('ctag/edit.html.twig', [
            'tag' => $tag,
            'form' => $form->createView(),
            //'langue' => $langue
        ]);
    }

    //#[Route('/delete/{id}', name: 'app_ctag_delete', methods: ['POST'])]
    public function delete(Request $request, Tags $tag): Response
    {
        if ($this->isCsrfTokenValid('delete' . $tag->getId(), $request->request->get('_token'))) {
            $entityManager = $this->managerRegistry->getManager();
            $entityManager->remove($tag);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_ctag_index');
    }




}