<?php

namespace App\Repository;

use App\Entity\TasksScreen;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TasksScreen>
 *
 * @method TasksScreen|null find($id, $lockMode = null, $lockVersion = null)
 * @method TasksScreen|null findOneBy(array $criteria, array $orderBy = null)
 * @method TasksScreen[]    findAll()
 * @method TasksScreen[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TasksScreenRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TasksScreen::class);
    }

//    /**
//     * @return TasksScreen[] Returns an array of TasksScreen objects
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

//    public function findOneBySomeField($value): ?TasksScreen
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
