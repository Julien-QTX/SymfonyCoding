<?php

namespace App\Controller;

use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Entity\Articles;

class CRUDPostsController extends AbstractController
{
    #[Route('/posts/create', name: 'app_posts_create_controller', methods: ['GET', 'POST'])]
    public function create(Request $request, ManagerRegistry $entityManager): Response
    {

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $tags = $request->request->get('tags');
            $content = $request->request->get('content');
            $slug = preg_replace('/-+/', '-', strtr($title, ['é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ô' => 'o', 'ö' => 'o', 'û' => 'u', 'ü' => 'u', 'ç' => 'c']));
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $slug));
            $slug = preg_replace('/-+/', '-', $slug);



            // Process the form submission
            $article = new Articles();

            $article->setTitle($title)
                ->setDescription($content)
                ->setDate(new \DateTime())
                ->setImage('https://picsum.photos/200/300')
                // ->setTags($tags)
                ->setSlug($slug);


            $entityManager->getManager()->persist($article);
            $entityManager->getManager()->flush();

            return $this->redirectToRoute('app_posts_show_controller', ['slug' => $slug]);
        }
        return $this->render('posts/create.html.twig');

    }

    #[Route('/posts/show/{slug}', name: 'app_posts_show_controller', methods: ['GET'])]
    public function show($slug, ManagerRegistry $entityManager): Response
    {
        $article = $entityManager->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $title = $article->getTitle();
        $content = $article->getDescription();
        $date = $article->getDate();
        $date = $date->format('d/m/Y');
        $image = $article->getImage();
        // $tags = $article->getTags();
        return $this->render('posts/show.html.twig', [
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'image' => $image,
            // 'tags' => $tags
        ]);
    }

    #[Route('/posts/edit/{slug}', name: 'app_posts_edit_controller', methods: ['GET', 'POST'])]
    public function edit($slug, Request $request, ManagerRegistry $entityManager): Response
    {
        $article = $entityManager->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $title = $article->getTitle();
        $content = $article->getDescription();
        $date = $article->getDate();
        $date = $date->format('d/m/Y');
        $image = $article->getImage();
        // $tags = $article->getTags();
        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $tags = $request->request->get('tags');
            $content = $request->request->get('content');
            $slug = preg_replace('/-+/', '-', strtr($title, ['é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e', 'à' => 'a', 'â' => 'a', 'ä' => 'a', 'ô' => 'o', 'ö' => 'o', 'û' => 'u', 'ü' => 'u', 'ç' => 'c']));
            $slug = strtolower(preg_replace('/[^a-zA-Z0-9]/', '-', $slug));
            $slug = preg_replace('/-+/', '-', $slug);
            // Update the article in the database
            $article->setTitle($title)
                ->setDescription($content)
                ->setDate(new \DateTime())
                ->setImage('https://picsum.photos/200/300')
                // ->setTags($tags)
                ->setSlug($slug);

            $entityManager->getManager()->persist($article);
            $entityManager->getManager()->flush();

            return $this->redirectToRoute('app_posts_show_controller', ['slug' => $slug]);
            
        }
        return $this->render('posts/edit.html.twig', [
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'image' => $image,
            // 'tags' => $tags
        ]);
    }

    #[Route('/posts/delete/{slug}', name: 'app_posts_delete_controller', methods: ['POST'])]
    public function delete($slug, Request $request, ManagerRegistry $entityManager): Response
    {
        $article = $entityManager->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        if (!$article) {
            throw $this->createNotFoundException('No article found for slug ' . $slug);
        }
        if ($request->isMethod('POST')) {
            $entityManager->getManager()->remove($article);
            $entityManager->getManager()->flush();
            return $this->redirectToRoute('app_posts_index_controller');
        } else {
            return $this->redirectToRoute('app_posts_show_controller', ['slug' => $slug]);
        }
    }
}