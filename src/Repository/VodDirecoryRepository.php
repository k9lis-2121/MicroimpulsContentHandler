<?php

namespace App\Repository;

use App\Entity\VodDirecory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VodDirecory>
 *
 * @method VodDirecory|null find($id, $lockMode = null, $lockVersion = null)
 * @method VodDirecory|null findOneBy(array $criteria, array $orderBy = null)
 * @method VodDirecory[]    findAll()
 * @method VodDirecory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VodDirecoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VodDirecory::class);
    }

//    /**
//     * @return VodDirecory[] Returns an array of VodDirecory objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('v.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?VodDirecory
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
