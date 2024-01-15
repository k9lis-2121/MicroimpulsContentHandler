<?php

namespace App\Repository;

use App\Entity\Testnversion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Testnversion>
 *
 * @method Testnversion|null find($id, $lockMode = null, $lockVersion = null)
 * @method Testnversion|null findOneBy(array $criteria, array $orderBy = null)
 * @method Testnversion[]    findAll()
 * @method Testnversion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TestnversionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Testnversion::class);
    }

//    /**
//     * @return Testnversion[] Returns an array of Testnversion objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('t.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Testnversion
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
