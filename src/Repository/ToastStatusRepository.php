<?php

namespace App\Repository;

use App\Entity\ToastStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<ToastStatus>
 *
 * @method ToastStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ToastStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ToastStatus[]    findAll()
 * @method ToastStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ToastStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ToastStatus::class);
    }

    public function findLatestUnviewedToastStatus(): ?ToastStatus
    {
        return $this->createQueryBuilder('t')
            ->where('t.viewed = :viewed')
            ->setParameter('viewed', false)
            ->orderBy('t.id', 'DESC')
            ->setMaxResults(1)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function setViewScreens(string $kpId, int $viewed = 0): void
    {
        $this->createQueryBuilder('t')
            ->update()
            ->set('t.viewed', ':viewed')
            ->where('t.kp_id = :kpId')
            ->setParameter('viewed', $viewed)
            ->setParameter('kpId', $kpId)
            ->getQuery()
            ->execute();
    }


    public function getToast(int $userId): array
    {
        $qb = $this->createQueryBuilder('ts');
        $qb->leftJoin('App\Entity\UserToastStatus', 'uts', 'WITH', 'ts.id = uts.toast_id AND uts.user_id = :userId')
           ->where($qb->expr()->neq('ts.component', ':component'))
           ->setParameter('component', 'WorkerThumbnailExtractor')
           ->andWhere($qb->expr()->isNull('uts.id'))
           ->setParameter('userId', $userId);

        return $qb->getQuery()->getResult();
    }


    public function getToastThumbnailExtractor(int $userId): array
    {
        $component = 'WorkerThumbnailExtractor';

        $qb = $this->createQueryBuilder('ts');
        $qb->leftJoin('App\Entity\UserToastStatus', 'uts', 'WITH', 'ts.id = uts.toast_id AND uts.user_id = :userId')
           ->where($qb->expr()->eq('ts.component', ':component'))
           ->setParameter('component', $component)
           ->setParameter('userId', $userId);

        return $qb->getQuery()->getResult();
    }

//    /**
//     * @return ToastStatus[] Returns an array of ToastStatus objects
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

//    public function findOneBySomeField($value): ?ToastStatus
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
