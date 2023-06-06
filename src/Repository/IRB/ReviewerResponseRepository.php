<?php

namespace App\Repository\IRB;

use App\Entity\IRB\ReviewerResponse;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReviewerResponse|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewerResponse|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewerResponse[]    findAll()
 * @method ReviewerResponse[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewerResponseRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewerResponse::class);
    }

    // /**
    //  * @return ReviewerResponse[] Returns an array of ReviewerResponse objects
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
    public function findOneBySomeField($value): ?ReviewerResponse
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
