<?php

namespace App\Services;

use App\Entity\BrandsCar;
use App\Entity\City;
use App\Entity\Departement;
use App\Entity\ModelsCar;
use App\Entity\Region;
use App\Repository\BrandsCarRepository;
use App\Repository\CityRepository;
use App\Repository\DepartementRepository;
use App\Repository\ModelsCarRepository;
use App\Repository\RegionRepository;
use App\Trait\CarsTrait;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiManager
{
    use CarsTrait;

    public const API_URL = 'https://geo.api.gouv.fr/';

    public function __construct(
        private readonly HttpClientInterface    $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly RegionRepository       $regionRepository,
        private readonly DepartementRepository  $departementRepository,
        private readonly CityRepository         $cityRepository,
        private readonly BrandsCarRepository    $brandsCarRepository,
        private readonly ModelsCarRepository    $modelsCarRepository
    )
    {
    }

    public function getRegions()
    {
        $existingRegions = $this->regionRepository->findAll();

        if (empty($existingRegions)) {
            $regions = $this->client->request(
                'GET',
                self::API_URL . 'regions'
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
                self::API_URL . 'departements'
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

    public function getCities(string $value): array
    {
        $existingCities = $this->cityRepository->findAll();

        if (empty($existingCities)) {
            $cities = $this->client->request(
                'GET',
                'https://geo.api.gouv.fr/communes?nom=' . $value
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
            }
        }

        return $this->cityRepository->findAll();
    }

    public function getAllBrandCars(): array
    {
        $existingBrands = $this->brandsCarRepository->findAll();

        if (empty($existingBrands)) {
            $brands = $this->getAllBrands();

            foreach ($brands as $brand) {
                $newBrand = new BrandsCar();
                $newBrand->setName($brand);

                $this->entityManager->persist($newBrand);
            }
            $this->entityManager->flush();
        }

        return $this->brandsCarRepository->findAll();
    }

    public function getAllModels(): array
    {
        $existingCars = $this->brandsCarRepository->findAll();

        if (empty($existingCars)) {
            $cars = $this->getAllModels();

            foreach ($cars as $car) {
                $brand = $this->brandsCarRepository->findOneBy(['name' => $car['brand']]);
                if (!$brand) {
                    $brand = new BrandsCar();
                    $brand->setName($car['brand']);
                    $this->entityManager->persist($brand);
                }
                foreach ($car['model'] as $model) {
                    $modelCar = $this->modelsCarRepository->findOneBy(['name' => $model]);
                    if (!$modelCar) {
                        $modelCar = new ModelsCar();
                        $modelCar->setName($model);
                        $modelCar->setBrand($brand);
                        $this->entityManager->persist($modelCar);
                    }
                }
            }

            $this->entityManager->flush();
        }

        return $this->brandsCarRepository->findAll();
    }
}
