<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;

class ArticleController extends AbstractController
{
    #[Route('/', name: 'app_article')]
    public function index(ManagerRegistry $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $user = $this->getUser();
        $articlestable = [];
        $articles = $entityManager->getRepository(Articles::class)->findAll();
        foreach ($articles as $article) {
            $title = $article->getTitle();
            $content = $article->getDescription();
            $content = substr($content, 0, 200);
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
        // paginate
        $articles = $paginator->paginate(
            $articlestable, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'page' => 'home',
        ]);

    }
}