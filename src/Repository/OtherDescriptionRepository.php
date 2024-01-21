<?php

namespace App\Repository;

use App\Entity\OtherDescription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<OtherDescription>
 *
 * @method OtherDescription|null find($id, $lockMode = null, $lockVersion = null)
 * @method OtherDescription|null findOneBy(array $criteria, array $orderBy = null)
 * @method OtherDescription[]    findAll()
 * @method OtherDescription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OtherDescriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, OtherDescription::class);
    }

//    /**
//     * @return OtherDescription[] Returns an array of OtherDescription objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('o.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?OtherDescription
//    {
//        return $this->createQueryBuilder('o')
//            ->andWhere('o.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
