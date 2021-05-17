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
