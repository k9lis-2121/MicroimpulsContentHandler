<?php

namespace App\Repository;

use App\Entity\Ratios;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Ratios>
 *
 * @method Ratios|null find($id, $lockMode = null, $lockVersion = null)
 * @method Ratios|null findOneBy(array $criteria, array $orderBy = null)
 * @method Ratios[]    findAll()
 * @method Ratios[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RatiosRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Ratios::class);
    }

//    /**
//     * @return Ratios[] Returns an array of Ratios objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('r.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Ratios
//    {
//        return $this->createQueryBuilder('r')
//            ->andWhere('r.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
