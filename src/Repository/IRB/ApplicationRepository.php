<?php

namespace App\Repository\IRB;

use App\Entity\IRB\Application;
use DoctrineExtensions\Query\Mysql\DateFormat;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Application|null find($id, $lockMode = null, $lockVersion = null)
 * @method Application|null findOneBy(array $criteria, array $orderBy = null)
 * @method Application[]    findAll()
 * @method Application[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ApplicationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Application::class);
    }

    // /**
    //  * @return Application[] Returns an array of Application objects
    //  */
  
    public function getData($filter=[],$user=null,$active=false)
    {
        $qb= $this->createQueryBuilder('a');

        if (isset($filter['projectType']) and sizeof($filter['projectType']) > 0) {


            $qb->andWhere("a.projectType in  (:projectType)")
                ->setParameter("projectType", $filter['projectType']);
        }
        if (isset($filter['type']) and $filter['type']) {


            $qb->andWhere("a.type =  :type")
                ->setParameter("type", $filter['type']);
        }
        if (isset($filter['startDate'])  && $filter['startDate']) {
            $date = $this->exploadeDates($filter['startDate']);

            // dd($date);
            $qb->andWhere("a.startDate <= '" . $date[1] . "'");
            $qb->andWhere("a.startDate >= '" . $date[0]. "'");
        }
        if (isset($filter['endDate'])  && $filter['endDate']) {
            $date = $this->exploadeDates($filter['endDate']);

            // dd($date);
            $qb->andWhere("a.endDate <= '" . $date[1] . "'");
            $qb->andWhere("a.endDate >= '" . $date[0] . "'");
        }
        if (isset($filter['submittedAt'])  && $filter['submittedAt']) {
            $date = $this->exploadeDates($filter['submittedAt']);

            // dd($date);
            $qb->andWhere("a.submittedAt <= '" . $date[1] . "'");
            $qb->andWhere("a.submittedAt >= '" . $date[0] . "'");
        }
        if($user){
            
            $qb->andWhere("a.submittedBy = :user")->setParameter('user',$user);
        }elseif ($active) {
            $qb->andWhere("a.status in (:status)")->setParameter('status',[1,2,3,4]);
        }

      
        if (isset($filter['title']) && $filter['title']) {
      
            $qb->andWhere("a.title LIKE '%" . $filter['title'] . "%'");
        }
        if (isset($filter['location']) && $filter['location']) {
      
            $qb->andWhere("a.location LIKE '%" . $filter['location'] . "%'");
        }
            // ->andWhere('i.exampleField = :val')
            // ->setParameter('val', $value)
            return $qb->orderBy('a.id', 'DESC')
          
            ->getQuery();
    }


   public function exploadeDates($input_date)
   {

    $date = explode(" - ", $input_date);
    $dates[]=(new \DateTime($date[0]))->format('Y-m-d H:i:s');
    $dates[]=(new \DateTime($date[1]))->format('Y-m-d H:i:s');

    return $dates;
   }
   public function getDashboardData()
   {

    // DateFormat::cla
      $qb = $this
      ->createQueryBuilder('e')
      ->select('DATE_FORMAT(e.createdAt, \'%Y-%m\'), sum(e.id) apps')
      ->groupBy('DATE_FORMAT(e.createdAt, \'%Y-%m\')');
    return $qb->getQuery()->getResult();
   }

   

    
   
    
}
