<?php

namespace App\Controller;

use App\Repository\DepartementRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DepartementController extends AbstractController
{
    #[Route('/departement', name: 'app_departement')]
    public function index(DepartementRepository $departementRepository): Response
    {
        $departements = $departementRepository->findAll();

        return $this->render('departement/index.html.twig', [
            'departements' => $departements,
        ]);
    }
}
