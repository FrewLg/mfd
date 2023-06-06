<?php

namespace App\Repository\IRB;

use App\Entity\IRB\AmendmentAttachment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method AmendmentAttachment|null find($id, $lockMode = null, $lockVersion = null)
 * @method AmendmentAttachment|null findOneBy(array $criteria, array $orderBy = null)
 * @method AmendmentAttachment[]    findAll()
 * @method AmendmentAttachment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AmendmentAttachmentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AmendmentAttachment::class);
    }

    // /**
    //  * @return AmendmentAttachment[] Returns an array of AmendmentAttachment objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AmendmentAttachment
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
