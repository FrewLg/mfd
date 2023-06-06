<?php

namespace App\Repository;

use App\Entity\CallForProposal;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Security;

/**
 * @method CallForProposal|null find($id, $lockMode = null, $lockVersion = null)
 * @method CallForProposal|null findOneBy(array $criteria, array $orderBy = null)
 * @method CallForProposal[]    findAll()
 * @method CallForProposal[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CallForProposalRepository extends ServiceEntityRepository
{
    private $security;
    public function __construct(ManagerRegistry $registry, Security $security)
    {
        parent::__construct($registry, CallForProposal::class);
        $this->security = $security;
    }

    // /**
    //  * @return CallForProposal[] Returns an array of CallForProposal objects
    //  */

    public function findByWorkunit($value)
    {

        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    public function findActiveApproved()
    {

        return $this->createQueryBuilder('c')
            ->andWhere('c.deadline  > :date')
            ->andWhere('c.approved = :approved')
            ->setParameter('date', new \DateTime())
            ->setParameter('approved', 1)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }
    public function findAllByTheme($call,$college)
    {

        return $this->createQueryBuilder('c')
            ->andWhere('c.college = :college')
            ->andWhere('c.thematic_area = :approved')
            ->setParameter('date', new \DateTime())
            ->setParameter('approved', 1)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult();
    }



    public function getCalls($filter = [])
    {
        $qb = $this->createQueryBuilder('c');

        if (!in_array("ROLE_SUPER_ADMIN",$this->security->getUser()->getRoles())){
        if(isset($filter['college']))
            $qb->andWhere('c.college = :college')
                ->setParameter('college', $filter['college']);
        }
        if(isset($filter['approved'])){
            $qb->andWhere('c.approved = :approved')
                ->setParameter('approved', $filter['approved']);
        }
        return $qb
            ->orderBy("c.id", "DESC")
            ->getQuery();
 
        }

        public function submissionByThemeCall($value)
        {
            $qb= $this->createQueryBuilder('c');
            // ->select("count(s.id)");
            $userpublication = $qb
            // ->select('t.name as name , s.title as title ,  u.username')
            ->innerJoin('c.thematicArea', 't')
            ->innerJoin('c.submissions', 's')
            ->leftJoin('s.callForProposal','sc')

            ->andWhere('c.id = :call')
            ->andWhere('sc.id     =:id')
             
            ->setParameter('id', $value)
            ->setParameter('call', $value)
            // ->groupBy('t.id')
            ->getQuery()->getResult();
    
            return   $userpublication 
            ;
        }  

      
    
}
