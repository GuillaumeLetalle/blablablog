<?php

namespace App\Controller\Account;

use App\Entity\Commentaire;
use App\Form\CommentaireType;
use App\Repository\ArticleRepository;
use App\Repository\CommentaireRepository;
use Carbon\Carbon;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/account/commentaire')]
class AccountCommentaireController extends AbstractController
{
    #[Route('/list/{idArticle?}', name: 'account_commentaire_index', methods: ['GET'])]
    public function index(CommentaireRepository $commentaireRepository, $idArticle): Response
    {

        if ($idArticle === null) {
            return $this->render('account/commentaire/index.html.twig', [
                'commentaires' => $commentaireRepository->findBy(['fk_user' => $this->getUser()]),
            ]);
        } else {
            return $this->render('account/commentaire/index.html.twig', [
                'commentaires' => $commentaireRepository->findBy(['fk_article' => $idArticle]),
            ]);
        }
    }

    #[Route('/{idArticle}/new', name: 'account_commentaire_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, ArticleRepository $articleRepository, $idArticle): Response
    {

        $now = Carbon::now();
        $commentaire = new Commentaire();
        $article = $articleRepository->find($idArticle);
        $form = $this->createForm(CommentaireType::class, $commentaire);
        $form->handleRequest($request);
        $commentaire->setDate($now);

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $this->getUser();
            $commentaire->setFkUser($user);
            $commentaire->setFkArticle($article);
            $commentaire->setIsvalide(0);
            $entityManager->persist($commentaire);
            $entityManager->flush();
            return $this->redirectToRoute('account_commentaire_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('account/commentaire/new.html.twig', [
            'commentaire' => $commentaire,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'account_commentaire_show', methods: ['GET'])]
    public function show(Commentaire $commentaire): Response
    {
        return $this->render('account/commentaire/show.html.twig', [
            'commentaire' => $commentaire,
        ]);
    }


    #[Route('/{id}', name: 'account_commentaire_delete', methods: ['POST'])]
    public function delete(Request $request, Commentaire $commentaire, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $commentaire->getId(), $request->request->get('_token'))) {
            $entityManager->remove($commentaire);
            $entityManager->flush();
        }
        $user = $this->getUser();
        return $this->redirectToRoute('account_commentaire_index', ['fk_user' => $user->getId()], Response::HTTP_SEE_OTHER);
    }
}
