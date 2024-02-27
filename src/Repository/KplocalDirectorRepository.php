<?php

namespace App\Repository;

use App\Entity\KplocalDirector;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KplocalDirector>
 *
 * @method KplocalDirector|null find($id, $lockMode = null, $lockVersion = null)
 * @method KplocalDirector|null findOneBy(array $criteria, array $orderBy = null)
 * @method KplocalDirector[]    findAll()
 * @method KplocalDirector[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KplocalDirectorRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KplocalDirector::class);
    }

//    /**
//     * @return KplocalDirector[] Returns an array of KplocalDirector objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('k.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?KplocalDirector
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
