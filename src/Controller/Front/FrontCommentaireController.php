<?php
declare(strict_types=1);

namespace App\Controller\Front;

use App\Entity\Commentaire;
use App\Repository\CommentaireRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/front/commentaire')]
class FrontCommentaireController extends AbstractController
{
    #[Route('/{idArticle?}', name: 'front_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository, $idArticle): Response
    {
        if($idArticle === null){
            return $this->render('front/commentaire/index.html.twig', [
                'commentaires' => $commentaireRepository->findAll(),
            ]);
        }else{
            return $this->render('front/commentaire/index.html.twig', [
                'commentaires' => $commentaireRepository->findBy(['fk_article' => $idArticle]),
            ]);
        }
       # return $this->render('front/commentaire/index.html.twig', [
           # 'commentaires' => $commentaireRepository->findAll(),
        #]);
    }

    #[Route('/{id}', name: 'front_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('front/commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }
}