<?php

namespace App\Repository;

use App\Entity\KplocalFilms;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<KplocalFilms>
 *
 * @method KplocalFilms|null find($id, $lockMode = null, $lockVersion = null)
 * @method KplocalFilms|null findOneBy(array $criteria, array $orderBy = null)
 * @method KplocalFilms[]    findAll()
 * @method KplocalFilms[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class KplocalFilmsRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, KplocalFilms::class);
    }

    public function finAllFilmsBySearch()
    {
        $em = $this->getEntityManager();
    
        $qb = $em->createQueryBuilder();
        $qb->select('e.id', 'e.name', 'e.isSeason', 'e.nameOrig', 'e.kpId')
        ->from(KplocalFilms::class, 'e');
        
        return $qb->getQuery()->getResult();
    }

    public function findFilmsBySearchTerm($searchTerm)
    {
        $em = $this->getEntityManager();
        
        $qb = $em->createQueryBuilder();
        $qb->select('e.id', 'e.name', 'e.isSeason', 'e.nameOrig', 'e.kpId')
            ->from(KplocalFilms::class, 'e')
            ->where($qb->expr()->like('e.name', ':searchTerm'))
            ->setParameter('searchTerm', '%'.$searchTerm.'%'); // добавляем знаки % для поиска частичного совпадения
        
        return $qb->getQuery()->getResult();
    }

    public function findOneByAsArray($criteria)
{
    $em = $this->getEntityManager();
    $qb = $em->createQueryBuilder();

    $qb->select('e.id', 'e.name', 'e.isSeason', 'e.nameOrig', 'e.kpId')
        ->from(KplocalFilms::class, 'e')
        ->where('e.kpId = :someValue')
       ->setParameter('someValue', $criteria['kpId']);

    // Добавьте любые другие условия, которые вам нужны

    $query = $qb->getQuery();

    // Получаем один результат в виде массива
    $result = $query->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

    return $result;
}
//    /**
//     * @return KplocalFilms[] Returns an array of KplocalFilms objects
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

//    public function findOneBySomeField($value): ?KplocalFilms
//    {
//        return $this->createQueryBuilder('k')
//            ->andWhere('k.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
