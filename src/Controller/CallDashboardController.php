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
 * @Route("/call/dashboard")
 */

class CallDashboardController extends AbstractController {

    /**
     * @Route("/{id}/all/", name="aadashboard", methods={"GET","POST"})
     */
    public function dashboard(): Response {
        // $this->denyAccessUnlessGranted('assn_clg_cntr');

        $entityManager = $this->getDoctrine()->getManager();

        $querytwo = $entityManager->createQuery(
            'SELECT   s.id,  c.confirmed
                     FROM App:CoAuthor c
                         JOIN c.submission s
                       WHERE
                    c.confirmed =:confirmation and s.complete=:completed and  c.confirmed is NOT NULL
                    and s.id=c.submissionid
                      GROUP BY s.id
             '

        )
            ->setParameter('confirmation', 1)
            ->setParameter('completed', 'completed');
        $recepients = $querytwo->getResult();

        dd($recepients);

        return $this->render('dashboard/index.html.twig', []);
    }

    /**
     * @Route("/{id}/theme/", name="theme", methods={"GET","POST"})
     */
    public function theme(CallForProposal $call): Response {
        $this->denyAccessUnlessGranted('view_dashboard');

        $entityManager = $this->getDoctrine()->getManager();

        $thiscollege = $this->getUser()->getUserInfo()->getCollege();
        $submissionbytheme = $entityManager->getRepository(ThematicArea::class)->findBy(['college' => $thiscollege]);

        return $this->render('dashboard/bytheme.html.twig', [
            'thematic_areas' => $submissionbytheme,
            'colleges' => $submissionbytheme,
            // 'sub_by_departments'=>$recepients,
        ]);
    }

    /**
     * @Route("/{id}/theme/bycoll", name="theme_by_coll", methods={"GET","POST"})
     */
    public function themeBycoll(CallForProposal $callForProposal): Response {
        $this->denyAccessUnlessGranted('view_dashboard');

        return $this->render('dashboard/bytheme.html.twig', [
            'thematic_areas' => $callForProposal->getThematicArea(),
            'allowedCall' => $callForProposal,
        ]);
    }

    /**
     * @Route("/{id}/", name="call_dashboard", methods={"GET","POST"})
     */
    public function index(Request $request, SubmissionRepository $submissionRepository, PaginatorInterface $paginator, CallForProposal $call): Response {
        $this->denyAccessUnlessGranted('view_dashboard'); 
        $formFilter = $this->createForm(SubmissionFilterType::class);
        $formFilter->handleRequest($request); 
        $Allsubmissions = $submissionRepository->getSubmissions();

        $entityManager = $this->getDoctrine()->getManager();
       

        $submissions = $entityManager->getRepository(Submission::class)->findAll();
        $submissionbytheme = $entityManager->getRepository(ThematicArea::class)->findBy(['college' => $this->getUser()->getUserInfo()->getCollege()]);
        $allcalls = $entityManager->getRepository(CallForProposal::class)->getCalls(['college' => $this->getUser()->getUserInfo()->getCollege()]);
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
            JOIN b.callForProposal c

          where  u.is_reviewer  is NULL and   c.id=:call  GROUP BY u.id
        '
        )
        ->setParameter('call', $call);

        $recepients = $query->getResult();

        #######################
        $query2 = $entityManager->createQuery(
            'SELECT   count(b.id) as subs,  count(u.id) as review_assignment
                    FROM App:ReviewAssignment s
                    JOIN s.reviewer u
                    JOIN u.userInfo pi
                    JOIN s.submission b
                    JOIN b.callForProposal c

                  where  u.is_reviewer =:external and c.id=:call  GROUP BY u.id
                '
        )
        ->setParameter('call', $call)
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

                    JOIN s.submission b 
                    JOIN b.callForProposal c

