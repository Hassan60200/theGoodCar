<?php

namespace App\Controller;

use App\Entity\Car;
use App\Entity\City;
use App\Entity\Departement;
use App\Entity\ModelsCar;
use App\Form\CarType;
use App\Repository\CarRepository;
use App\Repository\CityRepository;
use App\Repository\DepartementRepository;
use App\Repository\ModelsCarRepository;
use App\Repository\RegionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

#[Route('/car')]
class CarController extends AbstractController
{
    public function __construct(private readonly ModelsCarRepository   $modelsCarRepository,
                                private readonly DepartementRepository $departementRepository,
                                private readonly RegionRepository      $regionRepository,
                                private readonly CityRepository        $cityRepository)
    {
    }

    #[Route('/', name: 'app_car_index', methods: ['GET'])]
    public function index(CarRepository $carRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $regions = $this->regionRepository->findAll();

        $region = $request->query->get('region');
        $carDepartment = $request->query->get('carDepartment');
        $minPrice = $request->query->get('minPrice');
        $maxPrice = $request->query->get('maxPrice');
        $brand = $request->query->get('brand');
        $name = $request->query->get('name');
        $year = $request->query->get('year');
        $model = $request->query->get('model');

        if ($region || $carDepartment || $minPrice || $maxPrice || $brand || $name || $year || $model) {
            $cars = $carRepository->getCarsByFilters($region, $carDepartment, $minPrice, $maxPrice, $brand, $name, $year, $model);
        } else {
            $cars = $carRepository->findAll();
        }

        $carsPaginate = $paginator->paginate(
            $cars,
            $request->query->getInt('page', 1),
            10
        );
        $prices = [];
        foreach ($cars as $car) {
            $prices[] = $car->getPrice();
        }

        $brands = [];
        foreach ($cars as $car) {
            $brands[] = $car->getBrand()->getName();
        }
        $brands = array_unique($brands);


        return $this->render('car/index.html.twig', [
            'cars' => $carsPaginate,
            'regions' => $regions,
            'prices' => $prices,
            'departements' => $departements ?? [],
            'brands' => $brands,
        ]);
    }

    #[Route('/new', name: 'app_car_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $car = new Car();
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $year = $form->get('years')->getData();
            /** @var ModelsCar $model */
            $model = $this->modelsCarRepository->findOneBy(['name' => $form->get('model')->getData()]);
            /** @var Departement $departement */
            $departement = $this->departementRepository->findOneBy(['name' => $request->request->get('departement')]);
            /** @var City $city */
            $city = $this->cityRepository->findOneBy(['name' => $form->get('city')->getData()]);

            $car->setCarModel($model);
            $car->setDepartement($departement);
            $car->setYearOfManufacture($year);
            $car->setCity($city);

            $entityManager->persist($car);
            $entityManager->flush();

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/new.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{slug}', name: 'app_car_show', methods: ['GET'])]
    public function show(Car $car): Response
    {
        return $this->render('car/show.html.twig', [
            'car' => $car,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_car_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(CarType::class, $car);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('car/edit.html.twig', [
            'car' => $car,
            'form' => $form,
        ]);
    }

    #[Route('/{id}', name: 'app_car_delete', methods: ['POST'])]
    public function delete(Request $request, Car $car, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $car->getId(), $request->request->get('_token'))) {
            $entityManager->remove($car);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_car_index', [], Response::HTTP_SEE_OTHER);
    }

    #[Route('/autocomplete/model', name: 'app_car_autocomplete_model', methods: ['GET'])]
    public function autocompleteModel(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $model = $this->modelsCarRepository->findByBrand($request->query->get('query'), $request->query->get('search'));

        return $this->json($model, 200);
    }

    #[Route('/autocomplete/departement', name: 'app_car_autocomplete_departement', methods: ['GET'])]
    public function autocompleteDepartement(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $departements = $this->departementRepository->findDepartementByCode($request->query->get('query'));

        return $this->json($departements, 200);
    }

    #[Route('/autocomplete/city', name: 'app_car_autocomplete_city', methods: ['GET'])]
    public function autocompleteCity(Request $request): \Symfony\Component\HttpFoundation\JsonResponse
    {
        $departement = $this->departementRepository->findOneBy(['name' => $request->query->get('query')]);
        $city = $this->cityRepository->findCityByDepartement($departement->getId(), $request->query->get('search'));

        return $this->json($city, 200);
    }
}
