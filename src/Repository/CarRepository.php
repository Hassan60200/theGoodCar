<?php

namespace App\Repository;

use App\Entity\Car;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Car>
 *
 * @method Car|null find($id, $lockMode = null, $lockVersion = null)
 * @method Car|null findOneBy(array $criteria, array $orderBy = null)
 * @method Car[]    findAll()
 * @method Car[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Car::class);
    }

    public function getCarsByFilters(?int $region = null, ?int $carDepartment = null, ?float $minPrice = null, ?float $maxPrice = null, ?string $brand = null, ?string $name = null, ?int $year = null, ?int $model = null)
    {
        $qb = $this->createQueryBuilder('c');

        if ($region) {
            $qb->innerJoin('c.region', 'r')
                ->andWhere('r.id = :region')
                ->setParameter('region', $region);
        }

        if ($carDepartment) {
            $qb->innerJoin('c.departement', 'd')
                ->andWhere('d.id = :departement')
                ->setParameter('departement', $carDepartment);
        }

        if ($minPrice) {
            $qb->andWhere('c.price >= :minPrice')
                ->setParameter('minPrice', $minPrice);
        }

        if ($maxPrice) {
            $qb->andWhere('c.price <= :maxPrice')
                ->setParameter('maxPrice', $maxPrice);
        }

        if ($brand) {
            $qb->innerJoin('c.brand', 'b')
                ->andWhere('b.name = :brand')
                ->setParameter('brand', $brand);
        }

        if ($year) {
            $qb->andWhere('c.yearOfManufacture = :year')
                ->setParameter('year', $year);
        }

        if ($model) {
            $qb->innerJoin('c.carModel', 'm')
                ->andWhere('m.id = :model')
                ->setParameter('model', $model);
        }

        if ($name) {
            $qb->innerJoin('c.brand', 'b')
                ->innerJoin('c.carModel', 'm')
                ->andWhere('b.name LIKE :name')
                ->orWhere('m.name LIKE :name')
                ->setParameter('name', '%'.$name.'%');
        }

        return $qb->getQuery()
            ->getResult();
    }

    public function getAvailaibleCars()
    {
        return $this->createQueryBuilder('c')
            ->orWhere('c.status = :status1')
            ->orWhere('c.status = :status2')
            ->setParameter('status1', 'Vente')
            ->setParameter('status2', 'Louer')
            ->getQuery()
            ->getResult();
    }

}
