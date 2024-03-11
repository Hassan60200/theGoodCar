<?php

namespace App\Controller;

use App\Services\ApiManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class RegionController extends AbstractController
{

    #[Route('/region', name: 'app_region')]
    public function index(ApiManager $apiManager): Response
    {
        $regions = $apiManager->getRegions();

        return $this->render('region/index.html.twig', [
            'regions' => $regions,
        ]);
    }
}
