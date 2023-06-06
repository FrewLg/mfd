<?php

namespace App\Repository;

use App\Entity\ResearchReportComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResearchReportComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchReportComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchReportComment[]    findAll()
 * @method ResearchReportComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchReportCommentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchReportComment::class);
    }

    // /**
    //  * @return ResearchReportComment[] Returns an array of ResearchReportComment objects
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
    public function findOneBySomeField($value): ?ResearchReportComment
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
