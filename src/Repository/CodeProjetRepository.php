<?php

namespace App\Repository;

use App\Entity\CodeProjet;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method CodeProjet|null find($id, $lockMode = null, $lockVersion = null)
 * @method CodeProjet|null findOneBy(array $criteria, array $orderBy = null)
 * @method CodeProjet[]    findAll()
 * @method CodeProjet[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CodeProjetRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CodeProjet::class);
    }

    // /**
    //  * @return CodeProjet[] Returns an array of CodeProjet objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CodeProjet
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
