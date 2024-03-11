<?php

namespace App\Controller;

use App\Services\ApiManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class DepartementController extends AbstractController
{
    #[Route('/departement', name: 'app_departement')]
    public function index(ApiManager $apiManager): Response
    {

        $departements = $apiManager->getDepartements();
        return $this->render('departement/index.html.twig', [
            'departements' => $departements,
        ]);
    }
}
