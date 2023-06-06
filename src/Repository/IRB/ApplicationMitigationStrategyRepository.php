<?php

namespace App\Repository\IRB;

use App\Entity\IRB\ApplicationMitigationStrategy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApplicationMitigationStrategy|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationMitigationStrategy|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationMitigationStrategy[]    findAll()
 * @method ApplicationMitigationStrategy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationMitigationStrategyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationMitigationStrategy::class);
    }

    // /**
    //  * @return ApplicationMitigationStrategy[] Returns an array of ApplicationMitigationStrategy objects
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
    public function findOneBySomeField($value): ?ApplicationMitigationStrategy
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
