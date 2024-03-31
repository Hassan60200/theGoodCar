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

    //    /**
    //     * @return Car[] Returns an array of Car objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('c.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?Car
    //    {
    //        return $this->createQueryBuilder('c')
    //            ->andWhere('c.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByRegionAndDepartement($region, $departement): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.region', 'r')
            ->innerJoin('c.departement', 'd')
            ->andWhere('r.id = :region')
            ->andWhere('d.id = :departement')
            ->setParameter('region', $region)
            ->setParameter('departement', $departement)
            ->getQuery()
            ->getResult();
    }

    public function findByMinPrice(int $minPrice): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.price >= :minPrice')
            ->setParameter('minPrice', $minPrice)
            ->getQuery()
            ->getResult();
    }

    public function findByMaxPrice(int $maxPrice): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.price <= :maxPrice')
            ->setParameter('maxPrice', $maxPrice)
            ->getQuery()
            ->getResult();
    }

    public function findByPrices(int $minPrice, int $maxPrice): array
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.price >= :minPrice')
            ->andWhere('c.price <= :maxPrice')
            ->setParameter('minPrice', $minPrice)
            ->setParameter('maxPrice', $maxPrice)
            ->getQuery()
            ->getResult();
    }

    public function findByBrand(int $brand): array
    {
        return $this->createQueryBuilder('c')
            ->innerJoin('c.brand', 'b')
            ->andWhere('b.id = :brand')
            ->setParameter('brand', $brand)
            ->getQuery()
            ->getResult();
    }
}
