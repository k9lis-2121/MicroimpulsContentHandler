<?php

namespace App\Repository;

use App\Entity\VodDirTemplate;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<VodDirTemplate>
 *
 * @method VodDirTemplate|null find($id, $lockMode = null, $lockVersion = null)
 * @method VodDirTemplate|null findOneBy(array $criteria, array $orderBy = null)
 * @method VodDirTemplate[]    findAll()
 * @method VodDirTemplate[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class VodDirTemplateRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, VodDirTemplate::class);
    }

//    /**
//     * @return VodDirTemplate[] Returns an array of VodDirTemplate objects
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

//    public function findOneBySomeField($value): ?VodDirTemplate
//    {
//        return $this->createQueryBuilder('v')
//            ->andWhere('v.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
