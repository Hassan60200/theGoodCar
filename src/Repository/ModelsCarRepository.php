<?php

namespace App\Repository;

use App\Entity\ModelsCar;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ModelsCar>
 *
 * @method ModelsCar|null find($id, $lockMode = null, $lockVersion = null)
 * @method ModelsCar|null findOneBy(array $criteria, array $orderBy = null)
 * @method ModelsCar[]    findAll()
 * @method ModelsCar[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ModelsCarRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ModelsCar::class);
    }

    //    /**
    //     * @return ModelsCar[] Returns an array of ModelsCar objects
    //     */
    //    public function findByExampleField($value): array
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->orderBy('m.id', 'ASC')
    //            ->setMaxResults(10)
    //            ->getQuery()
    //            ->getResult()
    //        ;
    //    }

    //    public function findOneBySomeField($value): ?ModelsCar
    //    {
    //        return $this->createQueryBuilder('m')
    //            ->andWhere('m.exampleField = :val')
    //            ->setParameter('val', $value)
    //            ->getQuery()
    //            ->getOneOrNullResult()
    //        ;
    //    }

    public function findByBrand(int $id, string $name): array
    {
        return $this->createQueryBuilder('m')
            ->innerJoin('m.brand', 'b')
            ->andWhere('b.id = :id')
            ->andWhere('m.name LIKE :name')
            ->setParameter('id', $id)
            ->setParameter('name', $name.'%')
            ->orderBy('m.id', 'ASC')
            ->getQuery()
            ->getArrayResult();
    }
}
