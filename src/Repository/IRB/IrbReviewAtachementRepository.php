<?php

namespace App\Repository\IRB;

use App\Entity\IRB\IrbReviewAtachement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method IrbReviewAtachement|null find($id, $lockMode = null, $lockVersion = null)
 * @method IrbReviewAtachement|null findOneBy(array $criteria, array $orderBy = null)
 * @method IrbReviewAtachement[]    findAll()
 * @method IrbReviewAtachement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class IrbReviewAtachementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IrbReviewAtachement::class);
    }

    // /**
    //  * @return IrbReviewAtachement[] Returns an array of IrbReviewAtachement objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('i.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?IrbReviewAtachement
    {
        return $this->createQueryBuilder('i')
            ->andWhere('i.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
