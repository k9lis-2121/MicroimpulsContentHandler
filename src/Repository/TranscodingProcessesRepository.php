<?php

namespace App\Repository;

use App\Entity\TranscodingProcesses;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<TranscodingProcesses>
 *
 * @method TranscodingProcesses|null find($id, $lockMode = null, $lockVersion = null)
 * @method TranscodingProcesses|null findOneBy(array $criteria, array $orderBy = null)
 * @method TranscodingProcesses[]    findAll()
 * @method TranscodingProcesses[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TranscodingProcessesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TranscodingProcesses::class);
    }

    public function findOneByKpIdAsArray($kpId)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();

        $qb->select(
            't.kpId', // Используем правильное имя свойства
            't.hdd',
            't.ErrorMessage AS error_message', // Соблюдаем регистр, указанный в классе сущности
            't.UserSubmittedBy',
            't.Status',
            't.UpdateAt',
            't.PID as pid' // Сохраняем обозначение в camelCase, соответствующее PHP-стилю, но указываем, как PID в вашем классе
        )
        ->from(TranscodingProcesses::class, 't')
        ->where('t.kpId = :kpId')
        ->setParameter('kpId', $kpId);

        $query = $qb->getQuery();

        // Получаем один результат в виде массива
        $result = $query->getOneOrNullResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);

        return $result;
    }

    public function findByTranscodingStatus($status)
    {
        $em = $this->getEntityManager();
        $qb = $em->createQueryBuilder();
    
        $qb->select(
            't.kpId', // Убедитесь, что используете правильные названия полей
            't.hdd',
            't.ErrorMessage AS error_message', // Соблюдаем стиль именования
            't.UserSubmittedBy',
            't.Status',
            't.UpdateAt',
            't.PID as pid' // Обращаем внимание на регистр и стиль именования переменных
        )
        ->from(TranscodingProcesses::class, 't')
        ->where('t.Status = :Status')
        ->setParameter('Status', $status);
    
        $query = $qb->getQuery();
    
        // Получаем массив результатов
        $results = $query->getResult(\Doctrine\ORM\Query::HYDRATE_ARRAY);
    
        return $results;
    }

//    /**
//     * @return TranscodingProcesses[] Returns an array of TranscodingProcesses objects
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

//    public function findOneBySomeField($value): ?TranscodingProcesses
//    {
//        return $this->createQueryBuilder('t')
//            ->andWhere('t.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }
}
