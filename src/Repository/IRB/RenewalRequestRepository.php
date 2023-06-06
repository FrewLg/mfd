<?php

namespace App\Repository\IRB;

use App\Entity\IRB\RenewalRequest;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RenewalRequest|null find($id, $lockMode = null, $lockVersion = null)
 * @method RenewalRequest|null findOneBy(array $criteria, array $orderBy = null)
 * @method RenewalRequest[]    findAll()
 * @method RenewalRequest[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RenewalRequestRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RenewalRequest::class);
    }

    // /**
    //  * @return RenewalRequest[] Returns an array of RenewalRequest objects
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
    public function findOneBySomeField($value): ?RenewalRequest
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
