<?php

namespace App\Controller\IRB;

use App\Entity\EditorialDecision;
use App\Entity\IRB\IRBReviewAssignment;
use App\Form\IRB\IRBReviewAssignmentType;
use App\Repository\IRBReviewAssignmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use App\Entity\IRB\IRBReview;
use App\Entity\GuidelineForIrbreviewer;
use App\Form\GuidelineForIrbreviewerType;
use App\Repository\GuidelineForIrbreviewerRepository;
use Symfony\Component\Form\Extension\Core\Type\RadioType;
use App\Form\IRB\IRBReviewType;
use Symfony\Component\Form\Extension\Core\Type\DateType; 
use Symfony\Component\Form\Extension\Core\Type\CoiceType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use App\Entity\IRB\Application; 
use App\Repository\ApplicationRepository;
use App\Repository\InstitutionalIrbreviewersBoardRepository;
use App\Repository\ReviewRepository;
use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use App\Entity\InstitutionalIrbreviewersBoard;
use App\Entity\IrbCertificate;
use App\Helper\ReviewHelper;
use App\Repository\EvaluationFormRepository;
use App\Utils\Constants;
use DateTime;
use FOS\CKEditorBundle\Form\Type\CKEditorType;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Lexik\Bundle\TranslationBundle\Util\Csrf\CsrfCheckerTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
// use Lexik\Bundle\TranslationBundle\Util\Csrf\CsrfCheckerTrait;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Security\Csrf\CsrfToken;

/**
 * @Route("/irb")
 */
class IrbController extends AbstractController
{
    use CsrfCheckerTrait;   
  

