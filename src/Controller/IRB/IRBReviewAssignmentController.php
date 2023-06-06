<?php

namespace App\Controller\IRB;

use App\Entity\IrbCertificate;
use App\Entity\IRB\Application;
use App\Entity\IRB\IRBReview;
use App\Entity\IRB\IRBReviewAssignment;
use App\Entity\IRB\IRBStatus;
use App\Entity\IRB\ReviewChecklist;
use App\Entity\IRB\ReviewChecklistGroup;
use App\Entity\IRB\ReviewerResponse;
use App\Form\IRB\ExternalIRBReviewAssignmentType;
use App\Form\IRB\IRBReviewAssignmentType;
use App\Form\IRB\IRBReviewType;
use App\Helper\MailHelper;
use App\Helper\ReviewHelper;
use App\Repository\IRB\IRBReviewAssignmentRepository;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\TranslationBundle\Util\Csrf\CsrfCheckerTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/irb/reviewer-assignment")
 */
class IRBReviewAssignmentController extends AbstractController {
    use CsrfCheckerTrait;

    /**
     * @Route("/{id}/assign", name="irb_review_assignment_new", methods={"GET","POST"})
     */
    public function assign(Request $request, MailHelper $mailHelper, Application $submission, ReviewHelper $reviewHelper, MailerInterface $mailer, IRBReviewAssignmentRepository $reviewAssignmentRepository): Response {

        // $this->denyAccessUnlessGranted('assn_clg_cntr');

        $entityManager = $this->getDoctrine()->getManager();

        if ($submission->getSubmittedBy() == $this->getUser()) {
            $this->addFlash('danger', 'Sorry! You can not assign by yourself a reviewer to the submission you made!');
            return $this->redirectToRoute('application_index');
        }
        ///// check if the submission is completed or not
        $reviewAssignment = new IRBReviewAssignment();
        $reviewAssignment->setStatus(1);
        $reviewAssignment->setApplication($submission);

         
        $messages = $entityManager->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'REVIEW_INVITATION']);
        $subject = $messages->getSubject();
        $body = $messages->getBody();
        $title = $submission->getTitle();

        $form = $this->createForm(IRBReviewAssignmentType::class, $reviewAssignment, ["application" => $submission]);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {

            if (!$reviewAssignment->getIrbreviewer()) {
                return $this->redirectToRoute('irb_review_assignment_new', array('id' => $submission->getId()));
            }

            $reviewAssignment->setApplication($submission);
            $duedate = $reviewAssignment->getDuedate();
            $reviewAssignment->setInvitationSentAt(new \DateTime());
            // dd($submission->getId());
            $reviewAssignment->getApplication()->setStatus($entityManager->getRepository(IRBStatus::class)->find(2));

            $entityManager->persist($reviewAssignment);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'Research reviewer assigned successfully!'
            );
            $suffix = $reviewAssignment->getIRBReviewer();
            $theFirstName = $reviewAssignment->getIRBReviewer()->getUserInfo()->getFirstName();
            $invitation_url = "irb/reviewer-assignment/" . $reviewAssignment->getId() . "/revise/";
            $theEmail = $reviewAssignment->getIRBReviewer()->getEmail();

            // dd( $form);
            $email = (new TemplatedEmail())
                ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
                ->to(new Address($reviewAssignment->getIRBReviewer()->getEmail(), $reviewAssignment->getIRBReviewer()->getUserInfo()))
                // ->cc(new Address($alternative_email[$i], $theFirstNames[$i]))
                ->subject($subject)
                ->htmlTemplate('emails/reviewerinvitation.html.twig')
                ->context([ 
                    'subject' => $subject,
                    'suffix' => $suffix,
                    'body' => $body,
                    'title' => $title,
                    'college' => $reviewAssignment->getIRBReviewer()->getUserInfo()->getCollege(),
                    'reviewerinvitation_URL' => $invitation_url,
                    'name' => $theFirstName,
                    'Authoremail' => $theEmail,
                ]);
            $mailer->send($email);

            return $this->redirectToRoute('irb_review_assignment_new', array('id' => $submission->getId()));
        }

        $external_reviewAssignment = new IRBReviewAssignment();
        $external_reviewAssignment->setStatus(1);
        $external_reviewAssignment->setApplication($submission);

        $external_reviewer_form = $this->createForm(ExternalIRBReviewAssignmentType::class, $external_reviewAssignment)->handleRequest($request);

        if ($external_reviewer_form->isSubmitted() && $external_reviewer_form->isValid()) {

            // dd($external_reviewer_form->getData());
            $token = bin2hex(random_bytes(20));
            $external_reviewAssignment->setToken($token);
            $external_reviewAssignment->setInvitationDueDate(new \DateTime('+5 day'));
            $entityManager->persist($external_reviewAssignment);

            $entityManager->flush();

            //sent email
            $mailHelper->sendEmail($external_reviewAssignment->getExternalirbrevieweremail(), "review assignment", "emails/reviewerinvitation.html.twig", [
                'subject' => $subject,
                'suffix' => $external_reviewAssignment->getExternalirbreviewerName(),
                'body' => $body,
                'title' => $title,
                'college' => " ",
                'reviewerinvitation_URL' => "external-irb-review/" . $token,
                'name' => $external_reviewAssignment->getExternalirbreviewerName(),
                'Authoremail' => $external_reviewAssignment->getExternalirbrevieweremail(),
            ]);
            $this->addFlash("success", "External assigned successfully!!");
            return $this->redirectToRoute('irb_review_assignment_new', array('id' => $submission->getId()));
        }

        $reviewAssignments = $entityManager->getRepository('App\Entity\IRB\IRBReviewAssignment')->findBy(['application' => $submission]);

        ////////////////External reviewer
        return $this->render('irb_review_assignment/new.html.twig', [
            'irb_review_assignment' => $reviewAssignments,
            'external_reviewer_form' => $external_reviewer_form->createView(),
            'form' => $form->createView(),

        ]);
    }

    /**
     * @Route("/my-assigned", name="irb_myassigned", methods={"GET"})
     */
    public function myassigned(Request $request, PaginatorInterface $paginator): Response {

        $entityManager = $this->getDoctrine()->getManager();
        $me = $this->getUser()->getId();
        $this_is_me = $this->getUser();
        // if($this->isGranted('ROLE_SECRETARY') || $this->isGranted('vw_all_irb_assgn')){
        // // $myassigned = $entityManager->getRepository(IRBReviewAssignment::class)->findBy(['irbreviewer' => $this_is_me, 'closed' => NULL], ["id" => "ASC"]);
        // // // if($this->isGranted('ROLE_SECRETARY')){
        //     $myassigned = $entityManager->getRepository(IRBReviewAssignment::class)->getActiveApplication();
        // }
        // else{

        $myassigned = $entityManager->getRepository(IRBReviewAssignment::class)->findBy(['irbreviewer' => $this_is_me, 'closed' => NULL], ["id" => "DESC"]);
        $all = $entityManager->getRepository(IRBReviewAssignment::class)->findBy(['irbreviewer' => $this_is_me, 'closed' => 1], ["id" => "DESC"]);
        // }
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

        $entityManager = $this->getDoctrine()->getManager();
        #######################

        #######################
        $query3 = $entityManager->createQuery(
            'SELECT    b.id , ass.invitation_sent_at as InvitationSentAt,     ass.Declined as Declined,  b.title , s.createdAt  , ass.duedate  as dueDate
         FROM App\Entity\IRB\IRBReview s
        JOIN s.application b
        JOIN s.iRBReviewAssignment ass
        WHERE   s.reviewed_by=:reviewer AND ass.inactive_assignment is NULL AND ass.closed=:closed
'
        )
            ->setParameter('closed', 1)
            ->setParameter('reviewer', $this_is_me);

        $closeds = $query3->getResult();

        #################################################

        return $this->render('application/myassigned.html.twig', [
            'closeds' => $all,
            'myreviews' => $myassigneds,
        ]);
    }

    /**
     * @Route("/{id}/revise", name="review_application", methods={"GET","POST"})
     */
    public function revise(Request $request, IRBReviewAssignment $reviewAssignment): Response {
        ////Ultimate reviewers page

        $entityManager = $this->getDoctrine()->getManager();

        if ($request->request->get('review-checklist') && !$reviewAssignment->getReviewedAt()) {
            foreach ($request->request->get('checklist') as $key => $value) {

                if (!$value) {
                    continue;
                }

                $reviewerResponse = new ReviewerResponse();
                $reviewerResponse->setReviewAssignment($reviewAssignment);

                $reviewerResponse->setAnswer($value);
                $reviewerResponse->setChecklist($entityManager->getRepository(ReviewChecklist::class)->find($key));

                $entityManager->persist($reviewerResponse);
            }
            $reviewAssignment->setClosed(true);
            $reviewAssignment->getApplication()->setStatus($entityManager->getRepository(IRBStatus::class)->find(3));
            $reviewAssignment->setWaiver($request->request->get('waivers'));
            $reviewAssignment->setRiskLevel($request->request->get('risk_level'));
            $reviewAssignment->setRecommendation($request->request->get('recommendation'));
            $reviewAssignment->setReviewedAt(new \DateTime());
            $entityManager->flush();

            $this->addFlash("success", "Review sent.");
            return $this->redirectToRoute('review_application', ["id" => $reviewAssignment->getId()]);
        }

        $submissionOfreviewer = $entityManager->getRepository(IRBReviewAssignment::class)->find($reviewAssignment);
        $submissions = $submissionOfreviewer->getApplication();
        #######################
        // if ($reviewAssignment->getClosed() == 1) {
        //     return $this->redirectToRoute('irb_myassigned');
        // }
        #######################

        $measareviewer = $this->getUser();
        $author = $submissions->getSubmittedBy();

        if ($measareviewer == $author) {
            ////if you are the author then you can't review it///////
            $this->addFlash(
                'warining',
                'You can not see the Application  you made in this page!'
            );
            return $this->redirectToRoute('irb_myassigned');
        }

        $review = new IRBReview();
        $review->setIRBReviewAssignment($reviewAssignment);
        $review->setApplication($reviewAssignment->getApplication());
        $review->setReviewedBy($measareviewer);
        $form = $this->createForm(IRBReviewType::class, $review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $review->setCreatedAt(new \DateTime());
            $review->setReviewedBy($this->getUser());
            if ($review->getRemark() == 1 || $review->getRemark() == 3) {

            }
            $review->setFromDirector(1);
            if ( $review->getRemark() == 3) {
            
            #######################Certificategeneration#################
            $cert = new IrbCertificate();
            $cert->setIrbApplication($reviewAssignment->getApplication());
            $year= date('y'); 
            #####################
            $prefix = $reviewAssignment->getApplication()->getCollege()->getPrefix();
            $id = $reviewAssignment->getApplication()->getId(); 
            $randnum = rand(100, 10000);
            $certcode = $prefix .'-EC'. "-" . $randnum . "-" . $id ."-". $year;
            $today= new \DateTime();
            $validuntil= date_modify($today, '+12 month');
            #####################
             
            $cert->setCertificateCode($certcode);
            // $cert->setApprovedBy($this->getUser());
            // $cert->setApprovedAt(new \DateTime());
            $cert->setValidUntil($validuntil );
            #######################Certificategeneration#################
            // $cert->setIrbRequest($reviewAssignment->getApplication());
            #######################Certificategeneration#################

            // $reviewAssignment->setClosed(1);

            $entityManager->persist($cert);
            }
            $entityManager->persist($review);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'You have  completed a revision successfully!'
            );
            return $this->redirectToRoute('irb_myassigned', array('id' => $reviewAssignment->getId()));
        }

        $reviews = $entityManager->getRepository(IRBReview::class)->findBy(['application' => $reviewAssignment->getApplication(), 'reviewed_by' => $measareviewer]);
        $irb_review_checklist_group = $entityManager->getRepository(ReviewChecklistGroup::class)->findAll();

        return $this->render('application/irb-revise.html.twig', [
            'review_assignment' => $reviewAssignment,
            'review_assignments' => $reviews,
            'submission' => $submissions,
            'irb_review_checklist_group' => $irb_review_checklist_group,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/decide", name="decisions_application", methods={"GET","POST"})
     */
    public function decide(Request $request, Application $reviewAssignment): Response {
        ////Ultimate reviewers page

        $entityManager = $this->getDoctrine()->getManager();
        $review = new IRBReview();
        $form = $this->createForm(IRBReviewType::class, $review);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $review->setCreatedAt(new \DateTime());
            $review->setReviewedBy($this->getUser());
            $review->setFromDirector(1);
            #######################Certificategeneration#################
            $cert = new IrbCertificate();
            $cert->setIrbApplication($reviewAssignment);
            $cert->setCertificateCode('sass');
            $cert->setApprovedAt(new \DateTime());
            $cert->setValidUntil(new \DateTime());
            #######################Certificategeneration#################
            // $reviewAssignment->setClosed(1);
            // $review=$reviewAssignment->getIRBReviewAssignments();

            $entityManager->persist($cert);
            $entityManager->persist($review);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'You have  completed a revision successfully!'
            );
            return $this->redirectToRoute('irb_myassigned', array('id' => $reviewAssignment->getId()));
        }
        return $this->render('application/irb-revision-decision.html.twig', [
            'review_assignment' => $reviewAssignment,
            'submission' => $reviewAssignment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/{id}/delete", name="review_assignment_delete")
     */
    public function delete(IRBReviewAssignment $reviewAssignment): Response {
        $this->denyAccessUnlessGranted('assn_clg_cntr');
        $entityManager = $this->getDoctrine()->getManager();
        $submission = $reviewAssignment->getApplication();
        $entityManager->remove($reviewAssignment);
        $entityManager->flush();
        $this->addFlash("info", "Reviewer deleted successfully ! Thank you!");

        return $this->redirectToRoute('irb_review_assignment_new', array('id' => $submission->getId()));
    }

    /**
     * @Route("/{id}/sendcomment", name="send_comment", methods={"POST"})
     *
     **/
    public function sendcomment(IRBReviewAssignment $reviewAssignment, MailerInterface $mailer): Response {
        $this->denyAccessUnlessGranted('ROLE_CHAIR');
        $entityManager = $this->getDoctrine()->getManager();
        // dd($reviewAssignment );
        #########
        $reviewAssignment->setAllowToView(1);
        $entityManager->persist($reviewAssignment);
        $entityManager->flush();
        $messages = $entityManager->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'REVIEW_RESULT_SENT']);
        $subject = $messages->getSubject();
        $body = $messages->getBody();
        $title = $reviewAssignment->getApplication()->getTitle();
        $theFirstName = $reviewAssignment->getApplication()->getSubmittedBy()->getUserInfo()->getFirstName();
        $app_url = "irb/application/" . $reviewAssignment->getApplication()->getId();
        $theEmail = $reviewAssignment->getApplication()->getSubmittedBy()->getEmail();
        $email = (new TemplatedEmail())
            ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
            ->to(new Address($reviewAssignment->getApplication()->getSubmittedBy()->getEmail(), $reviewAssignment->getApplication()->getSubmittedBy()->getUserInfo()))
            // ->cc(new Address($alternative_email[$i], $theFirstNames[$i]))
            ->subject($subject)
            ->htmlTemplate('emails/irb_reviewer_response.html.twig')
            ->context([
                'subject' => $subject,
                'suffix' => $reviewAssignment->getApplication()->getSubmittedBy()->getUserInfo()->getSuffix(),
                'body' => $body,
                'title' => $title,
                'submission_url' => $app_url,
                'name' => $theFirstName,
                'Authoremail' => $theEmail,
            ]);
        // dd($reviewAssignment->getApplication());
        $mailer->send($email);

        #########
        $entityManager->flush();
        $this->addFlash("success", "Reviewer comment sent successfully! Thank you!");
        return $this->redirectToRoute('application_show', ["id" => $reviewAssignment->getApplication()->getId()], Response::HTTP_SEE_OTHER);

    }
}
