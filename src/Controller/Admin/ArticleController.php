<?php

namespace App\Controller\Admin;

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

#[Route('/admin/article')]
class ArticleController extends AbstractController
{
    #[Route('/list/{idCategorie?}', name: 'app_article_index', methods: ['GET'])]
    public function index(ArticleRepository $articleRepository, $idCategorie): Response
    {
        if($idCategorie === null){
            return $this->render('admin/article/index.html.twig', [
                'articles' => $articleRepository->findAll(),
            ]);
        }else{
            return $this->render('admin/article/index.html.twig', [
                'articles' => $articleRepository->findBy(['fk_categorie' => $idCategorie]),
            ]);
        }
    }

    #[Route('/new', name: 'app_article_new', methods: ['GET', 'POST'])]
    public function new(
        Request $request,
        EntityManagerInterface $entityManager,
        FileUploaderService $fileUploaderService,
        $publicUploadDir,
    ): Response
    {

        $date = date('Y-m-d');
        $format = 'Y-m-d';
        $date = DateTime::createFromFormat($format, $date);

        $article = new Article();
        $form = $this->createForm(ArticleType::class, $article);

        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $article->setDate($date);

            $file= $form['image']->getData();
            if($file){
                $this->doUpload($file, $article, $fileUploaderService, $publicUploadDir );
            }

            $entityManager->persist($article);
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/new.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_show', methods: ['GET'])]
    public function show(Article $article): Response
    {
        return $this->render('admin/article/show.html.twig', [
            'article' => $article,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_article_edit', methods: ['GET', 'POST'])]
    public function edit(
        Request $request,
        Article $article,
        EntityManagerInterface $entityManager,
        FileUploaderService $fileUploaderService,
        $publicUploadDir,
    ): Response
    {
        $form = $this->createForm(ArticleType::class, $article);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $file= $form['image']->getData();
            if($file){
                $this->doUpload($file, $article, $fileUploaderService, $publicUploadDir );
            }
            $entityManager->flush();

            return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('admin/article/edit.html.twig', [
            'article' => $article,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_article_delete', methods: ['POST'])]
    public function delete(Request $request, Article $article, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$article->getId(), $request->request->get('_token'))) {
            $entityManager->remove($article);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_article_index', [], Response::HTTP_SEE_OTHER);
    }

    private function doUpload($file, $article, $fileUploaderService, $publicUploadDir ){
        $file_name = $fileUploaderService->upload($file);
        if ($file_name !== null){
            $file_path = $publicUploadDir.'/'.$file_name;
            $article->setImage($file_path);
        }
    }
}
