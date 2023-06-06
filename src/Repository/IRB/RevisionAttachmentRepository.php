<?php

namespace App\Repository\IRB;

use App\Entity\IRB\RevisionAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method RevisionAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method RevisionAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method RevisionAttachment[]    findAll()
 * @method RevisionAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class RevisionAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, RevisionAttachment::class);
    }

    // /**
    //  * @return RevisionAttachment[] Returns an array of RevisionAttachment objects
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
    public function findOneBySomeField($value): ?RevisionAttachment
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
