<?php

namespace App\Services;

use App\Entity\BrandsCar;
use App\Entity\City;
use App\Entity\Departement;
use App\Entity\Region;
use App\Repository\BrandsCarRepository;
use App\Repository\CityRepository;
use App\Repository\DepartementRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class ApiManager
{
    public const API_URL = 'https://geo.api.gouv.fr/';

    public function __construct(
        private readonly HttpClientInterface $client,
        private readonly EntityManagerInterface $entityManager,
        private readonly RegionRepository $regionRepository,
        private readonly DepartementRepository $departementRepository,
        private readonly CityRepository $cityRepository,
        private readonly BrandsCarRepository $brandsCarRepository
    ) {
    }

    public function getRegions()
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

    public function getCities(string $value): array
    {
        $existingCities = $this->cityRepository->findAll();

        if (empty($existingCities)) {
            $cities = $this->client->request(
                'GET',
                'https://geo.api.gouv.fr/communes?nom='.$value
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
            $brands = [
                'ABARTH', 'AIWAYS', 'ALEKO', 'ALFA ROMEO', 'ALPINE RENAULT', 'ARO', 'ASIA', 'ASTON MARTIN', 'AUDI', 'AUSTIN',
                'AUTOBIANCHI', 'AUVERLAND', 'BEDFORD', 'BEE BEE AUTOMOTIVE', 'BENTLEY', 'BERTONE', 'BMW', 'BUICK', 'BYD',
                'CADILLAC', 'CHEVROLET', 'CHRYSLER', 'CITROEN', 'COURB', 'CUPRA', 'DACIA', 'DAEWOO', 'DAF', 'DAIHATSU', 'DAIMLER',
                'DATSUN', 'DODGE', 'DS', 'EBRO', 'FERRARI', 'FEST', 'FIAT', 'FISKER', 'FORD', 'FSO-POLSKI', 'GAC GONOW', 'GME',
                'GRANDIN', 'HONDA', 'HYUNDAI', 'INEOS', 'INFINITI', 'INNOCENTI', 'ISUZU', 'IVECO', 'JAGUAR', 'JEEP', 'KIA', 'LADA',
                'LANCIA', 'LAND ROVER', 'LDV', 'LEAPMOTOR', 'LEXUS', 'LOTUS', 'LYNK&CO', 'MAHINDRA', 'MAN', 'MARUTI', 'MASERATI',
                'MATRA', 'MAZDA', 'MCC', 'MEGA', 'MERCEDES', 'MG', 'MIA', 'MINI', 'MITSUBISHI', 'MPM MOTORS', 'NISSAN', 'OPEL',
                'PANHARD', 'PEUGEOT', 'PIAGGIO', 'PONTIAC', 'PORSCHE', 'PROTON', 'RENAULT', 'ROVER', 'SAAB', 'SANTANA', 'SEAT',
                'SERES DFSK', 'SKODA', 'SMART', 'SSANGYONG', 'SUBARU', 'SUNBEAM', 'SUZUKI', 'TALBOT', 'TATA', 'TESLA', 'THINK',
                'TOYOTA', 'TRIUMPH', 'UMM', 'VINFAST', 'VOLKSWAGEN', 'VOLVO', 'ZASTAVA', 'ZAZ',
            ];

            foreach ($brands as $brand) {
                $newBrand = new BrandsCar();
                $newBrand->setName($brand);

                $this->entityManager->persist($newBrand);
            }
            $this->entityManager->flush();

        }

        return $this->brandsCarRepository->findAll();
    }
}
