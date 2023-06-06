<?php

namespace App\Repository;

use App\Entity\UserGroup;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method UserGroup|null find($id, $lockMode = null, $lockVersion = null)
 * @method UserGroup|null findOneBy(array $criteria, array $orderBy = null)
 * @method UserGroup[]    findAll()
 * @method UserGroup[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class UserGroupRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserGroup::class);
    }
    public function countUserGroup()
    {
        $qb = $this->createQueryBuilder('u')
        ->select('count(u.id)')
        ->andWhere('u.isActive = 1')
        ->getQuery()
        ->getSingleScalarResult();
      
        return $qb;

    }
    public function findUserGroup($search=null)
    {
        $qb=$this->createQueryBuilder('u');
        if($search)
            $qb->andWhere("u.name  LIKE '%".$search."%'");

            return 
            $qb->orderBy('u.id', 'ASC')
            ->getQuery()
     
        ;
    }
   
    public function getData($filters=[])
    {
        return $this->createQueryBuilder('u')
         ->orderBy('u.id','DESC')
          
            ->getQuery()
           
        ;
    }
 

    /*
    public function findOneBySomeField($value): ?UserGroup
    {
        return $this->createQueryBuilder('u')
            ->andWhere('u.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
