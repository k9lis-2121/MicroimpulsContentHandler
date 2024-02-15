<?php

namespace App\Repository;

use App\Entity\AsocPanelToSmarty;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<AsocPanelToSmarty>
 *
 * @method AsocPanelToSmarty|null find($id, $lockMode = null, $lockVersion = null)
 * @method AsocPanelToSmarty|null findOneBy(array $criteria, array $orderBy = null)
 * @method AsocPanelToSmarty[]    findAll()
 * @method AsocPanelToSmarty[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AsocPanelToSmartyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AsocPanelToSmarty::class);
    }

//    /**
//     * @return AsocPanelToSmarty[] Returns an array of AsocPanelToSmarty objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?AsocPanelToSmarty
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
