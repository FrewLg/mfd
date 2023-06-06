<?php

namespace App\Repository\IRB;

use App\Entity\IRB\ReviewStatusGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReviewStatusGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewStatusGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewStatusGroup[]    findAll()
 * @method ReviewStatusGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewStatusGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewStatusGroup::class);
    }

    // /**
    //  * @return ReviewStatusGroup[] Returns an array of ReviewStatusGroup objects
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
    public function findOneBySomeField($value): ?ReviewStatusGroup
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
