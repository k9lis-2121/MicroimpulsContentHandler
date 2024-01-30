<?php

namespace App\Repository;

use App\Entity\UserToastStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<UserToastStatus>
 *
 * @method UserToastStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserToastStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserToastStatus[]    findAll()
 * @method UserToastStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserToastStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserToastStatus::class);
    }

//    /**
//     * @return UserToastStatus[] Returns an array of UserToastStatus objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('u.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?UserToastStatus
//    {
//        return $this->createQueryBuilder('u')
//            ->andWhere('u.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
