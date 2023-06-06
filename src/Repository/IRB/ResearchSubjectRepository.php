<?php

namespace App\Repository\IRB;

use App\Entity\IRB\ResearchSubject;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResearchSubject|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchSubject|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchSubject[]    findAll()
 * @method ResearchSubject[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchSubjectRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchSubject::class);
    }

    // /**
    //  * @return ResearchSubject[] Returns an array of ResearchSubject objects
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
    public function findOneBySomeField($value): ?ResearchSubject
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
