<?php

namespace App\Repository\IRB;

use App\Entity\IRB\IRBReviewAssignment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IRBReviewAssignment|null find($id, $lockMode = null, $lockVersion = null)
 * @method IRBReviewAssignment|null findOneBy(array $criteria, array $orderBy = null)
 * @method IRBReviewAssignment[]    findAll()
 * @method IRBReviewAssignment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IRBReviewAssignmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IRBReviewAssignment::class);
    }

    // /**
    //  * @return IRBReviewAssignment[] Returns an array of IRBReviewAssignment objects
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
    public function findOneBySomeField($value): ?ReviewAssignment
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */

    public function getActiveApplication()
    {
        return $this->createQueryBuilder('i')
            ->join("App:IRB\Application", "r", "with", "r.id=i.application")
            ->where('r.status in (:status)')->setParameter('status',[1,2,3,4])
            ->getQuery()
        ;
    }
}
