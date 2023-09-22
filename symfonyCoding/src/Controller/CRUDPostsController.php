<?php

namespace App\Controller;

use App\Entity\Articles;
use App\Entity\Tags;
use App\Entity\TagsLiaison;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Service\Slugger;
use App\Entity\Logs;
use App\Entity\Users;

class CRUDPostsController extends AbstractController
{
    private Slugger $slugger;

    public function __construct(Slugger $slugger)
    {
        $this->slugger = $slugger;
    }

    //#[Route('/posts/create', name: 'app_posts_create_controller', methods: ['GET', 'POST'])]
    public function create(Request $request, ManagerRegistry $entityManager): Response
    {
        $langue = $request->getLocale();
        $user = $this->getUser();

        if ($user) {
            $userId = $user->getId();
            $userEntity = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
            $log = new Logs();
            $log->setIdUser($userEntity);
            $log->setPage('create post');
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        } else {
            $log = new Logs();
            $log->setPage('create post');
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        }

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $tags = $request->request->get('tags');

            $tags = str_replace(', ', ',', $tags);
            $tags = explode(',', $tags);

            if (count($tags) > 5) {
                throw new \Exception('Too many tags. Maximum is 5.');
            }
            foreach ($tags as $tag) {
                if (strlen($tag) > 25) {
                    throw new \Exception('One of your tags is too long. Maximum is 25 characters.');
                }
                $tagEntity = $entityManager->getRepository(Tags::class)->findOneBy(['name' => $tag]);
                if (!$tagEntity) {
                    $tagEntity = new Tags();
                    $tagEntity->setName($tag);
                    $entityManager->getManager()->persist($tagEntity);
                    $entityManager->getManager()->flush();
                }
                $tagId = $tagEntity->getId();
            }
            $content = $request->request->get('content');
            $slug = $this->slugger->generateSlug($title);



            // Process the form submission
            $article = new Articles();

            $article->setTitle($title)
                ->setDescription($content)
                ->setDate(new \DateTime())
                ->setImage('https://picsum.photos/200/300')
                ->setSlug($slug);


            $entityManager->getManager()->persist($article);
            $entityManager->getManager()->flush();

            $article = $entityManager->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
            $title = $article->getid();
            foreach ($tags as $tag) {
                $tagsLiaison = new TagsLiaison();
                $tagsLiaison->setIdTag($tagEntity)
                    ->setIdArticle($article);
                $entityManager->getManager()->persist($tagsLiaison);
                $entityManager->getManager()->flush();
            }


            return $this->redirectToRoute('app_posts_show_controller', ['slug' => $slug]);
        }
        return $this->render('posts/create.html.twig', [
            'page' => 'create',
            'langue' => $langue,
        ]);

    }

    //#[Route('/posts/show/{slug}', name: 'app_posts_show_controller', methods: ['GET'])]
    public function show($slug, ManagerRegistry $entityManager, Request $request): Response
    {
        //$langue = $request->getLocale();
        $user = $this->getUser();

        if ($user) {
            $userId = $user->getId();
            $userEntity = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
            $log = new Logs();
            $log->setIdUser($userEntity);
            $log->setPage('show post ' . $slug);
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        } else {
            $log = new Logs();
            $log->setPage('show post ' . $slug);
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        }

        $article = $entityManager->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $title = $article->getTitle();
        $content = $article->getDescription();
        $date = $article->getDate();
        $date = $date->format('d/m/Y');
        $image = $article->getImage();
        $article_id = $article->getId();

        return $this->render('posts/show.html.twig', [
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'image' => $image,
            'slug' => $slug,
            //'langue' => $langue,
            'article_id' => $article_id,
            'article'=> $article
            // 'tags' => $tags,
        ]);
    }

    //#[Route('/posts/edit/{slug}', name: 'app_posts_edit_controller', methods: ['GET', 'POST'])]
    public function edit(string $slug, Request $request, ManagerRegistry $entityManager): Response
    {
        //$langue = $request->getLocale();
        $user = $this->getUser();

        if ($user) {
            $userId = $user->getId();
            $userEntity = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
            $log = new Logs();
            $log->setIdUser($userEntity);
            $log->setPage('edit post ' . $slug);
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        } else {
            $log = new Logs();
            $log->setPage('edit post ' . $slug);
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        }

        $article = $entityManager->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        $title = $article->getTitle();
        $content = $article->getDescription();
        $date = $article->getDate();
        $date = $date->format('d/m/Y');
        $image = $article->getImage();
        $tagsentity = $entityManager->getRepository(TagsLiaison::class)->findBy(['id_article' => $article->getId()]);
        $tags = [];
        foreach ($tagsentity as $tag) {
            $tag = $tag->getIdTag();
            $tagEntity = $entityManager->getRepository(Tags::class)->findOneBy(['id' => $tag]);
            $tags[] = $tagEntity->getName();
        }
        $tags = implode(', ', $tags);

        if ($request->isMethod('POST')) {
            $title = $request->request->get('title');
            $tags = $request->request->get('tags');
            $tags = str_replace(', ', ',', $tags);
            $tags = explode(',', $tags);
            if (count($tags) > 5) {
                throw new \Exception('Too many tags. Maximum is 5.');
            }
            foreach ($tags as $tag) {
                if (strlen($tag) > 25) {
                    throw new \Exception('One of your tags is too long. Maximum is 25 characters.');
                }
                $tagEntity = $entityManager->getRepository(Tags::class)->findOneBy(['name' => $tag]);
                if (!$tagEntity) {
                    $tagEntity = new Tags();
                    $tagEntity->setName($tag);
                    $entityManager->getManager()->persist($tagEntity);
                    $entityManager->getManager()->flush();
                }
                $tagId = $tagEntity->getId();
            }
            $content = $request->request->get('content');
            $slug = $this->slugger->generateSlug($title);

            $article->setTitle($title)
                ->setDescription($content)
                ->setDate(new \DateTime())
                ->setImage('https://picsum.photos/200/300')
                ->setSlug($slug);

            $entityManager->getManager()->persist($article);
            $entityManager->getManager()->flush();

            $article = $entityManager->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
            $title = $article->getid();
            foreach ($tags as $tag) {
                $tagsLiaison = new TagsLiaison();
                $tagsLiaison->setIdTag($tagEntity)
                    ->setIdArticle($article);
                $entityManager->getManager()->persist($tagsLiaison);
                $entityManager->getManager()->flush();
            }
            return $this->redirectToRoute('app_posts_show_controller', ['slug' => $slug]);

        }
        return $this->render('posts/edit.html.twig', [
            'title' => $title,
            'content' => $content,
            'date' => $date,
            'image' => $image,
            'tags' => $tags,
            'slug' => $slug,
            //'langue' => $langue,
        ]);
    }

    //#[Route('/posts/delete/{slug}', name: 'app_posts_delete_controller', methods: ['POST'])]
    public function delete($slug, Request $request, ManagerRegistry $entityManager): Response
    {
        //$langue = $request->getLocale();
        $user = $this->getUser();

        if ($user) {
            $userId = $user->getId();
            $userEntity = $entityManager->getRepository(Users::class)->findOneBy(['id' => $userId]);
            $log = new Logs();
            $log->setIdUser($userEntity);
            $log->setPage('delete post ' . $slug);
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        } else {
            $log = new Logs();
            $log->setPage('delete post ' . $slug);
            $log->setDatetime(new \DateTime());
            $entityManager->getManager()->persist($log);
            $entityManager->getManager()->flush();
        }

        $article = $entityManager->getRepository(Articles::class)->findOneBy(['slug' => $slug]);
        if (!$article) {
            throw $this->createNotFoundException('No article found for slug ' . $slug);
        }
        if ($request->isMethod('POST')) {
            // verify tags liaison
            $tagsLiaison = $entityManager->getRepository(TagsLiaison::class)->findBy(['id_article' => $article->getId()]);
            foreach ($tagsLiaison as $tagLiaison) {
                $entityManager->getManager()->remove($tagLiaison);
                $entityManager->getManager()->flush();
            }
            $entityManager->getManager()->remove($article);
            $entityManager->getManager()->flush();
            return $this->redirectToRoute('app_article');
        } else {
            return $this->redirectToRoute('app_posts_show_controller', ['slug' => $slug,
            //    'langue' => $langue,
        ]);
        }
    }
}