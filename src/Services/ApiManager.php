<?php

namespace App\Services;

use App\Entity\City;
use App\Entity\Departement;
use App\Entity\Region;
use App\Repository\CityRepository;
use App\Repository\DepartementRepository;
use App\Repository\RegionRepository;
use App\Trait\CarsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiManager
{
    use CarsTrait;

    public const API_URL = 'https://geo.api.gouv.fr/';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly RegionRepository $regionRepository,
        private readonly DepartementRepository $departementRepository,
        private readonly CityRepository $cityRepository,
    ) {
    }

    public function getRegions(): array
    {
        $existingRegions = $this->regionRepository->findAll();

        if (empty($existingRegions)) {
            $regions = $this->client->request(
                'GET',
                self::API_URL.'regions'
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

    public function getDepartements(): array
    {
        $existingDepartements = $this->departementRepository->findAll();

        if (empty($existingDepartements)) {
            $departements = $this->client->request(
                'GET',
                self::API_URL.'departements'
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

    public function getCities($code): JsonResponse
    {
        $cities = $this->client->request(
            'GET',
            'https://geo.api.gouv.fr/departements/'.$code.'/communes'
        );

        foreach ($cities->toArray() as $cityData) {
            $region = $this->regionRepository->findOneBy(['code' => $cityData['codeRegion']]);
            $departement = $this->departementRepository->findOneBy(['code' => $cityData['codeDepartement']]);

            $city = new City();
            $city->setName($cityData['nom'])
                ->setCodeDepartement($departement)
                ->setCodeRegion($region)
                ->setCode(intval($cityData['code']))
                ->setZipCode($cityData['codesPostaux'][0]);

            $this->entityManager->persist($city);
        }
        $this->entityManager->flush();

        return new JsonResponse(['message' => 'Cities imported successfully.'], 200);
    }
}
