<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Service\PdfGenerator;

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
        'page' => 'home',
        ]);
    }
    #[Route('/download-article-pdf/{id}', name: 'downloadArticlePdf')]
    public function downloadArticlePdf(int $id, PdfGenerator $pdfGenerator, EntityManagerInterface $entityManager): Response
    {
        // Récupérez l'article en fonction de l'ID
        $article = $entityManager->getRepository(Articles::class)->find($id);
        $title = $article->getTitle();
        $content = $article->getDescription();
        $date = $article->getDate();
        $date = $date->format('d/m/Y');
        $image = $article->getImage();
        $article_id = $article->getId();

        if (!$article) {
            throw $this->createNotFoundException('Article not found');
        }

        // Vous pouvez passer les données de l'article à votre template Twig
        $html = $this->renderView('posts/show.html.twig', [
            'article' => $article,// Assurez-vous que le nom de la variable ici est "article"
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'image' => $image,
            'article_id' => $article_id,
        ]);

        // Générez le PDF
        $pdf = $pdfGenerator->generatePdf($html);

        // Retournez le PDF en réponse
        return new Response(
            $pdf,
            200,
            [
                'Content-Type' => 'application/pdf',
                'Content-Disposition' => 'inline; filename="article.pdf"',
            ]
        );
    }

}