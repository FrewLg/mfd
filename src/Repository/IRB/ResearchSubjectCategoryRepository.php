<?php

namespace App\Repository\IRB;

use App\Entity\IRB\ResearchSubjectCategory as IRBResearchSubjectCategory;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ResearchSubjectCategory|null find($id, $lockMode = null, $lockVersion = null)
 * @method ResearchSubjectCategory|null findOneBy(array $criteria, array $orderBy = null)
 * @method ResearchSubjectCategory[]    findAll()
 * @method ResearchSubjectCategory[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ResearchSubjectCategoryRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, IRBResearchSubjectCategory::class);
    }

    // /**
    //  * @return ResearchSubjectCategory[] Returns an array of ResearchSubjectCategory objects
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
    public function findOneBySomeField($value): ?ResearchSubjectCategory
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
