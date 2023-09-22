<?php

namespace App\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Articles;
use App\Entity\Logs;
use App\Entity\Users;
use App\Entity\Tags;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use App\Service\PdfGenerator;

class ArticleController extends AbstractController
{
    //#[Route('/', name: 'app_article')]
    public function index(ManagerRegistry $entityManager, PaginatorInterface $paginator, Request $request): Response
    {
        $langue = $request->getLocale();
        $user = $this->getUser();

        if ($user) {
            $userId = $user->getId();
            $userEntity = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
            $log = new Logs();
            $log->setIdUser($userEntity);
            $log->setPage('home');
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        } else {
            $log = new Logs();
            $log->setPage('home');
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        }

        $articlestable = [];
        $articles = $entityManager->getRepository(Articles::class)->findAll();
        $tags = $entityManager->getRepository(Tags::class)->findAll();
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
            ];
        }
        // paginate
        $articles = $paginator->paginate(
            $articlestable,
            /* query NOT result */
            $request->query->getInt('page', 1),
            /*page number*/
            10 /*limit per page*/
        );
        return $this->render('articles/index.html.twig', [
            'articles' => $articles,
            'page' => 'home',
            'langue' => $langue,
            'categories' => $tags,
        ]);
    }
    #[Route('/download-article-pdf/{id}', name: 'downloadArticlePdf')]
    public function downloadArticlePdf(int $id, PdfGenerator $pdfGenerator, EntityManagerInterface $entityManager, Request $request): Response
    {
        $langue = $request->getLocale();
        // Récupérez l'article en fonction de l'ID
        $article = $entityManager->getRepository(Articles::class)->find($id);
        $title = $article->getTitle();
        $content = $article->getDescription();
        $date = $article->getDate();
        $date = $date->format('d/m/Y');
        $image = $article->getImage();
        $article_id = $article->getId();
        $slug = $article->getSlug();
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
            'langue' => $langue,
            'slug' => $slug,
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