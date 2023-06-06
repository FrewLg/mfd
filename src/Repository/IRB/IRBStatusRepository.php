<?php

namespace App\Repository\IRB;

use App\Entity\IRB\IRBStatus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IRBStatus|null find($id, $lockMode = null, $lockVersion = null)
 * @method IRBStatus|null findOneBy(array $criteria, array $orderBy = null)
 * @method IRBStatus[]    findAll()
 * @method IRBStatus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IRBStatusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IRBStatus::class);
    }

    // /**
    //  * @return IRBStatus[] Returns an array of IRBStatus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IRBStatus
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
