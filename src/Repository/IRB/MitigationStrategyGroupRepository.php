<?php

namespace App\Repository\IRB;

use App\Entity\IRB\MitigationStrategyGroup as IRBMitigationStrategyGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method MitigationStrategyGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method MitigationStrategyGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method MitigationStrategyGroup[]    findAll()
 * @method MitigationStrategyGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MitigationStrategyGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IRBMitigationStrategyGroup::class);
    }

    // /**
    //  * @return MitigationStrategyGroup[] Returns an array of MitigationStrategyGroup objects
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
    public function findOneBySomeField($value): ?MitigationStrategyGroup
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
