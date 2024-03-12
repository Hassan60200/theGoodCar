<?php

namespace App\Controller;

use App\Services\ApiManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

class CityController extends AbstractController
{
    #[Route('/city', name: 'app_city')]
    public function index(ApiManager $apiManager): Response
    {
        $cities = $apiManager->getCities('paris');
        return $this->render('city/index.html.twig', [
            'cities' => $cities,
        ]);
    }
}
