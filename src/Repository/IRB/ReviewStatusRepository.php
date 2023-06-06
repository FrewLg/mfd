<?php

namespace App\Repository\IRB;

use App\Entity\IRB\ReviewStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReviewStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewStatus[]    findAll()
 * @method ReviewStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewStatus::class);
    }

    // /**
    //  * @return ReviewStatus[] Returns an array of ReviewStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReviewStatus
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
