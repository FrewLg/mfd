<?php

namespace App\Repository\IRB;

use App\Entity\IRB\ApplicationResearchSubject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ApplicationResearchSubject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ApplicationResearchSubject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ApplicationResearchSubject[]    findAll()
 * @method ApplicationResearchSubject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationResearchSubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ApplicationResearchSubject::class);
    }

    // /**
    //  * @return ApplicationResearchSubject[] Returns an array of ApplicationResearchSubject objects
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
    public function findOneBySomeField($value): ?ApplicationResearchSubject
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
