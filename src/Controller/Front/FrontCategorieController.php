<?php
declare(strict_types=1);

namespace App\Controller\Front;

use App\Entity\Categorie;
use App\Repository\CategorieRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
#[Route('/front/categorie')]
class FrontCategorieController extends AbstractController
{
    #[Route('/', name: 'front_categorie_index', methods: ['GET'])]
    public function index(CategorieRepository $categorieRepository): Response
    {
        return $this->render('front/categorie/index.html.twig', [
            'categories' => $categorieRepository->findAll(),
        ]);
    }

    #[Route('/{id}', name: 'front_categorie_show', methods: ['GET'])]
    public function show(Categorie $categorie): Response
    {
        return $this->render('front/categorie/show.html.twig', [
            'categorie' => $categorie,
        ]);
    }
}