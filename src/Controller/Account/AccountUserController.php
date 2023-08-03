<?php

namespace App\Controller\Account;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\CommentaireRepository;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;


#[Route('/account/user')]
class AccountUserController extends AbstractController
{

    #[Route('/edit', name: 'account_user_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, EntityManagerInterface $entityManager, UserPasswordHasherInterface $passwordHasher): Response
    {
        $user = $this->getUser();
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
            $entityManager->flush();

            return $this->redirectToRoute('account_home');
        }

        return $this->render('account/user/edit.html.twig', [
            'user' => $user,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'account_user_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager, CommentaireRepository $commentaireRepository, $id): Response
    {
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $commentaireRepository->setCommentsToNull($id);
            $entityManager->remove($user);
            $entityManager->flush();
            $this->container->get('security.token_storage')->setToken(null);
        }
        return $this->redirectToRoute('app_home');
    }
}
