<?php

namespace App\Repository\IRB;

use App\Entity\IRB\BoardMember;
use App\Repository\UserRepository;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method BoardMember|null find($id, $lockMode = null, $lockVersion = null)
 * @method BoardMember|null findOneBy(array $criteria, array $orderBy = null)
 * @method BoardMember[]    findAll()
 * @method BoardMember[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class BoardMemberRepository extends ServiceEntityRepository
{
    private $userRepository;
    public function __construct(ManagerRegistry $registry,UserRepository $userRepository)
    {
        parent::__construct($registry, BoardMember::class);
        $this->userRepository=$userRepository;
    }

    // /**
    //  * @return BoardMember[] Returns an array of BoardMember objects
    //  */
  
    public function getData($filters=[])
    {
        $qb=$this->createQueryBuilder('b');
         
        if(isset($filters['search']) && $filters['search']){
           $res= $this->userRepository->getData(["name"=>$filters['search']]);
         
           $qb->andWhere("b.user in (:users)")
           ->setParameter("users",$res->getResult());
        }
        
            return $qb->getQuery()
         
        ;
    }


    /*
    public function findOneBySomeField($value): ?BoardMember
    {
        return $this->createQueryBuilder('b')
            ->andWhere('b.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
