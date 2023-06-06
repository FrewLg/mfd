<?php

namespace App\Repository;

use App\Entity\ResearchReport;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResearchReport|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchReport|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchReport[]    findAll()
 * @method ResearchReport[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchReportRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ResearchReport::class);
    }

    // /**
    //  * @return ResearchReport[] Returns an array of ResearchReport objects
    //  */
   
    public function getData($filters = [])
    {
        $qb = $this->createQueryBuilder('r');
        // if (isset($filters['search']) && $filters['search']) {
        //     $qb
        //         ->andWhere("r.label LIKE '%" . $filters['search'] . "%'");
        // }
        if (isset($filters['call']) && $filters['call']) {
            $qb
            ->join('r.submission',"s")
           
                ->andWhere("s.callForProposal = :call")
                ->setParameter("call",$filters['call'])
                ;
        }
        return $qb->orderBy('r.id', 'DESC')
            ->getQuery();
    }
  

    /*
    public function findOneBySomeField($value): ?ResearchReport
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
