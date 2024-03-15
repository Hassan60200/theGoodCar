<?php

namespace App\Repository;

use App\Entity\BrandsCar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<BrandsCar>
 *
 * @method BrandsCar|null find($id, $lockMode = null, $lockVersion = null)
 * @method BrandsCar|null findOneBy(array $criteria, array $orderBy = null)
 * @method BrandsCar[]    findAll()
 * @method BrandsCar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BrandsCarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, BrandsCar::class);
    }

    //    /**
    //     * @return BrandsCar[] Returns an array of BrandsCar objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('b.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?BrandsCar
    //    {
    //        return $this->createQueryBuilder('b')
    //            ->andWhere('b.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }
}
