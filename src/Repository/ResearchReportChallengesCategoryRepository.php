<?php

namespace App\Repository;

use App\Entity\ResearchReportChallengesCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResearchReportChallengesCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchReportChallengesCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchReportChallengesCategory[]    findAll()
 * @method ResearchReportChallengesCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchReportChallengesCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchReportChallengesCategory::class);
    }

    // /**
    //  * @return ResearchReportChallengesCategory[] Returns an array of ResearchReportChallengesCategory objects
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
    public function findOneBySomeField($value): ?ResearchReportChallengesCategory
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