                    WHERE c.id=:call   GROUP BY s.remark
                '
        )
        ->setParameter('call', $call);

        $remark = $query3->getScalarResult();
        ################################
        #######################
        $query4 = $entityManager->createQuery(
            'SELECT  i.gender as Gender, count(s.id)  as Proposals
                    FROM App:User u
                    JOIN u.submissions s
                    JOIN s.callForProposal c

                    JOIN u.userInfo i
WHERE c.id=:call
                      GROUP BY i.gender
                '
        )
        ->setParameter('call', $call);
        $remark2 = $query4->getScalarResult();
       
        #######################
        $res = $entityManager->createQuery(
            "SELECT  DISTINCT   count(u.id)  as copis , cl.name as college,  cl.id
            FROM App:CoAuthor u
            JOIN u.submission s 
            JOIN s.callForProposal c
            JOIN u.researcher i 
            JOIN  i.userInfo n 
            JOIN  n.college cl 
            WHERE c.id=:call  
            GROUP BY cl.id
            ORDER BY cl.id

               "
        ) 
        ->setParameter('call', $call);
        $copisdist = $res->getScalarResult();
       
        // dd($coa);
              ####Grant by call according to call ###################
              $awarded = $entityManager->createQuery(
                'SELECT  u.name as theme,  count(s.id)  as proposals 
                        FROM App:ThematicArea u
                        JOIN u.submissions s 
                        JOIN s.callForProposal c 
                        WHERE c.id=:call and  s.awardgranted=:awarded
                        GROUP BY u.id
                    '
            )
            ->setParameter('awarded', 1)
            ->setParameter('call', $call);
    
            $awardedresult = $awarded->getArrayResult();
