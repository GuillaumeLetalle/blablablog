<?php

namespace App\Controller\Front;

use App\Entity\User;
use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;

class FrontController extends AbstractController
{
    #[Route('/', name: 'app_home')]
    public function index(): Response
    {
        return $this->render('front/dashboard.html.twig', [
            'controller_name' => 'FrontController',
        ]);
    }

    #[Route('/new', name: 'front_user_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = new User();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $plaintextPassword = $user->getPassword();
            $hasedPassword = $passwordHasher->hashPassword(
                $user,
                $plaintextPassword
            );
            $user->setPassword($hasedPassword);
            $user->setRoles(['ROLE_IDENTIFIED']);
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('user_login', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('front/userFront/new.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }
}
