<?php

namespace App\Repository\IRB;

use App\Entity\IRB\ApplicationReview as IRBApplicationReview;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApplicationReview|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationReview|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationReview[]    findAll()
 * @method ApplicationReview[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationReviewRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IRBApplicationReview::class);
    }

    // /**
    //  * @return ApplicationReview[] Returns an array of ApplicationReview objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ApplicationReview
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
