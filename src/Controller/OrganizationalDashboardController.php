<?php

namespace App\Controller;

use App\Entity\CallForProposal;
use App\Entity\CoAuthor;
use App\Entity\Submission;
use App\Entity\ThematicArea;
use App\Filter\Type\SubmissionFilterType;
use App\Repository\SubmissionRepository;
use App\Utils\Constants;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/visualization")
 */

class OrganizationalDashboardController extends AbstractController {
 
    /**
     * @Route("/", name="orgdashboard", methods={"GET","POST"})
     */
    public function index(Request $request, SubmissionRepository $submissionRepository, PaginatorInterface $paginator, FilterBuilderUpdaterInterface $query_builder_updater): Response {
        $this->denyAccessUnlessGranted('view_dashboard');
         $formFilter = $this->createForm(SubmissionFilterType::class);
        $formFilter->handleRequest($request);
         $Allsubmissions = $submissionRepository->getSubmissions();

        $entityManager = $this->getDoctrine()->getManager();
        

        $submissions = $entityManager->getRepository(Submission::class)->findAll();
        $submissionbytheme = $entityManager->getRepository(ThematicArea::class)->findBy(['college' => $this->getUser()->getUserInfo()->getCollege()]);
        $allcalls = $entityManager->getRepository(CallForProposal::class)->getCalls(['college' => $this->getUser()->getUserInfo()->getCollege()]);
        $allcalls = $entityManager->getRepository(CallForProposal::class)->findBy(['college' => $this->getUser()->getUserInfo()->getCollege()]);
        $copis = $entityManager->getRepository(CoAuthor::class)->findall();

        $allcallsp = $paginator->paginate(
            // Doctrine Query, not results
            $allcalls,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );

        $query = $entityManager->createQuery(
            'SELECT    count(b.id) as subs,  count(u.id) as review_assignment
            FROM App:ReviewAssignment s
            JOIN s.reviewer u
            JOIN u.userInfo pi
            JOIN s.submission b
          where  u.is_reviewer  is NULL  GROUP BY u.id
        '
        );
        $recepients = $query->getResult();

        #######################
        $query2 = $entityManager->createQuery(
            'SELECT   count(b.id) as subs,  count(u.id) as review_assignment
                    FROM App:ReviewAssignment s
                    JOIN s.reviewer u
                    JOIN u.userInfo pi
                    JOIN s.submission b
                  where  u.is_reviewer =:external   GROUP BY u.id
                '
        )
            ->setParameter('external', 1);
        $recepientextrnal = $query2->getResult();
        ################################
        // $recepients = $querytwo->getScalarResult();
        $all = count($recepients);
        $allext = count($recepientextrnal);
        // dd(count($recepients) );

        #######################
        $query3 = $entityManager->createQuery(
            'SELECT DISTINCT s.remark as decision, count(b.id)  as proposals
                    FROM App:Review s

                    JOIN s.submission b    GROUP BY s.remark
                '
        );
        $remark = $query3->getScalarResult();
        ################################
        #######################
        $query4 = $entityManager->createQuery(
            'SELECT  i.gender as Gender, count(s.id)  as Proposals
                    FROM App:User u
                    JOIN u.submissions s
                    JOIN u.userInfo i

                      GROUP BY i.gender
                '
        );
        $remark2 = $query4->getScalarResult();
 #################publication
 ####################### $argon2id$v=19$m=65536,t=4,p=1$/04iIEjvQ0NaK7rI/WYM2w$2qFl9Wj2egStOVKHb2vGTuv1+HDnj6S0rd0Hx8RjjH4
    $res = $entityManager->createQuery(
    "SELECT  DISTINCT   count(u.id)  as copis , cl.name as college,  cl.id
    FROM App:CoAuthor u
    JOIN u.submission s 
    JOIN s.callForProposal c
    JOIN u.researcher i 
    JOIN  i.userInfo n 
    JOIN  n.college cl  
    GROUP BY cl.id
    ORDER BY cl.id
     ");
    $copisdist = $res->getScalarResult();
    #################publication
        return $this->render('dashboard/org-dashboard.html.twig', [
            'formFilter' => $formFilter->createView(),
            'submissions' => $Allsubmissions,
            'bythemes' => $submissionbytheme,
            'allcalls' => $allcallsp,
            'all_calls' => $allcalls,
            'submissions' => $submissions,
            'copis' => $copis,
            'copisdist' => $copisdist,
            'desision' => $remark,
            'gender_distribution' => $remark2,
            'all' => $all,
            'allext' => $allext,
        ]);
    }
    
}
