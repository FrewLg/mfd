<?php

namespace App\Repository;

use App\Entity\CoAuthor;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;
/**
 * @method CoAuthor|null find($id, $lockMode = null, $lockVersion = null)
 * @method CoAuthor|null findOneBy(array $criteria, array $orderBy = null)
 * @method CoAuthor[]    findAll()
 * @method CoAuthor[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CoAuthorRepository extends ServiceEntityRepository
{
    private $security;
    private $user;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, CoAuthor::class);
        $this->security = $security;
        $this->user = $this->security->getUser();
    }

    // /**
    //  * @return CoAuthor[] Returns an array of CoAuthor objects
    //  */ 

    public function isCoPI($submission, ?User $user=null)
    {
       
        $user ??= $this->user;
        return $this->createQueryBuilder('c')
            ->andWhere('c.submission = :submission')
            ->setParameter('submission', $submission)
            ->andWhere('c.researcher = :researcher')
            ->setParameter('researcher', $user)

            ->getQuery()
            ->getOneOrNullResult();
    }


    /*
    public function findOneBySomeField($value): ?CoAuthor
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
