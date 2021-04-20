<?php

namespace App\Repository;

use App\Entity\Imputation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Imputation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Imputation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Imputation[]    findAll()
 * @method Imputation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ImputationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Imputation::class);
    }

    // /**
    //  * @return Imputation[] Returns an array of Imputation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Imputation
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
