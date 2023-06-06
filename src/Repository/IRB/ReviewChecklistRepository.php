<?php

namespace App\Repository\IRB;

use App\Entity\IRB\ReviewChecklist;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReviewChecklist|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReviewChecklist|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReviewChecklist[]    findAll()
 * @method ReviewChecklist[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReviewChecklistRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReviewChecklist::class);
    }

    // /**
    //  * @return ReviewChecklist[] Returns an array of ReviewChecklist objects
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
    public function findOneBySomeField($value): ?ReviewChecklist
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
