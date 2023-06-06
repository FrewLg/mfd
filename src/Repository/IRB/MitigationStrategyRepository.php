<?php

namespace App\Repository\IRB;

use App\Entity\IRB\MitigationStrategy;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MitigationStrategy|null find($id, $lockMode = null, $lockVersion = null)
 * @method MitigationStrategy|null findOneBy(array $criteria, array $orderBy = null)
 * @method MitigationStrategy[]    findAll()
 * @method MitigationStrategy[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MitigationStrategyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MitigationStrategy::class);
    }

    // /**
    //  * @return MitigationStrategy[] Returns an array of MitigationStrategy objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MitigationStrategy
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
