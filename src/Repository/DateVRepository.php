<?php

namespace App\Repository;

use App\Entity\DateV;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method DateV|null find($id, $lockMode = null, $lockVersion = null)
 * @method DateV|null findOneBy(array $criteria, array $orderBy = null)
 * @method DateV[]    findAll()
 * @method DateV[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DateVRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DateV::class);
    }
    public function findByUserId($userId)
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('d')
            ->from(DateV::class, 'd')
            ->leftJoin('d.imput', 'i')
            ->leftJoin('i.user', 'u')
            ->where('u.id = :userId')
            ->setParameter('userId', $userId)
            ->getQuery()
            ->getResult();
    }

    public function getDateVValuesPerDay($userId, $date){
        $qb = $this->_em->createQueryBuilder();

        $from = new \DateTime($date->format("Y-m-d")." 00:00:00");
        $to   = new \DateTime($date->format("Y-m-d")." 23:59:59");

        return $qb->select('d')
            ->from(DateV::class, 'd')
            ->leftJoin('d.imput', 'i')
            ->leftJoin('i.user', 'u')
            ->where('u.id = :userId')
            ->andWhere('d.date BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
            ->setParameter('userId', $userId)
            ->orderBy('d.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

    public function getDateVValuesPerWeek($userId, $date){
        $qb = $this->_em->createQueryBuilder();

        $from = new \DateTime($date->format("Y-m-d")." 00:00:00");
        $to   = (new \DateTime($date->format("Y-m-d")." 23:59:59"))->modify('+4 day');;

        return $qb->select('d')
            ->from(DateV::class, 'd')
            ->leftJoin('d.imput', 'i')
            ->leftJoin('i.user', 'u')
            ->where('u.id = :userId')
            ->andWhere('d.date BETWEEN :from AND :to')
            ->setParameter('from', $from )
            ->setParameter('to', $to)
            ->setParameter('userId', $userId)
            ->orderBy('d.date', 'ASC')
            ->getQuery()
            ->getResult();
    }

public function findByDateRange($dateDebut, $dateFin)
    {
        $qb = $this->_em->createQueryBuilder();
        return $qb->select('d')
        ->from(DateV::class, 'd')
        ->leftJoin('d.imput', 'i')
        ->where('d.date BETWEEN :dateDebut AND :dateFin')
        ->setParameter('dateDebut', $dateDebut )
        ->setParameter('dateFin', $dateFin)
        ->orderBy('d.date', 'ASC')
        ->getQuery()
        ->getResult();
    }
    // /**
    //  * @return DateV[] Returns an array of DateV objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DateV
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