    /**
     * @Route("/myassignedirb", name="myassignedirb", methods={"GET"})
     */
    public function myassigned(Request $request, PaginatorInterface $paginator): Response {
        
        $entityManager = $this->getDoctrine()->getManager();
        $me = $this->getUser()->getId();
        $this_is_me = $this->getUser();
        $myassigned = $entityManager->getRepository(IRBReviewAssignment::class)->findBy(['irbreviewer' => $this_is_me, 'closed' => NULL , 'Declined' => NULL ],["id"=>"DESC"]);
        ////// if no throw exception
        $myassigneds = $paginator->paginate(
            // Doctrine Query, not results
            $myassigned,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
#################################################

$all = $entityManager->getRepository(IRBReviewAssignment::class)->findBy(['irbreviewer' => $this_is_me],["id"=>"DESC"]);
 

#################################################

$entityManager = $this->getDoctrine()->getManager();  
#######################

        #######################
        $query3 = $entityManager->createQuery(
        'SELECT    b.id , ass.invitation_sent_at as InvitationSentAt,     ass.Declined as Declined,  b.title , s.createdAt  , ass.duedate  as dueDate 
        FROM App\Entity\IRB\IRBReview s  
        JOIN s.application b     
        JOIN s.iRBReviewAssignment ass      
        WHERE   s.reviewed_by=:irbreviewer AND ass.inactive_assignment is NULL AND ass.closed=:closed
 
        ')  
        ->setParameter('closed', 1 )  
        ->setParameter('irbreviewer', $this_is_me   ) 
        ;

        $closeds = $query3->getResult();


#################################################

        return $this->render('application/myassigned.html.twig', [
            'closeds' => $closeds,
            'all'=>$all,
            'myreviews' => $myassigneds,
        ]);
    }
    /**
     * @Route("/clearances", name="clearances", methods={"GET"})
     */
    public function clearances(Request $request, PaginatorInterface $paginator): Response {
        
        $entityManager = $this->getDoctrine()->getManager();
        $me = $this->getUser()->getId();
        $this_is_me = $this->getUser();
        $myassigned = $entityManager->getRepository('App\Entity\IrbCertificate'::class)->findAll();
        ////// if no throw exception
        $myassigneds = $paginator->paginate(
            // Doctrine Query, not results
            $myassigned,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        ); 

        return $this->render('application/certs.html.twig', [
            
            'certs' => $myassigneds,
        ]);
    }
    /**
     * @Route("/approve{id}", name="approve", methods={"GET"})
     */
    public function approve( IrbCertificate $cert): Response {
        
        $entityManager = $this->getDoctrine()->getManager(); 
        $cert->setApprovedAt(new \DateTime());
        $cert->setApprovedBy($this->getUser());
        // 
        $entityManager->persist($cert);
        $entityManager->flush();

        $this->addFlash(
            'success',
            ' Certificate approved!'

        );

        return $this->redirectToRoute('clearances');
         
        
    }

     

    /**
     * @Route("/{id}/irbassigned", name="his_assignment", methods={"GET"})
     */
    public function allassigned(Request $request, User $user, PaginatorInterface $paginator): Response {
        
        $entityManager = $this->getDoctrine()->getManager();
         
        $myassigned = $entityManager->getRepository(IRBReviewAssignment::class)->findBy(['irbreviewer' => $user  ],["id"=>"DESC"]);
        ////// if no throw exception
        $myassigneds = $paginator->paginate(
            // Doctrine Query, not results
            $myassigned,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
#################################################

#################################################

        return $this->render('review_assignment/assigned.html.twig', [
            'user' => $user,
            'myreviews' => $myassigneds,
        ]);
    }


    /**
     * @Route("/{id}/irbrevise", name="reviewapplication", methods={"GET","POST"})
     */
    public function revise(Request $request,  IRBReviewAssignment $iRBReviewAssignment, EvaluationFormRepository $evaluationFormRepository): Response {
        ////Ultimate irbreviewers page
        
        $entityManager = $this->getDoctrine()->getManager();
        $me = $this->getUser()->getId();
        $applicationOfirbreviewer = $entityManager->getRepository(IRBReviewAssignment::class)->find($iRBReviewAssignment);
        $metoo = $this->getUser();
        $me_as_a_irbreviewer = $applicationOfirbreviewer->getIrbreviewer()->getId();
        $applications = $applicationOfirbreviewer->getApplication();
        $editorialDecisions = $entityManager->getRepository(EditorialDecision::class)->find($applications);
        #dd($me_as_a_irbreviewer.$me);
        $iamairbreviewers = $entityManager->getRepository(IRBReviewAssignment::class)->findBy(['application' => $applications, 'irbreviewer' => $metoo]);

        // $myassigned =  $entityManager->getRepository(IRBReviewAssignment::class)->findBy($iRBReviewAssignment);
        #######################
        if ($iRBReviewAssignment->getClosed() == 1) {
            return $this->redirectToRoute('myassignedirb');

        }
        #######################
        foreach ($applicationOfirbreviewer as $muke) {
            $dd = $muke->getIrbreviewer()->getId();
            echo $dd; #=  $muke->getIrbreviewer()->getId();

            $lala = $dd . 'compare' . $me;
            /////////
            $me = $this->getUser()->getId();
            $theirbreviewerone = $iRBReviewAssignment->getIrbreviewer()->getId();
            if ($dd == $me) {

                return $this->redirectToRoute('myreviews');
                $this->addFlash(
                    'danger',
                    'Sorry you' . $dd . '//' . $me . ' never been assigned to this submision!'

                );

            }

            /////
        }
        $measairbreviewer = $this->getUser();
        $author = $applications->getAuthor();
        //   $reviews=$entityManager->getRepository(IRBReview::class)->findBy(['application' => $applications ] );

        if ($measairbreviewer == $author) {
            ////if you are the author then you can't review it///////
            $this->addFlash(
                'warining',
                'You can not see the application you made in this page!'
            );
            return $this->redirectToRoute('myreviews');
        }

        if ($iRBReviewAssignment->getReassigned()==1 ) {
            ////if you are the author then you can't review it///////
            $this->addFlash(
                'warining',
                'You have been re-assigned!'
            );
            return $this->redirectToRoute('rereviewapplication', array('id' => $iRBReviewAssignment->getId()));
        } 
        $review = new IRBReview();
        $review->setIRBReviewAssignment($iRBReviewAssignment);
        $review->setApplication($iRBReviewAssignment->getApplication());
        $review->setReviewedBy($measairbreviewer);

        // $review = new Review();
        $form = $this->createForm(IRBReviewType::class, $review);
        $form->handleRequest($request); 
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            // $reviewfile = $form->get('attachment')->getData();
            // if ($reviewfile == "") {
            //     $this->addFlash(
            //         'danger',
            //         'Review file  not uploaded!'
            //     );
            // } else {
            //     $reviewfile = $form->get('attachment')->getData();
            //     $Areviewfile = md5(uniqid()) . '.' . $reviewfile->guessExtension();
            //     $reviewfile->move($this->getParameter('review_files'), $Areviewfile);
            //     $review->setAttachment($Areviewfile);
            // }
            ##########
            $reviewfile2 = $form->get('evaluation_attachment')->getData();
            if ($reviewfile2 == "") {
                $this->addFlash(
                    'danger',
                    'Evaluation  file  not uploaded!'
                );
            } else {
                $reviewfile2 = $form->get('evaluation_attachment')->getData();
                $Areviewfile2 = md5(uniqid()) . '.' . $reviewfile2->guessExtension();
                $reviewfile2->move($this->getParameter('review_files'), $Areviewfile2);
                $review->setEvaluationAttachment($Areviewfile2);
            }
            ###############
            $review->setCreatedAt(new \DateTime());
            $review->setReviewedBy($this->getUser());
            $iRBReviewAssignment->setClosed(1); 
            $entityManager->persist($review);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'You have  completed a revision successfully!'
            );
            return $this->redirectToRoute('reviewapplication', array('id' => $iRBReviewAssignment->getId()));
        }

        $editorialDecision = new EditorialDecision();
        $editorialDecisionform = $this->createFormBuilder($editorialDecision)
      
            ->add('feedback', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Feedback  for the author',
                    'required' => true,
                    'class' => 'form-control',
                )))
            ->getForm();
        $editorialDecisionform->handleRequest($request); 
        $reviews = $entityManager->getRepository(IRBReview::class)->findBy(['application' => $iRBReviewAssignment->getApplication(), 'reviewed_by' => $measairbreviewer]);
   return $this->render('application/review_byirbreviewer.html.twig', [
            'review_assignment' => $iRBReviewAssignment,
            'review_assignments' => $reviews,
            'application' => $applications,
            'editorialDecisions' => $editorialDecisions,
            'editorialDecisionform' => $editorialDecisionform->createView(),
            'form' => $form->createView(),
            'evaluationForms' => $evaluationFormRepository->findBy(['parent' => null]),
        ]);
    }
    
    /**
     * @Route("/{id}/rereviseapp", name="rereviewapp", methods={"GET","POST"})
     */
    public function rerevise(Request $request, IRBReviewAssignment $iRBReviewAssignment, EvaluationFormRepository $evaluationFormRepository): Response {
        ////Ultimate irbreviewers page
        
        $entityManager = $this->getDoctrine()->getManager();
        $me = $this->getUser()->getId();
        // $id=  $review->getIRBReviewAssignment()->getId();  
        $applicationOfirbreviewer = $entityManager->getRepository(IRBReviewAssignment::class)->find($iRBReviewAssignment);
        $metoo = $this->getUser();
        $me_as_a_irbreviewer = $applicationOfirbreviewer->getIrbreviewer()->getId();
        $applications = $applicationOfirbreviewer->getApplication();
        $editorialDecisions = $entityManager->getRepository(EditorialDecision::class)->find($applications);
        #dd($me_as_a_irbreviewer.$me);
        $iamairbreviewers = $entityManager->getRepository(IRBReviewAssignment::class)->findBy(['application' => $applications, 'irbreviewer' => $metoo]);

        // $myassigned =  $entityManager->getRepository(IRBReviewAssignment::class)->findBy($iRBReviewAssignment);
        #######################
        if ($iRBReviewAssignment->getClosed() == 1) {
            return $this->redirectToRoute('myassignedirb');

        }
        #######################
        foreach ($applicationOfirbreviewer as $muke) {
            $dd = $muke->getIrbreviewer()->getId();
            echo $dd; #=  $muke->getIrbreviewer()->getId();

            $lala = $dd . 'compare' . $me;
            /////////
            $me = $this->getUser()->getId();
            $theirbreviewerone = $iRBReviewAssignment->getIrbreviewer()->getId();
            if ($dd == $me) {

                return $this->redirectToRoute('myreviews');
                $this->addFlash(
                    'danger',
                    'Sorry you' . $dd . '//' . $me . ' never been assigned to this submision!'

                );

            }

            /////
        }
        $measairbreviewer = $this->getUser();
        $author = $applications->getAuthor();
        //   $reviews=$entityManager->getRepository(IRBReview::class)->findBy(['application' => $applications ] );

        if ($measairbreviewer == $author) {
            ////if you are the author then you can't review it///////
            $this->addFlash(
                'warining',
                'You can not see the application you made in this page!'
            );
            return $this->redirectToRoute('myreviews');
        }
        // $review = new Review();
         
        $reviewid = $entityManager->getRepository(IRBReview::class)->findOneBy(['iRBReviewAssignment'=>$iRBReviewAssignment->getId(), 'reviewed_by'=> $this->getUser()]);
        $review = $entityManager->getRepository(IRBReview::class)->find($reviewid);

        $review->setIRBReviewAssignment($iRBReviewAssignment);
        $review->setApplication($iRBReviewAssignment->getApplication());
        $review->setReviewedBy($measairbreviewer);

        // $review = new Review();
        $form = $this->createForm(IRBReviewType::class, $review);
        $form->handleRequest($request);
 
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $reviewfile = $form->get('attachment')->getData();
            if ($reviewfile == "") {
                $this->addFlash(
                    'danger',
                    'Review  file  not uploaded!'
                );
            } else {
                $reviewfile = $form->get('attachment')->getData();
                $Areviewfile = md5(uniqid()) . '.' . $reviewfile->guessExtension();
                $reviewfile->move($this->getParameter('review_files'), $Areviewfile);
                $review->setAttachment($Areviewfile);
            }

              ##########
              $reviewfile2 = $form->get('evaluation_attachment')->getData();
              if ($reviewfile2 == "") {
                  $this->addFlash(
                      'danger',
                      'Evaluation  file  not uploaded!'
                  );
              } else {
                  $reviewfile2 = $form->get('evaluation_attachment')->getData();
                  $Areviewfile2 = md5(uniqid()) . '.' . $reviewfile2->guessExtension();
                  $reviewfile2->move($this->getParameter('review_files'), $Areviewfile2);
                  $review->setEvaluationAttachment($Areviewfile2);
              }
              ###############

            $review->setCreatedAt(new \DateTime());
            $review->setReviewedBy($this->getUser());
            $iRBReviewAssignment->setClosed(1);

            $entityManager->persist($review);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'You have  completed a revision successfully!'
            );
            return $this->redirectToRoute('reviewapplication', array('id' => $iRBReviewAssignment->getId()));
        }

        $editorialDecision = new EditorialDecision();
        $editorialDecisionform = $this->createFormBuilder($editorialDecision)
      
            ->add('feedback', TextareaType::class, array(
                'attr' => array(
                    'placeholder' => 'Feedback  for the author',
                    'required' => true,
                    'class' => 'form-control',
                )))
            ->getForm();
        $editorialDecisionform->handleRequest($request);


       
        $reviews = $entityManager->getRepository(IRBReview::class)->findBy(['application' => $iRBReviewAssignment->getApplication(), 'reviewed_by' => $measairbreviewer]);

        return $this->render('application/review_byirbreviewer.html.twig', [
            'review_assignment' => $iRBReviewAssignment,
            'review_assignments' => $reviews,
            'application' => $applications,
            'editorialDecisions' => $editorialDecisions,
            'editorialDecisionform' => $editorialDecisionform->createView(),
            'form' => $form->createView(),
            'evaluationForms' => $evaluationFormRepository->findBy(['parent' => null]),
        ]);
    }
 
    

      
  /**
     * @Route("/{id}/IRBdecline/", name="irbdecline_invitation", methods={"GET","POST"})
     */
    public function declineinvitation(Request $request, IRBReviewAssignment $iRBReviewAssignment): Response
    {
        

	$entityManager = $this->getDoctrine()->getManager();
    $mew= $this->getUser()->getId();
	$deadline= $iRBReviewAssignment->getDuedate();
	$today= new \DateTime();
	$message='';
 	if ($deadline<=$today){
 	$message="Overdue!";
#	echo $day;
	}
	////if he is not the one he has been assigned to this proposal then redirect the page to the list of the application he hasd been assigned to
	$iRBReviewAssignment->setDeclined(1);
	$this->getDoctrine()->getManager()->flush();
	$this->addFlash( 
            'danger',
            'You declined your review invitation. The process will not be undone!'
        ); 
	return $this->redirectToRoute('myreviews');
}
    /**
     * @Route("/{id}", name="irbunassign", methods={"DELETE", "GET","POST"})
     */
    public function unassign(Request $request, IRBReviewAssignment $iRBReviewAssignment  ): Response
    {
        $this->denyAccessUnlessGranted('assn_clg_cntr');

        if ($this->isCsrfTokenValid('delete'.$iRBReviewAssignment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->remove($iRBReviewAssignment);
            $iRBReviewAssignment->setInactiveAssignment(1);
              $this->addFlash(
            'success',
            'Irbreviewer unassigned!'
        ); 
            $entityManager->flush();
        }
            return $this->redirectToRoute('review_assignment_new', array('id'=>$iRBReviewAssignment->getApplication()->getId()));
   }
 
   /**
     * @Route("/{id}", name="irbreassign", methods={"DELETE", "GET","POST"})
     */
    public function reassign( IRBReviewAssignment $iRBReviewAssignment  ): Response
    {
        $this->denyAccessUnlessGranted('assn_clg_cntr');

             $entityManager = $this->getDoctrine()->getManager();
            // $entityManager->remove($iRBReviewAssignment);
            $iRBReviewAssignment->setInactiveAssignment(NULL);
            $iRBReviewAssignment->setClosed(NULL);
            $iRBReviewAssignment->setReassigned(1);
            
              $this->addFlash(
            'success',
            'Irbreviewer allowed to edit the review  successfully!'
        ); 
            $entityManager->flush();
        
            return $this->redirectToRoute('review_assignment_new', array('id'=>$iRBReviewAssignment->getApplication()->getId()));
 
    }
 

  /**
     * @Route("/{id}/accept/", name="accept_irb_invitation", methods={"GET","POST"})
     */
    public function acceptinvitation(Request $request, IRBReviewAssignment $iRBReviewAssignment): Response
    {
        

    $entityManager = $this->getDoctrine()->getManager();
    if($this->getUser() != $iRBReviewAssignment->getIrbreviewer()){
        
        $this->addFlash("danger", "Sorry you are not allowed for this service !" );
        return $this->redirectToRoute('myassignedirb');
    }
   
    if(  $iRBReviewAssignment->getDeclined()==1){
       
        
        $this->addFlash("danger", "Sorry invitation has declined !" );

        return $this->redirectToRoute('myassignedirb');
    }
    
    if ($iRBReviewAssignment->getIsAccepted()){
        return $this->redirectToRoute('reviewapplication', array('id' => $iRBReviewAssignment->getId()));
}
    if ($iRBReviewAssignment->getIsRejected()){
        // echo"'dsada'";
        // dd();
        return $this->redirectToRoute('myassignedirb');
}

if ($iRBReviewAssignment->getIsRejected()){
    // echo"'dsada'";
    // dd();
    return $this->redirectToRoute('rereviewapplication' );
}
    $application=$iRBReviewAssignment->getApplication();
    $workunit=$iRBReviewAssignment->getApplication();
	$guideline_for_irbreviewers = $entityManager->getRepository(GuidelineForIrbreviewer::class)->findAll()[0];
	$Allapplication = $entityManager->getRepository(Application::class)->findBy(['id' => $application ] );
	$deadline= $iRBReviewAssignment->getDuedate();
	$today= new \DateTime();
	$message='';
 	if ($deadline<=$today){
        
        $this->addFlash("danger", "Sorry Invitation overdue !" );

        //  $this->addFlash('error',"!!");
        return $this->redirectToRoute('myassignedirb');
}
 
        if ($request->request->get('accept-invitation')) {
            $this->checkCsrf('accept-invitation');
            $iRBReviewAssignment->setAcceptedAt(new DateTime());
            $this->getDoctrine()->getManager()->flush();
            return $this->redirectToRoute('reviewapplication', array('id' =>$iRBReviewAssignment->getId()));
        }
 
	return $this->render('review_assignment/accept_invitation.html.twig', [
	'review_assignment' => $iRBReviewAssignment,
	'guideline' => $guideline_for_irbreviewers,
        ]);
    }  
 
}
 
 
