<?php

namespace App\Controller\Account;

use App\Service\FileUploaderService;
use DateTime;
use App\Entity\Article;
use App\Form\ArticleType;
use App\Repository\ArticleRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route('/account/article')]
class AccountArticleController extends AbstractController
{
    #[Route('/list/{idCategorie?}', name: 'account_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, $idCategorie): Response
    {
        if ($idCategorie === null) {
            return $this->render('account/article/index.html.twig', [
                'articles' => $articleRepository->findAll(),
            ]);
        } else {
            return $this->render('account/article/index.html.twig', [
                'articles' => $articleRepository->findBy(['fk_categorie' => $idCategorie]),
            ]);
        }
    }

    #[Route('/{id}', name: 'account_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('account/article/show.html.twig', [
            'article' => $article,
        ]);
    }
}
