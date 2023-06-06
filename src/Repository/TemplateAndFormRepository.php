<?php

namespace App\Repository;

use App\Entity\TemplateAndForm;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method TemplateAndForm|null find($id, $lockMode = null, $lockVersion = null)
 * @method TemplateAndForm|null findOneBy(array $criteria, array $orderBy = null)
 * @method TemplateAndForm[]    findAll()
 * @method TemplateAndForm[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TemplateAndFormRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, TemplateAndForm::class);
    }

    // /**
    //  * @return TemplateAndForm[] Returns an array of TemplateAndForm objects
    //  */

    public function getData($filters = [])
    {
        $qb = $this->createQueryBuilder('t');
        if (isset($filters['search']) && $filters['search']) {
            $qb
                ->andWhere("t.label LIKE '%" . $filters['search'] . "%'");
        }
        if (isset($filters['call']) && $filters['call']) {
            $qb
                ->andWhere("t.callFor = :call")
                ->setParameter("call",$filters['call'])
                ;
        }
        return $qb->orderBy('t.id', 'DESC')
            ->getQuery();
    }


    /*
    public function findOneBySomeField($value): ?TemplateAndForm
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
