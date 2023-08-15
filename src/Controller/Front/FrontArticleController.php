<?php

declare(strict_types=1);

namespace App\Controller\Front;

use App\Entity\Article;
use App\Repository\ArticleRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/front/article')]
class FrontArticleController extends AbstractController
{
    #[Route('/list/{idCategorie?}', name: 'front_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, $idCategorie): Response
    {
        if ($idCategorie === null) {
            return $this->render('front/article/index.html.twig', [
                'articles' => $articleRepository->findAll(),
            ]);
        } else {
            return $this->render('front/article/index.html.twig', [
                'articles' => $articleRepository->findBy(['fk_categorie' => $idCategorie]),
            ]);
        }
    }

    #[Route('/{id}', name: 'front_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('front/article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
