<?php

namespace App\Repository\IRB;

use App\Entity\IRB\IRBReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IRBReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method IRBReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method IRBReview[]    findAll()
 * @method IRBReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IRBReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IRBReview::class);
    }

    // /**
    //  * @return IRBReview[] Returns an array of IRBReview objects
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
    public function findOneBySomeField($value): ?Review
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
