<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article')]
    public function index(ManagerRegistry $entityManager): Response
    {
        $articlestable = [];
        $articles = $entityManager->getRepository(Articles::class)->findAll();
        foreach ($articles as $article) {
            $title = $article->getTitle();
            $content = $article->getDescription();
            $date = $article->getDate();
            $date = $date->format('d/m/Y');
            $image = $article->getImage();
            $slug = $article->getSlug();
            // $tags = $article->getTags();
            $articlestable[] = [
                'title' => $title,
                'content' => $content,
                'date' => $date,
                'image' => $image,
                'slug' => $slug,
                // 'tags' => $tags,
            ];
        }
        return $this->render('articles/index.html.twig', [
            'articles' => $articlestable,
        ]);

    }
}