<?php

namespace App\Controller;

use App\Form\UserType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class UserController extends AbstractController
{
    public function __construct(private readonly EntityManagerInterface $entityManager)
    {
    }

    #[Route('/profil/', name: 'app_user_profil')]
    public function index(): Response
    {
        $user = $this->getUser();
        return $this->render('user/index.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profil/edit', name: 'app_user_profil_edit')]
    public function edit(Request $request): Response
    {
        $user = $this->getUser();
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->entityManager->flush();
            return $this->redirectToRoute('app_user_profil');
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
        ]);
    }

    #[Route('/profil/delete', name: 'app_user_profil_delete')]
    public function delete(): Response
    {
        $user = $this->getUser();
        $this->entityManager->remove($user);
        $this->entityManager->flush();
        return $this->redirectToRoute('app_home');
    }

    #[Route('/profil/orders/{id}', name: 'app_user_profil_orders')]
    public function orders(): Response
    {
        $user = $this->getUser();
        return $this->render('user/orders.html.twig', [
            'user' => $user,
        ]);
    }
}
