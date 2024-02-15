<?php

namespace App\Repository;

use App\Entity\KplocalActors;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KplocalActors>
 *
 * @method KplocalActors|null find($id, $lockMode = null, $lockVersion = null)
 * @method KplocalActors|null findOneBy(array $criteria, array $orderBy = null)
 * @method KplocalActors[]    findAll()
 * @method KplocalActors[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KplocalActorsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KplocalActors::class);
    }

//    /**
//     * @return KplocalActors[] Returns an array of KplocalActors objects
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

//    public function findOneBySomeField($value): ?KplocalActors
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
