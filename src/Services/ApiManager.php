<?php

namespace App\Services;

use App\Entity\Departement;
use App\Entity\Region;
use App\Repository\DepartementRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiManager
{

    public function __construct(
        private readonly HttpClientInterface    $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly RegionRepository       $regionRepository,
        private readonly DepartementRepository  $departementRepository,
    )
    {
    }

    public function getRegions()
    {
        $existingRegions = $this->regionRepository->findAll();

        if (empty($existingRegions)) {
            $regions = $this->client->request(
                'GET',
                'https://geo.api.gouv.fr/regions'
            );

            foreach ($regions->toArray() as $regionData) {
                $newRegion = new Region();
                $newRegion->setName($regionData['nom'])
                    ->setCode($regionData['code']);

                $this->entityManager->persist($newRegion);
            }

            $this->entityManager->flush();
        }

        return $this->regionRepository->findAll();
    }


    public function getDepartements()
    {

        $existingDepartements = $this->departementRepository->findAll();

        if (empty($existingDepartements)) {
            $departements = $this->client->request(
                'GET',
                'https://geo.api.gouv.fr/departements'
            );

            foreach ($departements->toArray() as $departementData) {
                $region = $this->regionRepository->findOneBy(['code' => $departementData['codeRegion']]);
                $newDepartement = new Departement();
                $newDepartement->setName($departementData['nom'])
                    ->setCode($departementData['code'])
                    ->setRegion($region);

                $this->entityManager->persist($newDepartement);
            }

            $this->entityManager->flush();
        }

        return $this->departementRepository->findAll();
    }
}