// dd($awardedresult);

        return $this->render('dashboard/call-dashboard.html.twig', [
            'formFilter' => $formFilter->createView(),
            'submissions' => $Allsubmissions,
            'bythemes' => $submissionbytheme,
            'allcalls' => $allcallsp,
            'submissions' => $submissions,
            'copis' => $copis,
            'desision' => $remark,
            'copisdist' => $copisdist,
            'gender_distribution' => $remark2,
            'awardedresult' => $awardedresult,
            'all' => $all,
            'allowedCall' => $call,
            'allext' => $allext,
        ]);
    }

    /**
     * @Route("/allassigned-rev", name="allassigned", methods={"GET","POST"})
     */
    public function allassigned(Request $request, PaginatorInterface $paginator) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        #######################
        $query3 = $entityManager->createQuery(
            'SELECT DISTINCT b.id ,  b.title   ,b.sent_at as sentAt, b.complete, i.first_name as firstName, i.midle_name, i.last_name
            FROM App:Review s
            JOIN s.submission b

            JOIN b.author a
            JOIN a.userInfo i

            WHERE s.remark=:remark
        '
        )
            ->setParameter('remark', 3);

        $rejecteds = $query3->getResult();

        ################################
        $Allsubmissions = $paginator->paginate(
            // Doctrine Query, not results
            $rejecteds,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $info = 'All Accepted with minor revision';
        ################################
        return $this->render('dashboard/submissions.html.twig', [
            'submissions' => $Allsubmissions,
            'info' => $info,
        ]);
    }

    /**
     * @Route("/rejecteds", name="allrejected", methods={"GET","POST"})
     */
    public function allrejected(Request $request, PaginatorInterface $paginator) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        #######################
        $query3 = $entityManager->createQuery(
            'SELECT DISTINCT  b.id ,  b.title   ,b.sent_at as sentAt, b.complete, i.first_name as firstName, i.midle_name, i.last_name
            FROM App:Review s
            JOIN s.submission b

            JOIN b.author a
            JOIN a.userInfo i

            WHERE s.remark=:remark AND  NOT s.remark=:remark1 AND NOT s.remark=:remark2

        '
        )

            ->setParameter('remark', 1)
            ->setParameter('remark1', 4)
            ->setParameter('remark2', 3);

        $rejecteds = $query3->getResult();

        ######################
        $Allsubmissions = $paginator->paginate(
            // Doctrine Query, not results
            $rejecteds,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $info = 'All rejected';
        ################################
        return $this->render('dashboard/submissions.html.twig', [
            'submissions' => $Allsubmissions,
            'info' => $info,
        ]);
    }

    /**
     * @Route("/minor-rev", name="minor_rev", methods={"GET","POST"})
     */
    public function minorrev(Request $request, PaginatorInterface $paginator) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        #######################
        $query3 = $entityManager->createQuery(
            'SELECT  DISTINCT b.id ,  b.title   ,b.sent_at as sentAt, b.complete, i.first_name as firstName, i.midle_name, i.last_name
            FROM App:Review s
            JOIN s.submission b

            JOIN b.author a
            JOIN a.userInfo i

            WHERE s.remark=:remark
        '
        )
            ->setParameter('remark', 3);

        $rejecteds = $query3->getResult();

        ################################
        $Allsubmissions = $paginator->paginate(
            // Doctrine Query, not results
            $rejecteds,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $info = 'All Accepted with minor revision';
        ################################
        return $this->render('dashboard/submissions.html.twig', [
            'submissions' => $Allsubmissions,
            'info' => $info,
        ]);
    }

    /**
     * @Route("/allaccepteds", name="allaccssepted", methods={"GET","POST"})
     */
    public function allaccepted(Request $request, PaginatorInterface $paginator) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        $filterform = $this->createFormBuilder()->add("status", ChoiceType::class, [
            "multiple" => true,
            "required" => true,
            "expanded" => true,

            "choices" => [
                "Accepted" => Constants::SUBMISSION_STATUS_ACCEPTED,
                "Accepted with major revision" => Constants::SUBMISSION_STATUS_ACCEPTED_WITH_MAJOR_REVISION,
                "Accepted with minor revision" => Constants::SUBMISSION_STATUS_ACCEPTED_WITH_MINOR_REVISION,
                "Decline" => Constants::SUBMISSION_STATUS_DECLINED,
            ],
        ])->getForm();
        $status = $request->query->get("status");

        $filterform->handleRequest($request);
        if ($filterform->isSubmitted() && $filterform->isValid()) {

            $submissions = $this->getDoctrine()->getRepository(Submission::class)->getSubmissions($filterform->getData()['status']);
            if ($request->query->get("export")) {
            }
        } else {
            $submissions = $this->getDoctrine()->getRepository(Submission::class)->getSubmissions();
        }

        $Allsubmissions = $paginator->paginate(
            $submissions,
            $request->query->getInt('page', 1),
            10,
            array('wrap-queries' => true)
        );
        $info = 'All Accepted';
        return $this->render('dashboard/submissions.html.twig', [
            'submissions' => $Allsubmissions,
            'info' => $info,
            'filterform' => $filterform->createView(),
        ]);
    }

    /**
     * @Route("/major-rev", name="all_minor", methods={"GET","POST"})
     */
    public function allminor(Request $request, PaginatorInterface $paginator) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();

        $allrej = $entityManager->createQuery(
            "SELECT DISTINCT     b.id from App:Review r
            JOIN r.submission b

            where r.remark in ('1','4')

            group by b.id having count(r.remark) >=1
"
        )
        //  ->setParameter('remark', 2)
        ;
        $rejecteds = $allrej->getResult();
        dd($rejecteds);
        $Allsubmissions = $paginator->paginate(
            // Doctrine Query, not results
            $rejecteds,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        $info = 'All Accepted with major revision';
        ################################
        return $this->render('dashboard/submissions.html.twig', [
            'submissions' => $Allsubmissions,
            'info' => $info,
        ]);
    }

    /**
     * @Route("/{id}/research-theams", name="callresearch_theams", methods={"GET","POST"})
     */
    public function allresearchers(Request $request, CallForProposal $call, PaginatorInterface $paginator) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();

        $submission = $em->getRepository('App:Submission')->getSubmissions(['callForProposal'=>$call]);
        $Allsubmissions = $paginator->paginate(
            // Doctrine Query, not results
            $submission,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );

        return $this->render('dashboard/test.html.twig', [
            'submissions' => $Allsubmissions,
            'call' => $call,
        ]);
    }
}
