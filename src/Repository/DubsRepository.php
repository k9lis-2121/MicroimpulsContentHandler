<?php

namespace App\Repository;

use App\Entity\Dubs;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Dubs>
 *
 * @method Dubs|null find($id, $lockMode = null, $lockVersion = null)
 * @method Dubs|null findOneBy(array $criteria, array $orderBy = null)
 * @method Dubs[]    findAll()
 * @method Dubs[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DubsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Dubs::class);
    }

//    /**
//     * @return Dubs[] Returns an array of Dubs objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('d.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Dubs
//    {
//        return $this->createQueryBuilder('d')
//            ->andWhere('d.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
