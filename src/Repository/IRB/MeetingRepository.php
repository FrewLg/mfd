<?php

namespace App\Repository\IRB;

use App\Entity\IRB\Meeting;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Meeting|null find($id, $lockMode = null, $lockVersion = null)
 * @method Meeting|null findOneBy(array $criteria, array $orderBy = null)
 * @method Meeting[]    findAll()
 * @method Meeting[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MeetingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Meeting::class);
    }

    // /**
    //  * @return Meeting[] Returns an array of Meeting objects
    //  */
 
    public function getData($filters=[])
    {
        return $this->createQueryBuilder('m')
            // ->andWhere('m.exampleField = :val')
            // ->setParameter('val', $value)
            ->orderBy('m.id', 'DESC')
            ->getQuery()
           ;
    }
  

    /*
    public function findOneBySomeField($value): ?Meeting
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
