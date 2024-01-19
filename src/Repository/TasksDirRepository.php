<?php

namespace App\Repository;

use App\Entity\TasksDir;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TasksDir>
 *
 * @method TasksDir|null find($id, $lockMode = null, $lockVersion = null)
 * @method TasksDir|null findOneBy(array $criteria, array $orderBy = null)
 * @method TasksDir[]    findAll()
 * @method TasksDir[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasksDirRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TasksDir::class);
    }

//    /**
//     * @return TasksDir[] Returns an array of TasksDir objects
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

//    public function findOneBySomeField($value): ?TasksDir
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
