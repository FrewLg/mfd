<?php

namespace App\Controller;

use App\Entity\CallForProposal;
use App\Entity\Chat;
use App\Entity\CoAuthor;
use App\Entity\CollaboratingInstitution;
use App\Entity\Discussion;
use App\Entity\EditorialDecision;
use App\Entity\PublishedSubmission;
use App\Entity\PublishedSubmissionAttachment;
use App\Entity\ResearchReport;
use App\Entity\Review;
use App\Entity\ReviewAssignment;
use App\Entity\Submission;
use App\Entity\SubmissionAttachement;
use App\Entity\SubmissionBudget;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\ChatType;
use App\Form\ResearchReportSubmissionSettingType;
use App\Form\ResearchReportType;
use App\Form\ReviewDecisionType;
use App\Form\SubmissionFilterType as FormSubmissionFilterType;
use App\Form\SubmissionType;
use App\Form\UserCoAuthorType;
use App\Helper\SmsHelper;
use App\Helper\SubmissionHelper;
use App\Message\SendEmailMessage;
use App\Repository\CallForProposalRepository;
use App\Repository\ChatRepository;
use App\Repository\ReviewRepository;
use App\Repository\SubmissionRepository;
use App\Utils\Constants;
use Doctrine\ORM\Query\Expr;
use Dompdf\Dompdf;
use Dompdf\Options;
use Knp\Component\Pager\PaginatorInterface;
use Lexik\Bundle\FormFilterBundle\Filter\FilterBuilderUpdaterInterface;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Route("/submission")
 */

class SubmissionController extends AbstractController {
    /**
     * @Route("/", name="submission_index", methods={"GET","POST"})
     */
    // public function index(Request $request, CallForProposal $call,   SubmissionRepository $submissionRepository,  PaginatorInterface $paginator,  FilterBuilderUpdaterInterface $query_builder_updater): Response
    public function index(Request $request, SubmissionRepository $submissionRepository, PaginatorInterface $paginator, FilterBuilderUpdaterInterface $query_builder_updater): Response {
        $this->denyAccessUnlessGranted('vw_all_sub');

        $info = 'All';
        $submissionData = $submissionRepository->getSubmissions();

        $sumissionFilterForm = $this->createForm(FormSubmissionFilterType::class);
        $sumissionFilterForm->handleRequest($request);
        if ($sumissionFilterForm->isSubmitted() && $sumissionFilterForm->isValid()) {

            $submissionData = $submissionRepository->getSubmissions($sumissionFilterForm->getData());
        }

        // Paginate the results of the query
        $Allsubmissions = $paginator->paginate(
            // Doctrine Query, not results
            $submissionData,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        return $this->render('submission/index.html.twig', [
             'submissions' => $Allsubmissions,
            'info' => $info,
            'sumissionFilterForm' => $sumissionFilterForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/callresponses", name="callresponses", methods={"GET","POST"})
     */
    public function callresponses(Request $request, CallForProposal $call, SubmissionRepository $submissionRepository, PaginatorInterface $paginator): Response {
        $this->denyAccessUnlessGranted('vw_all_sub');

        $info = 'All';
        $submissionData = $submissionRepository->getSubmissions(['callForProposal' => $call]);

        $sumissionFilterForm = $this->createForm(FormSubmissionFilterType::class);
        $sumissionFilterForm->handleRequest($request);
        if ($sumissionFilterForm->isSubmitted() && $sumissionFilterForm->isValid()) {

            $submissionData = $submissionRepository->getSubmissions($sumissionFilterForm->getData(['callForProposal' => $call]));
        }

        // Paginate the results of the query
        $Allsubmissions = $paginator->paginate(
            // Doctrine Query, not results
            $submissionData,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        return $this->render('submission/index.html.twig', [
            'submissions' => $Allsubmissions,
            'info' => $info,
            'call' => $call,
            'sumissionFilterForm' => $sumissionFilterForm->createView(),
        ]);
    }

    /**
     * @Route("/{id}/coll-coord", name="coordinators", methods={"GET","POST"})
     */
    public function forcolleges(Request $request, CallForProposal $call, SubmissionRepository $submissionRepository, PaginatorInterface $paginator): Response {
        $this->denyAccessUnlessGranted('coordinators');

        $em = $this->getDoctrine()->getManager();
        $info = 'All';
        // $submissionData = $submissionRepository->getSubmissions(['callForProposal' => $call, 'college'=>$this->getUser()->getUserInfo()->getCollege()]);
        $submissionData = $em->getRepository('App:Submission')->findBy(['callForProposal' => $call,    'college'=>$this->getUser()->getUserInfo()->getCollege()]);
// 
        $sumissionFilterForm = $this->createForm(FormSubmissionFilterType::class);
        $sumissionFilterForm->handleRequest($request);
        if ($sumissionFilterForm->isSubmitted() && $sumissionFilterForm->isValid()) {

            $submissionData = $submissionRepository->getSubmissions($sumissionFilterForm->getData(['callForProposal' => $call]));
        }

        if ($request->request->get('change-fundingScheme')) {
         $submissionData = $em->getRepository('App:Submission')->findBy(['callForProposal' => $call,'college'=>$this->getUser()->getUserInfo()->getCollege(),'fundingScheme'=>$request->request->get('fundingScheme')]);
         $count = count($submissionData);
            $this->addFlash("success",  "(". $count .') results found under this funding scheme');
             } 

        // Paginate the results of the query
        $Allsubmissions = $paginator->paginate(
            // Doctrine Query, not results
            $submissionData,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            10
        );
        return $this->render('submission/index.html.twig', [
            'submissions' => $Allsubmissions,
            'info' => $info,
            'call' => $call,
            'sumissionFilterForm' => $sumissionFilterForm->createView(),
        ]);
    }

    // /**
    //  * @Route("/{id}/callresponses", name="callresponses", methods={"GET","POST"})
    //  */
    // public function callrespnses(Request $request, CallForProposal $call,  SubmissionRepository $submissionRepository, PaginatorInterface $paginator,  FilterBuilderUpdaterInterface $query_builder_updater): Response
    // {
    //     $this->denyAccessUnlessGranted('vw_all_sub');
    //     $em = $this->getDoctrine()->getManager();
    //     //  $submissionRepository = array_reverse($em->getRepository(Submission::class)->findAll());
    //     $formFilter = $this->createForm(SubmissionFilterType::class);
    //     $formFilter->handleRequest($request);
    //     $info = $call->getSubject();
    //     // $submissionRepository = $em->getRepository('App:Submission')->getSubmissions(['callForProposal' => $call]);
    //     $submissionRepository = $submissionRepository->getSubmissions();

    //     $sumissionFilterForm = $this->createForm(FormSubmissionFilterType::class);
    //     $sumissionFilterForm->handleRequest($request);
    //     if ($sumissionFilterForm->isSubmitted() && $sumissionFilterForm->isValid()) {

    //         $submissionRepository = $submissionRepository->getSubmissions($sumissionFilterForm->getData());
    //     }
    //     // Paginate the results of the query
    //     $submissions = $paginator->paginate(

    //         $submissionRepository,
    //         $request->query->getInt('page', 1),

    //         10
    //     );
    //     return $this->render('submission/index.html.twig', [
    //         'submissions' => $submissions,
    //         'info' => $info, 'call' => $call,
    //         'sumissionFilterForm' => $sumissionFilterForm->createView(),

    //     ]);
    // }

    #[Route('/{id}/call-reports', name:'call_research_reports', methods:['GET', "POST"])]
public function submissionReports(CallForProposal $callForProposal, PaginatorInterface $paginator, Request $request, SubmissionRepository $submissionRepository): Response {
    $this->denyAccessUnlessGranted('mng_rprts');

    if (!$callForProposal->getResearchReportPhase()) {
        $this->addFlash("warning", "this call has no report settings");
        return $this->redirect($request->headers->get('referer'));
    }
    $queryBulder = $submissionRepository->getSubmissions(["callForProposal" => $callForProposal, "awardGranted" => 1]);
    $submissions = $paginator->paginate(
        $queryBulder,
        $request->query->getInt('page', 1),
        10
    );

    return $this->render('research_report/index.html.twig', [
        'submissions' => $submissions,
        'callForProposal' => $callForProposal,
    ]);
}

/**
 * @Route("/alert/", name="alert", methods={"GET","POST"})
 */
public function alert(MailerInterface $mailer): Response {
    $this->denyAccessUnlessGranted('vw_all_sub');
    #####################################
    ///////////// Let us email  co-pis    to  remind
    $entityManager = $this->getDoctrine()->getManager();

    // $submission = $entityManager->getRepository('App:Submission')->findBy(['complete' => NULL]);
    $messages = $entityManager->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'SUBMISSION_CO_PI_INVITATION']);
    $subject = $messages->getSubject();
    $body = $messages->getBody();
    $em = $this->getDoctrine()->getManager();
    $query = $entityManager->createQuery(
        'SELECT u.email , s.id ,  u.username,  s.complete, s.title  
                                  , pi.first_name ,pi.gender, ui.alternative_email 
                    FROM App:CoAuthor c
                    JOIN c.researcher u
                    JOIN u.userInfo ui
                    JOIN c.submission s
                    JOIN s.author p
                    JOIN p.userInfo pi
                    WHERE
 	     c.confirmed is NULL and s.complete=:completed' 
    )

        ->setParameter('completed', 'completed');
    $recepients = $query->getResult();
    // dd($recepients);
    $em = $this->getDoctrine()->getManager();
    // $qb = $em->createQueryBuilder();
    $messages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'SUBMISSION_CO_PI_REMINDER']);
    $subject = $messages->getSubject();
    $body = $messages->getBody();
    foreach ($recepients as $row) {
        $theEmails[] = $row['email'] . ' ';
        $theNames[] = $row['username'] . ' ';
        $theFirstNames[] = $row['username'] . ' ';
        $pi_name[] = $row['first_name'] . ' ';
        $titles[] = $row['title'] . ' ';
        $alternative_email[] = $row['alternative_email'] . ' ';
        $copi_id[] = $row['id'] . ' ';
        // dd($row[0]);

    }
    ////////////
    $length = count($recepients);
    for ($i = 0; $i < $length; $i++) {
        ///////////////
        $theFirstName = $theFirstNames[$i];
        if ($theFirstName == '') {
            $theFirstName = $theNames[$i];
            // dd($theFirstName);
        }
        if ($alternative_email[$i] == '') {
            $alternative_email[$i] = $theEmails[$i];
        }
        $pi_name = $theEmails[$i];
        $theEmail = $theEmails[$i];
        // $titles = $titles[$i];

        $body = 'Dear ' . $theFirstNames[$i] . ',  <br>
                     ' . $pi_name . ' is waiting for you to respond
                    to his recent proposal submisison entitled
            "' . $titles[$i] . ' ". Please respond to the invitaion using the invitation
            link below before the deadline of the call.';
        $invitation_url = 'submission/my-membership-details/' . $copi_id[$i];
        $email = (new TemplatedEmail())
            ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
            ->to(new Address($theEmails[$i], $theFirstNames[$i]))
            // ->cc(new Address($alternative_email[$i], $theFirstNames[$i]))
            ->subject($subject)
            ->htmlTemplate('emails/co-authorship-alert.html.twig')
            ->context([
                'subject' => $subject,
                'body' => $body,
                'title' => $titles[$i],
                'pi' => $pi_name,
                'submission_url' => $invitation_url,
                'name' => $theFirstName,
                'Authoremail' => $theEmail,
            ]);
        $mailer->send($email);
    }
    ##########

    $this->addFlash("success", "Alert sent to Co-PI s successffully  !");

    return $this->redirectToRoute('submission_index');
}
/**
 * @Route("/wizard/{uidentifier}", name="submission_firststepold", methods={"GET","POST"})
 */
public function metadata(Request $request, CallForProposal $callForProposal, UserController $test, MailerInterface $mailer): Response {

    #######################
    $em = $this->getDoctrine()->getManager();

    $deadline = $callForProposal->getDeadline();
    $today = new \DateTime('');
    if ($deadline <= $today) {

        $this->addFlash("danger", "Sorry! Call has expired!  Thank you!");
        return $this->redirectToRoute('myreviews');
    }

    ################################
    ##########################
    $userdetails = $this->getUser()->getUserInfo();
    if ($userdetails == '') {
        $test->checkuser();
        return $this->redirectToRoute('researchworks');
    }
    // dd($userdetails);
    if (
        $userdetails->getFirstName() == '' || $userdetails->getMidleName() == '' ||
        $userdetails->getLastName() == '' ||
        $userdetails->getCollege() == '' ||
        $userdetails->getEducationLevel() == '' || $userdetails->getAcademicRank() == ''
    ) {

        $this->addFlash("danger", "Please complete your profile first before you submit the proposal  !");

        return $this->redirectToRoute('researchworks');
    }

    ########################## 

    
    ########################## Check submission exists #######################

    $entityManager = $this->getDoctrine()->getManager();
    // $submission = $entityManager->getRepository('App:Submission')->findOneBy(['author' => $this->getUser(), 'callForProposal' => $callForProposal]);

    // if ($p_i_college !== $callForProposal->getCollege() and $callForProposal->getAllowPiFromOtherUniversity()=='') {

    //     $this->addFlash("danger", "You are not allowed to submit on this  call!");

    //     return $this->redirectToRoute('myreviews');
    // }
    ########################## End Check submission exists #######################

    $entityManager = $this->getDoctrine()->getManager();
    // $new = false;

    //dd($request->request);
    // $submission = $entityManager->getRepository(Submission::class)->findOneBy(['author' => $this->getUser(),   'callForProposal' => $callForProposal , "id" => "DESC"]);
    // if (!$submission) {
    //     $new = true;
    //     $submission = new Submission();
    // } else {
    //     if ($submission->getStep() == 10) {
    //         // $this->addFlash('warning', "You have a  submission with this call. Edit your submission instead.");
    //         // $submission=$submission->getId()+1; 
    // // $submission = $entityManager->getRepository(Submission::class)->findOneBy(['id' => $submission->getId()+1]);
    // // $submission = new Submission();

    //         // return $this->redirectToRoute('myreviews');
    //     }
    // }
    $submission = new Submission();

    $submission->setCallForProposal($callForProposal);
    $submission->setUidentifier(md5(uniqid()));

    $form = $this->createForm(SubmissionType::class, $submission);
    $form->handleRequest($request);
    $submission->setStatus(1);
    if ($form->isSubmitted() && $form->isValid()) {
        $submission->setAuthor($this->getUser());

        // if ($new) {
        //     $entityManager->persist($submission);
        // }
 
        


        if ($submission->getStep() == 10) {
            $submission->setSentAt(new \DateTime());

            $submission->setComplete("completed");
            $entityManager->persist($submission);
           
            $entityManager->flush();
            $this->addFlash('success', "submission saved successfully!");

            $invitation_url = 'submission/my-membership';
            #####################################
            ///////////// Let us email  co-pis    to  remind
            $messages = $entityManager->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'SUBMISSION_CO_PI_INVITATION']);
            $subject = $messages->getSubject();
            $body = $messages->getBody();
            $em = $this->getDoctrine()->getManager();
            // $query = $entityManager->createQuery(
            //     'SELECT u.email ,  u.username
            //         FROM App:CoAuthor s
            //         JOIN s.researcher u
            //         WHERE s.submission = :submission'
            // )
            //     ->setParameter('submission', $submission);
            // $recepients = $query->getResult();
            $recepients = $submission->getCoAuthors();

            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $messages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'SUBMISSION_CO_PI_INVITATION']);
            $subject = $messages->getSubject();
            $body = $messages->getBody();
            foreach ($recepients as $row) {
                // $theEmails[] = $row['email'] . ' ';
                // $theNames[] = $row['username'] . ' ';
                // $theFirstNames[] = $row['username'] . ' ';
            }
            ////////////
            $length = count($recepients);
            // for ($i = 0; $i < $length; $i++) {
            foreach ($submission->getCoAuthors() as $key => $author) {
                $theFirstName = $author->getResearcher()->GetUserInfo()->getFirstName();
                // $theFirstNames[$i];
                $theEmail= $author->getResearcher()->getEmail();
                ///////////////
                 $email = (new TemplatedEmail())
                    ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
                    //  ->to(new Address($theEmails[$i], $theFirstNames[$i]))
                    ->to(new Address($theEmail, $theFirstName))

                    ->subject($subject)
                    ->htmlTemplate('emails/co-authorship-invitation.html.twig')
                    ->context([
                        'subject' => $subject,
                        'body' => $body,
                        'title' => $submission->getTitle(),
                        'submission_url' => $invitation_url,
                        'name' => $theFirstName,
                        'Authoremail' => $theEmail,
                    ]);
                $mailer->send($email);
                // dd($theEmail);
            }

            ##########
            $applicantmessages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'EMAIL_KEY_SUBMISSION_ACKNOWLEDGEMENT']);
            $applicantsubject = $applicantmessages->getSubject();
            $applicantbody = $applicantmessages->getBody();

            $submission_url = 'submission/' . $submission->getId() . '/status';
            $applicant = $submission->getAuthor()->getEmail();
            $applicantname = $submission->getAuthor()->getUserInfo()->getFirstName();
          
          // $emails = [];
            // foreach ($submission->getCoAuthors() as $key => $author) {
            //     $emails[] = $author->getEmail();
            // }
              $emailtwo = (new TemplatedEmail())
                ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
                ->to($applicant)
                ->subject($applicantsubject)
                ->htmlTemplate('emails/application_ack.html.twig')
                ->context([
                    'subject' => $applicantsubject,
                    'body' => $applicantbody,
                    'title' => $submission->getTitle(),
                    'submission_url' => $submission_url,
                    'name' => $applicantname,
                    'Authoremail' => $applicant,
                ]);
                // dd($applicant);

            $mailer->send($emailtwo);

            // $sendEmail = new SendEmailMessage([$this->getUser()->getEmail()], Constants::EMAIL_KEY_SUBMISSION_ACKNOWLEDGEMENT, "emails/application_ack.html.twig", [
            // ]);
            // $this->dispatchMessage($sendEmail);
            // $emails = [];
            // foreach ($submission->getCoAuthors() as $key => $author) {
            //     $emails[] = $author->getEmail();
            // }
            // $sendEmail = new SendEmailMessage($emails, Constants::EMAIL_KEY_SUBMISSION_ACKNOWLEDGEMENT, "emails/application_ack.html.twig", [
            // ]);
            // $this->dispatchMessage($sendEmail);
        // $entityManager->flush();
          
        $entityManager->persist($submission);
           
        $entityManager->flush();
        $this->addFlash('success', "Submission saved successfully!");

            return $this->redirectToRoute('editsubmission', array('uidentifier' => $submission->getUidentifier()));

            // return $this->redirectToRoute('myreviews');
        }
        $entityManager->persist($submission);

        $entityManager->flush();
        return $this->redirectToRoute('editsubmission', array('uidentifier' => $submission->getUidentifier()));

        ##############################

        return $this->redirectToRoute('submission_firststepold', ["uidentifier" => $callForProposal->getUidentifier()]);
    }
    return $this->render('submission/metadata.html.twig', [
        'submissionform' => $form->createView(),
        'submission' => $submission,
        'new' => 1,
        'call' => $callForProposal,
    ]);
}

/**
 * @Route("/m/{uidentifier}", name="editsubmission", methods={"GET","POST"})
 */
public function editsubmission(Request $request, Submission $submission  , MailerInterface $mailer): Response {

    #######################
 
    $callForProposal=$submission->getCallForProposal();
    $deadline = $callForProposal->getDeadline();
    $today = new \DateTime('');
    if ($deadline <= $today) {

        $this->addFlash("danger", "Sorry! Call has expired!  Thank you!");
        return $this->redirectToRoute('myreviews');
    }  
     $entityManager = $this->getDoctrine()->getManager();  
    $form = $this->createForm(SubmissionType::class, $submission);
    $form->handleRequest($request);
    $submission->setStatus(1);
    $requesteduser = $submission->getAuthor();
    if ($requesteduser !== $this->getUser()) {

        $this->addFlash("danger", "Sorry you are not allowed for this service ! Thank you!");
        return $this->redirectToRoute('myreviews');
    }
    
    $newcoPi = new User();
        $userform = $this->createForm(UserCoAuthorType::class, $newcoPi);
        $userform->handleRequest($request);

        if ($userform->isSubmitted() && $userform->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
           
            $userInfo=new UserInfo();
            $midle_name = $userform->get('midle_name')->getData();
            $first_name = $userform->get('first_name')->getData();
            $last_name = $userform->get('last_name')->getData();
            // $department = $userform->get('department')->getData();
            // $role = $userform->get('role')->getData();
            $userInfo->setFirstName($midle_name);
            $userInfo->setMidleName($first_name);
            $userInfo->setLastName($last_name);
            // $userInfo->setDepartment($department);
            $newcoPi->setUsername($first_name.$midle_name.$last_name);
            $userInfo->setUser($newcoPi);
            // $userInfo->setLastName($last_name);
            $coauthor = new CoAuthor();
            $coauthor->setResearcher($newcoPi);
            $coauthor->setSubmission($submission);
            $uexists = $entityManager->getRepository('App:User')->findBy(['email' =>$newcoPi->getEmail() ]);

            if($uexists){
                $this->addFlash('danger', "A user with ".$newcoPi->getEmail()." email already exists! please check it in the dropdown list. Or let the user with ( ".$newcoPi->getEmail().")  email update his profile and try again!");
                return $this->redirectToRoute('editsubmission', array('uidentifier' => $submission->getUidentifier()));

            }
            // $coauthor->setRole($role);
            $newcoPi->setPassword('newcoPi');
            $entityManager->persist($newcoPi);
            $entityManager->persist($userInfo);
            $entityManager->persist($coauthor);
            $entityManager->flush();

            return $this->redirectToRoute('editsubmission', array('uidentifier' => $submission->getUidentifier()));
        }
        
    if ($form->isSubmitted() && $form->isValid()) {
        $submission->setAuthor($this->getUser());
            // if ($submission->getStep() == 10) {
               
            if ($submission->getCoAuthors()) {
            $submission->setSentAt(new \DateTime());
            $submission->setComplete("completed");
            $entityManager->flush();
            $invitation_url = 'submission/my-membership';

            $this->addFlash('success', "Submission saved successfully!");
            $messages = $entityManager->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'SUBMISSION_CO_PI_INVITATION']);
            $subject = $messages->getSubject();
            $body = $messages->getBody();
            $em = $this->getDoctrine()->getManager();
            // $query = $entityManager->createQuery(
            //     'SELECT u.email ,  u.username
            //         FROM App:CoAuthor s
            //         JOIN s.researcher u
            //         WHERE s.submission = :submission'
            // )
            //     ->setParameter('submission', $submission);
            // $recepients = $query->getResult();
            $recepients = $submission->getCoAuthors();

            $em = $this->getDoctrine()->getManager();
            $qb = $em->createQueryBuilder();
            $messages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'SUBMISSION_CO_PI_INVITATION']);
            $subject = $messages->getSubject();
            $body = $messages->getBody();
            foreach ($recepients as $row) {
                // $theEmails[] = $row['email'] . ' ';
                // $theNames[] = $row['username'] . ' ';
                // $theFirstNames[] = $row['username'] . ' ';
            }
            ////////////
            $length = count($recepients);
            // for ($i = 0; $i < $length; $i++) {
            foreach ($submission->getCoAuthors() as $key => $author) {
                  $updateuinfo=  $author->getResearcher()->getUserInfo()->getId();
                //   $updateuinfo=  $author->getResearcher()->getUserInfo()->getPhoneNumber();
                //   dd($updateuinfo);
            $thisCoauthor = $em->getRepository('App:UserInfo')->find($updateuinfo);

                //   dd( $thisCoauthor);
                 $dsd= $thisCoauthor->setDepartment( $author->getDepartment());
                  $entityManager->persist($dsd);

                $theFirstName = $author->getResearcher()->getUserInfo()->getFirstName();
                if(!$theFirstName){
                    $theFirstName=$author->getResearcher()->getUsername();
                  }
                // $theFirstNames[$i];
                $theEmail= $author->getResearcher()->getEmail();
                ///////////////
                if (0<=count($submission->getCoAuthors())) {
                 if($author->getEmailSent()==1){
                }
                else{
                 $author->setEmailSent(1);
                 $entityManager->persist($author);
                 $entityManager->flush();
                
                 $email = (new TemplatedEmail())
                    ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
                    //  ->to(new Address($theEmails[$i], $theFirstNames[$i]))
                    ->to(new Address($theEmail, $theFirstName))

                    ->subject($subject)
                    ->htmlTemplate('emails/co-authorship-invitation.html.twig')
                    ->context([
                        'subject' => $subject,
                        'body' => $body,
                        'title' => $submission->getTitle(),
                        'submission_url' => $invitation_url,
                        'name' => $theFirstName,
                        'Authoremail' => $theEmail,
                    ]);
                    // dd();
                  $mailer->send($email);
                  }
                }
                // dd($theEmail);
            }

            ##########
            $applicantmessages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'EMAIL_KEY_SUBMISSION_ACKNOWLEDGEMENT']);
            $applicantsubject = $applicantmessages->getSubject();
            $applicantbody = $applicantmessages->getBody();

            $submission_url = 'submission/' . $submission->getId() . '/status';
            $applicant = $submission->getAuthor()->getEmail();
            $applicantname = $submission->getAuthor()->getUserInfo();
          if( !$applicantname){
            $applicantname=$submission->getAuthor()->getUsername();
          }
          // $emails = [];
            // foreach ($submission->getCoAuthors() as $key => $author) {
            //     $emails[] = $author->getEmail();
            // }
              $emailtwo = (new TemplatedEmail())
                ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
                ->to($applicant)
                ->subject($applicantsubject)
                ->htmlTemplate('emails/application_ack.html.twig')
                ->context([
                    'subject' => $applicantsubject,
                    'body' => $applicantbody,
                    'title' => $submission->getTitle(),
                    'submission_url' => $submission_url,
                    'name' => $applicantname,
                    'Authoremail' => $applicant,
                ]);
                // dd($applicant);

                if ($submission->getStep() == 10){
                    $mailer->send($emailtwo);
        $submission->setComplete("completed");

        $status2 = $entityManager->getRepository('App:SubmissionStatus')->findOneBy(['id' => 2]);

        $submission->setSubmissionStatus($status2);
            
            }
             

 
        // $entityManager->flush();

        //    return $this->redirectToRoute('submission_status', array('uidentifier' => $submission->getUidentifier()));
        //     // return $this->redirectToRoute('myreviews');
        }
      

        $entityManager->flush();
        ##############################
           return $this->redirectToRoute('submission_status', array('uidentifier' => $submission->getUidentifier()));
           // return $this->redirectToRoute('submission_status', array('id' => $submission->getId()));

        // return $this->redirectToRoute('submission_firststepold', ["uidentifier" => $callForProposal->getUidentifier()]);
    }
    return $this->render('submission/metadata.html.twig', [
        'submissionform' => $form->createView(),
        'userform' => $userform->createView(),
        'submission' => $submission,
        'new' => 0,

        'call' => $callForProposal,
    ]);
}


// /**
//  * @Route("/{uid}/download", name="download", methods={"GET"})
//  */
// public function download(Request $request, CallForProposal $uid) {

//     $em = $this->getDoctrine()->getManager();

//     $files = $em->getRepository('App:Submission')->findBy(['CallForProposal' => $uid]);
//     // foreach ($submission as $key => $attachments) {
//     //     # code...
//     //     // $attachments
//     //     $path=$this->getParameter('submission_files');
//     //     $this->getResponse()->setHttpHeader('Content-Type', 'application/docx');
//     //     $test=$this->redirect($path.$attachments->getProposalFile());
//     //     dd($test);

//     // }

//     // $files = [];
//     $em = $this->getDoctrine()->getManager();
//     $path=$this->getParameter('submission_files');

//     foreach ($documents as $document) {
//         array_push($files, $path . $document->getWebPath());
//     }

//     // Create new Zip Archive.
//     $zip = new \ZipArchive();

//     // The name of the Zip documents.
//     $zipName = 'Documents.zip';

//     $zip->open($zipName,  \ZipArchive::CREATE);
//     foreach ($files as $file) {
//         $zip->addFromString(basename($file),  file_get_contents($file));
//     }
//     $zip->close();

//     $response = new Response(file_get_contents($zipName));
//     $response->headers->set('Content-Type', 'application/zip');
//     $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
//     $response->headers->set('Content-length', filesize($zipName));

//     @unlink($zipName);

//     return $response;

// }
    // Configure Dompdf according to your needs
/**
 * @Route("/{uid}/research-sumary", name="research_sumary", methods={"GET"})
 */
public function exportnow(Request $request, $uid) {

    $em = $this->getDoctrine()->getManager();

    $submission = $em->getRepository('App:Submission')->findOneBy(['uidentifier' => $uid]);

    // Configure Dompdf according to your needs
    $pdfOptions = new Options();
    $pdfOptions->set('defaultFont', 'Arial');
    $pdfOptions->set('isRemoteEnabled', true);
    $data = file_get_contents('img/logo.png');
    $type = 'png';
    $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
    $pdfOptions->set('tempDir', '/home/ghost/Desktop/pdf-export/tmp');
    // Instantiate Dompdf with our options
    $dompdf = new Dompdf($pdfOptions);
    $dompdf->set_option("isPhpEnabled", true);

    $html = $this->renderView('submission/summary.html.twig', [
        'user' => $this->getUser(),
        'base64' => $base64,
        'submission' => $submission,
    ]);
    $dompdf->loadHtml($html);
    $dompdf->setPaper('A4', 'portrait');
    $dompdf->render();

    $font = $dompdf->getFontMetrics()->get_font("helvetica", "bold");
    $font = null;
    $dompdf->getCanvas()->page_text(72, 18, "Page: {PAGE_NUM} of {PAGE_COUNT}", $font, 10, array(0, 0, 0));

    ob_end_clean();
    $filename = $submission->getTitle();

    $dompdf->stream($filename . "file.pdf", [
        "Attachment" => false,
    ]);
}

// ob_end_clean();
// $dompdf->stream();
 

/**
 * @Route("/{id}/opendiscussion", name="opendiscussion", methods={"GET","POST"})
 */
public function new (Submission $submission) {
    $entityManager = $this->getDoctrine()->getManager();

    $opendiscussuion = $entityManager->getRepository(Discussion::class)->findBy(['submission' => $submission, 'status' => 0]);
    if ($opendiscussuion) {
        $opendiscussuion;
        $this->addFlash("danger", "Sorry you have to close the open discussion first to start the new one!");
           return $this->redirectToRoute('submission_status', array('uidentifier' => $submission->getUidentifier()));
           // return $this->redirectToRoute('submission_status', array('id' => $submission->getId()));
    } else {
        $discussion = new Discussion();
        $discussion->setSubmission($submission);
        $discussion->setStatus(0);
        $discussion->setCreatedAt(new \Datetime());
        $entityManager->persist($discussion);
        $entityManager->flush();
        $this->addFlash("success", "A new discussion has been opened you can make a conversation now!");

    }
    return $this->redirectToRoute('submission_status', array('uidentifier' => $submission->getUidentifier()));

    // return $this->redirectToRoute('submission_status', array('id' => $submission->getId()));

}

/**
 * @Route("/{uidentifier}/status", name="submission_status", methods={"GET","POST"})
 */
public function statusubmission(Request $request, Submission $submission, SubmissionHelper $submissionHelper, ChatRepository $chatRepository): Response {
    ////Ultimate reviewers page

    $entityManager = $this->getDoctrine()->getManager();

    ################### Are you the one? #################################
    $em = $this->getDoctrine()->getManager();
    $thisUser = $this->getUser();
    $myapplications = $em->getRepository(Submission::class)->find($submission);
    $requesteduser = $myapplications->getAuthor();
    if ($requesteduser !== $thisUser) {

        $this->addFlash("danger", "Sorry you are not allowed for this service ! Thank you!");
        return $this->redirectToRoute('myreviews');
    }
    ################### Are you the one? #################################

    $editorialDecisions = $entityManager->getRepository(EditorialDecision::class)->findBy(['submission' => $submission]);
    #dd($me_as_a_reviewer.$me);
    $measareviewer = $this->getUser();
    $author = $submission->getAuthor();

    // $reviews=$entityManager->getRepository(Review::class)->findBy(['submission' => $submission ] );
    $review = new Review();

    if (!$measareviewer == $author) {
        ////if you are the author then you can't review it///////
        $this->addFlash(
            'warining',
            'You can not see the submission you made in this page!'
        );
        return $this->redirectToRoute('myreviews');
    }
    ///////////////////////////edit sub attachment/////////
    $missingattachmentform = $this->createFormBuilder($review)
     ->add('proposal', FileType::class, [
        'label' => 'Attach a concept note',
        'mapped' => false,
        'attr'=>['class'=>'form-control'],
        'required' => false,
    ])
    ->getForm();
$missingattachmentform->handleRequest($request); 
    if ($missingattachmentform->isSubmitted() && $missingattachmentform->isValid()) {
         $file3 = $missingattachmentform->get('proposal')->getData();
        if ($file3 == '' ) {
        }
        else {
            $file3 = $missingattachmentform->get('proposal')->getData();
            $fileName3 = 'Concept note -'.md5(uniqid()) . '.' . $file3->guessExtension();
            $file3->move($this->getParameter('upload_destination'), $fileName3);
            $submission->setProposal($fileName3);

        }
        // $projectAttachmentRepository->add($projectAttachment);
        $entityManager->persist($submission);
        $entityManager->flush();
        return $this->redirectToRoute('submission_status', array('uidentifier' => $submission->getUidentifier()));

     }
    ///////////////////////////edit sub attachment/////////
    //////allow reviewer if he is only assigned to this submission
    $form = $this->createFormBuilder($review)
        ->add('comment')
        ->add('attachment', FileType::class, [
            'label' => 'Review document  file',
            'mapped' => false,
            'required' => false,
        ])
        ->getForm();
    $form->handleRequest($request);
    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $file3 = $review->getAttachment();
        $com = $review->getComment();
        if ($file3 == "") {
            echo 'file not uploaded';
        } else {
            $file3 = $form->get('attachment')->getData();
            $fileName3 = md5(uniqid()) . '.' . $file3->guessExtension();
            $file3->move($this->getParameter('review_files'), $fileName3);
            $review->setAttachment($fileName3);
        }
        $review->setSubmission($submission);
        $review->setCreatedAt(new \DateTime());
        $entityManager->persist($review);
        $entityManager->flush();

        return $this->redirectToRoute('submission_status', array('uidentifier' => $submission->getUidentifier()));
        // return $this->redirectToRoute('submission_status', array('id' => $submission->getId()));
    }
    ////// if e submission has benn sent to the publication then allow researcher to upload final report of the research with respect to their attachment types
    $published = new PublishedSubmission();

    $entityManager = $this->getDoctrine()->getManager();
    #          $contributors=$entityManager->getRepository(CoAuthor::class)->findBy(['submission' => $submission ] );
    $publicationstatus = $entityManager->getRepository(PublishedSubmission::class)->findBy(['submission' => $submission]);
    $finalreportform = $this->createFormBuilder($published)
        ->add('attachement_type', EntityType::class, array(
            'placeholder' => '-- Select Component Type --',
            'class' => 'App\Entity\AttachementType',
            'attr' => array(
                'empty' => '--select--- ',
                'required' => false,
                'class' => 'chosen-select form-control',
            ),
        ))
        ->add('published_date', DateType::class, array(
            'placeholder' => [
                'year' => 'Year', 'month' => 'Month', 'day' => 'Day',
            ],
            'widget' => 'single_text',
            'format' => 'yyyy-MM-dd',
            'attr' => array(
                'required' => true,
                'class' => 'form-control',
            ),
        ))
        ->add('final_report', FileType::class, [
            'label' => 'Upload your terminal report file',
            'mapped' => false,
            'required' => true,
        ])
        ->getForm();
    $finalreportform->handleRequest($request);
    if ($finalreportform->isSubmitted() && $finalreportform->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $file3 = $published->getFinalReport();

        if ($file3 = '') {
            echo 'File not uploaded';
        } else {
            $file3 = $finalreportform->get('final_report')->getData();
            $fileName3 = md5(uniqid()) . '.' . $file3->guessExtension();
            $file3->move($this->getParameter('submission_files'), $fileName3);
            $published->setFinalReport($fileName3);
        }
        ////// check if there is publication has
        if ($entityManager->getRepository(PublishedSubmission::class)->findBy(['submission' => $submission, 'attachement_type' => $published->getAttachementType()])) {

            $this->addFlash("danger", "Sorry you have already uploaded '" . $fileName3 . "' file is the same attachment , please change  instead !");
           return $this->redirectToRoute('submission_status', array('uidentifier' => $submission->getUidentifier()));
        //    return $this->redirectToRoute('submission_status', array('id' => $submission->getId()));
        }
        $published->setSubmission($submission);
        $entityManager->persist($published);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Info saved successfully!'
        );
    }

    $submission_report_schedule_count = sizeof($submission->getResearchReportSubmissionSettings());
    $researchReportPhase = $submission->getCallForProposal()?->getResearchReportPhase();

    $submission_report_schedule_form = $this->createForm(ResearchReportSubmissionSettingType::class, null, ["researchReportPhase" => $researchReportPhase]);
    $submission_report_schedule_form->handleRequest($request);

    if ($submission_report_schedule_form->isSubmitted()) {
        //create schedule
        return $submissionHelper->createSubmissionReportSchedule($request, $submission);
    }

    if ($request->request->get('approve_research_report')) {
        return $submissionHelper->approveResearchReport($request, $submission);
    }

    $researchReport = new ResearchReport();
    $research_report_form = $this->createForm(ResearchReportType::class, $researchReport);

    if ($submission_report_schedule_count == count($submission->getResearchReports()) + 1) {
        $research_report_form->add('manuscript', FileType::class, [
            "label" => "Manuscript",
            "help" => "Upload Financial clearance",
            "mapped" => false,
            "attr" => [
                "accept" => "application/pdf",
                "class" => "form-control",
            ],

        ]);
    }
    $research_report_form->handleRequest($request);
    if ($research_report_form->isSubmitted() && $research_report_form->isValid()) {

        //create research report
        return $submissionHelper->createResearchReport($research_report_form, $researchReport, $submission);
    }

    $attachements = $entityManager->getRepository(PublishedSubmissionAttachment::class)->findBy(['published_submission' => $publicationstatus]);
    $entityManager = $this->getDoctrine()->getManager();
    $publicationstatus = $entityManager->getRepository(PublishedSubmission::class)->findBy(['submission' => $submission]);
    $Expenses = $entityManager->getRepository(SubmissionBudget::class)->findBy(['submission' => $submission]);
    $reviewsatge = $entityManager->getRepository(ReviewAssignment::class)->findBy(['submission' => $submission], ["id" => "DESC"]);
    $reviews = $entityManager->getRepository(Review::class)->findBy(['submission' => $submission, 'allow_to_view' => 1]);
    $contributors = $entityManager->getRepository(CoAuthor::class)->find($submission);
    ######################Discuussion
    $chat = new Chat();
    $chatform = $this->createForm(ChatType::class, $chat);
    $chatform->handleRequest($request);

    if ($chatform->isSubmitted() && $chatform->isValid()) {
        $chatRepository->add($chat);
        $chat->setDiscussion(1);
        $chat->setSentFrom($this->getUser());
        $chat->setSentAt(new \Datetime());
        return $this->redirectToRoute('app_chat_index', [], Response::HTTP_SEE_OTHER);
    }

    ######################Discuussion
    return $this->render('submission/status.html.twig', [
        'co_authors' => $contributors,
        'expenses' => $Expenses,
        'missingattachmentform' => $missingattachmentform->createView(),
        'comments' => $reviews,
        'datasets' => $attachements,
        'review_assignments' => $reviewsatge,
        'publicationstatus' => $publicationstatus,
        'submission' => $submission,
        'editorialDecisions' => $editorialDecisions,
        'finalreportform' => $finalreportform->createView(),
        'form' => $form->createView(),
        'chatform' => $chatform->createView(),
        'research_report_form' => $research_report_form->createView(),
        'submission_report_schedule_form' => $submission_report_schedule_form->createView(),
        'submission_report_schedule_count' => $submission_report_schedule_count,
    ]);
}

/**
 * @Route("/{id}/datasets", name="publication_datasets_used", methods={"GET","POST"})
 */
public function datasets(Request $request, PublishedSubmission $publicationinfo): Response {
    $entityManager = $this->getDoctrine()->getManager();
    $attachements = $entityManager->getRepository(PublishedSubmissionAttachment::class)->findBy(['published_submission' => $publicationstatus]);

    return $this->render('submission_includes/dataset_used.html.twig', [

        'datasets' => $attachements,

    ]);
}
/**
 * @Route("/{id}/test", name="test_ta", methods={"GET","POST"})
 */
public function tcallsubs(Request $request, CallForProposal $call): Response {
    $entityManager = $this->getDoctrine()->getManager();
    $test = $entityManager->getRepository(CallForProposal::class)->getThematicAreaSubmissions($call);
    dd($test);
    return $this->render('submission_includes/dataset_used.html.twig', [

        'datasets' => $test,

    ]);
}
/**
 * @Route("/{id}/dataset/new/", name="publication_datasets", methods={"GET","POST"})
 */
public function datasetattachment(Request $request, PublishedSubmission $publicationinfo): Response {

    $datasetused = new PublishedSubmissionAttachment();

    $datasetusedform = $this->createFormBuilder($datasetused)
        ->add('attachment_type', EntityType::class, array(
            'placeholder' => '-- Select Attachment Type --',
            'class' => 'App\Entity\AttachementType',
            'attr' => array(
                'empty' => 'Select attachment type-- ',
                'required' => false,
                'class' => 'chosen-select form-inline form-control col-md-6',
            ),
        ))
        ->add('description', TextareaType::class, array(

            'attr' => array(
                'class' => 'form-inline  form-control col-md-6',
            ),
        ))

        ->add('dataset_label', TextType::class, [
            'attr' => array(
                'required' => true,

            ),
            'attr' => array(
                'class' => 'form-inline form-control col-md-6',
            ),

        ])
        ->add('attachment_file', FileType::class, [
            'label' => 'Upload  dataset file',
            'mapped' => false,
            'required' => true,
            'attr' => array(
                'class' => 'form-control col-md-6',
            ),
        ])
        ->getForm();
    $datasetusedform->handleRequest($request);
    if ($datasetusedform->isSubmitted() && $datasetusedform->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $file3 = $datasetused->getAttachmentFile();

        if ($file3 = '') {
            echo 'File not uploaded';
        } else {
            $file3 = $datasetusedform->get('attachment_file')->getData();
            $fileName3 = md5(uniqid()) . '.' . $file3->guessExtension();
            $file3->move($this->getParameter('datasets'), $fileName3);
            $datasetused->setAttachmentFile($fileName3);
        }
        $datasetused->setPublishedSubmission($publicationinfo);

        $entityManager->persist($datasetused);
        $entityManager->flush();
        $this->addFlash(
            'success',
            'Info saved successfully!'
        );
        return $this->redirectToRoute('publication_datasets', array('id' => $publicationinfo->getId()));
    }
    $entityManager = $this->getDoctrine()->getManager();
    $datasets = $entityManager->getRepository(PublishedSubmissionAttachment::class)->findBy(['published_submission' => $publicationinfo]);
    return $this->render('published/dataset_form.html.twig', [
        'datasets' => $datasets,

        'datasetusedform' => $datasetusedform->createView(),

    ]);
}

/**
 * @Route("/attachment/{id}/deleteAttachment", name = "submission_attachment_delete", methods= {"DELETE"})
 */
public function deleteAttachment(Request $request, SubmissionAttachement $submissionAttachement): Response {
    $callForProposal = $submissionAttachement->getSubmission()->getCallForProposal();
    if ($this->isCsrfTokenValid('delete' . $submissionAttachement->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($submissionAttachement);
        $entityManager->flush();
    }

    return $this->redirectToRoute('submission_firststepold', ["id" => $callForProposal->getId()]);
}

/**
 * @Route("/{id}/details", name="submission_show",  methods={"GET","POST"})
 */
public function directorshow(Request $request, Submission $submission, ReviewRepository $reviewRepository, SubmissionHelper $submissionHelper, MailerInterface $mailer): Response {

    // $this->denyAccessUnlessGranted('vw_all_sub');
    $entityManager = $this->getDoctrine()->getManager();
    ################### Are you the one? #################################
    $thisUser = $this->getUser();
    $requesteduser = $submission->getAuthor();
    if ($requesteduser == $thisUser) {

        $this->addFlash("danger", "Sorry you are not allowed for this service ! Thank you!");
        return $this->redirectToRoute('myreviews');
    }

    #####################################

    # $review = $entityManager->getRepository(Review::class)->findBy(['submission' => $submission ] );
    $contributors = $entityManager->getRepository(CoAuthor::class)->findBy(['submission' => $submission]);
    $CollaboratingInstitutions = $entityManager->getRepository(CollaboratingInstitution::class)->findBy(['submission' => $submission]);
    $Expenses = $entityManager->getRepository(SubmissionBudget::class)->findBy(['submission' => $submission]);
    $em = $this->getDoctrine()->getManager();
    $qb = $em->createQueryBuilder();
    $qb = $qb
        ->select('SUM(e.requestedexpense) as totalRequested, SUM(e.approvedexpense) as Totalapproved')
        ->from('App\Entity\Expense', 'e')
        ->where($qb->expr()->andX(
            $qb->expr()->eq('e.submission', ':status'),
        ))
        ->setParameter('status', $submission)
        ->getQuery();
    $Overall_budger_request = $qb->getOneOrNullResult();
    $reviewers = $entityManager->getRepository(ReviewAssignment::class)->findBy(['submission' => $submission]);
    $reviews = $entityManager->getRepository(Review::class)->findBy(['submission' => $submission]);
    ################ Admin Revision#########################

    $review = new Review();
    $review->setSubmission($submission);
    $review->setReviewedBy($this->getUser());

    /**
     * co pi responses to report
     */
    if ($request->request->get('copi_response') || $request->request->get('pi_response')) {

        return $submissionHelper->copiReportResponse($request, $submission);
    }

    /**
     * allow to edit report settings
     */
    if ($request->query->get('action-on-setting')) {

        $submission->getResearchReportSetting()?->setStatus($request->query->get('report-action'));
        $entityManager->flush();
        $this->addFlash("success", "Action done!");
        return $this->redirectToRoute('submission_show', array('id' => $submission->getId()));
    }

    //////allow reviewer if he is only assigned to this submission
    // $form = $this->createFormBuilder($review)
    $form = $this->createForm(ReviewDecisionType::class, $review);

    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $entityManager = $this->getDoctrine()->getManager();
        $reviewfile = $form->get('attachment')->getData();
        if ($reviewfile == "") {
            $review->setAttachment('');
        } else {
            $reviewfile = $form->get('attachment')->getData();
            $Areviewfile = md5(uniqid()) . '.' . $reviewfile->guessExtension();
            $reviewfile->move($this->getParameter('review_files'), $Areviewfile);
            $review->setAttachment($Areviewfile);
        }
        $review->setCreatedAt(new \DateTime());
        $review->setReviewedBy($this->getUser());
        ######################
        $review->setFromDirector(1);
        $review->setAllowToView(1);
        ######################
        ########### Let us mail it ###########
        if ($form->get('remark')->getData() == 4) {

            $applicantmessages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'EMAIL_KEY_SUBMISSION_STATUS_ACCEPTED']);
        } elseif ($form->get('remark')->getData() == 1) {
            $applicantmessages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'EMAIL_KEY_SUBMISSION_STATUS_DECLINED']);
        }

        $applicantsubject = $applicantmessages->getSubject();
        $applicantbody = $applicantmessages->getBody();
        $submission_url = 'submission/' . $submission->getId() . '/status';
        $applicant = $submission->getAuthor()->getEmail();
        $applicantcc = $submission->getAuthor()->getUserInfo()->getAlternativeEmail();
        $applicantname = $submission->getAuthor()->getUserInfo()->getFirstName();
        $emailtwo = (new TemplatedEmail())
            ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
            //->cc(new Address($applicantcc, $applicantname))
            ->to($applicant)
            ->subject($applicantsubject)
            ->htmlTemplate('emails/application_ack.html.twig')
            ->context([
                'subject' => $applicantsubject,
                'body' => $applicantbody,
                'title' => $submission->getTitle(),
                'submission_url' => $submission_url,
                'name' => $applicantname,
                'Authoremail' => $applicant,
            ]);

        $mailer->send($emailtwo);

        ########### End Let us mail it ###########

        $entityManager->persist($review);
        $entityManager->flush();

        $this->addFlash("success", "Decision sent successfully!");
        return $this->redirectToRoute('submission_show', array('id' => $submission->getId()));
    }

    ################ Admin Revision#########################
    return $this->render('submission/submission_details.html.twig', [
        'submission' => $submission,
        'Overall_budger_request' => $Overall_budger_request,
        'review_assignments' => $reviewers,
        'reviews' => $reviews,
        'adminvevisionform' => $form->createView(),
        'co_authors' => $contributors,
        'collaborating_institutions' => $CollaboratingInstitutions,
        'expenses' => $Expenses,

    ]);
}

/**
 * @Route("/my-membership", name="membership", methods={"GET"})
 */
public function mymembership(Request $request, PaginatorInterface $paginator): Response {
    $entityManager = $this->getDoctrine()->getManager();

    $myemail = $this->getUser();
    // $membership = $entityManager->getRepository(CoAuthor::class)->findBy(['email' => $this_is_me]);
    $myresearches = $entityManager->getRepository(CoAuthor::class)->findBy(['researcher' => $myemail], ["id" => "DESC"]);
    ////// if no throw exception
    $Allmyresearches = $paginator->paginate(
        $myresearches,
        $request->query->getInt('page', 1),
        10
    );

    return $this->render('submission/co-authorship.html.twig', [
        'collaborations' => $Allmyresearches,
    ]);
}

/**
 * @Route("/{id}/all-grant-winners/", name="allawarded", methods={"GET","POST"})
 */

public function allawarded(Request $request, CallForProposal $call, PaginatorInterface $paginator): Response {

    $this->denyAccessUnlessGranted('vw_all_sub');
    $entityManager = $this->getDoctrine()->getManager();
    $allawarded = $entityManager->getRepository(Submission::class)->findBy(['awardgranted' => 1, 'callForProposal' => $call]);
    $Allmyresearches = $paginator->paginate(
        $allawarded,
        $request->query->getInt('page', 1),
        15
    );

    return $this->render('submission/index.html.twig', [
        'info' => 'All grant winners ',
        'submissions' => $Allmyresearches,
        'call' => $call,
    ]);
}
/**
 * @Route("/grant-winners/{uidentifier}/", name="call_winners", methods={"GET"})
 */
public function call_winners(Request $request, CallForProposal $callForProposal, PaginatorInterface $paginator): Response {
    $this->denyAccessUnlessGranted('vw_all_sub');
    $entityManager = $this->getDoctrine()->getManager();
    $allawarded = $entityManager->getRepository(Submission::class)->findBy(['awardgranted' => 1, 'call_for_proposal' => $callForProposal]);

    $Allmyresearches = $paginator->paginate(
        $allawarded,
        $request->query->getInt('page', 1),
        15
    );

    return $this->render('submission/index.html.twig', [
        'submissions' => $Allmyresearches,
        'info' => 'Grant winners of' . $callForProposal->getSubject(),
    ]);
}

/**
 * @Route("/my-membership-details/{id}", name="membershipdetails" ,  methods={"GET","POST"})
 */
public function mymembershipdetails(CoAuthor $membership): Response {

    $entityManager = $this->getDoctrine()->getManager(); 
    $submission = $membership->getSubmission(); 

    if (  $this->getUser()  !==$membership->getResearcher()) { 
        $this->addFlash("danger", "Sorry the you are not allowed for this service!");
        return $this->redirectToRoute('membership');
    }

    $entityManager = $this->getDoctrine()->getManager();
    $publicationstatus = $entityManager->getRepository(PublishedSubmission::class)->findBy(['submission' => $submission]);
    $Expenses = $entityManager->getRepository(SubmissionBudget::class)->findBy(['submission' => $submission]);
    $reviewsatge = $entityManager->getRepository(ReviewAssignment::class)->findBy(['submission' => $submission], ["id" => "DESC"]);
    $reviews = $entityManager->getRepository(Review::class)->findBy(['submission' => $submission, 'allow_to_view' => 1]);
    $contributors = $entityManager->getRepository(CoAuthor::class)->find($submission);

    return $this->render('submission/co-authorship_detail.html.twig', [
        'co_authors' => $contributors,
        'expenses' => $Expenses,
        'comments' => $reviews,
        'review_assignments' => $reviewsatge,
        'publicationstatus' => $publicationstatus,
        'cosubmission' => $membership,

    ]);
}

/**
 * @Route("/myresearches", name="myreviews", methods={"GET"})
 */
public function myresearches(Request $request, PaginatorInterface $paginator): Response {
    $entityManager = $this->getDoctrine()->getManager();
    $me = $this->getUser();
    $this_is_me = $this->getUser();
    $myresearches = $entityManager->getRepository(Submission::class)->findBy(['author' => $me], ["id" => "DESC"]);
    ////// if no throw exception
    $Allmyresearches = $paginator->paginate(
        // Doctrine Query, not results
        $myresearches,
        // Define the page parameter
        $request->query->getInt('page', 1),
        // Items per page
        10
    );

    return $this->render('submission/my_submission_review.html.twig', [
        'submissions' => $Allmyresearches,
        'allsubmissions' => $myresearches,
    ]);
}
/**
 * @Route("/del/{id}", name="submission_delete", methods={"POST"})
 */
public function delete(Request $request, Submission $submission): Response {
    // if ($this->isCsrfTokenValid('delete' . $submission->getId(), $request->request->get('_token'))) {
        $entityManager = $this->getDoctrine()->getManager();
        $status = $entityManager->getRepository('App:SubmissionStatus')->findOneBy(['id' => 1]);

        $submission->setSubmissionStatus($status);
        // $submission->setComplete('test');
        // dd($submission);
        $entityManager->persist($submission);
        $entityManager->flush();
        $this->addFlash("success", "Your submissions successfully withdrawed! Thank you!");
        return $this->redirectToRoute('submission_status', array('uidentifier' => $submission->getUidentifier()));

 }


// /**
//  * @Route("/wizsard/{uidentifier}", name="submission_firstssstepold", methods={"GET","POST"})
//  */
// public function metadataold(Request $request, CallForProposal $callForProposal, UserController $test, MailerInterface $mailer): Response {

//     #######################
//     $em = $this->getDoctrine()->getManager();

//     $deadline = $callForProposal->getDeadline();
//     $today = new \DateTime('');
//     if ($deadline <= $today) {

//         $this->addFlash("danger", "Sorry! Call has expired!  Thank you!");
//         return $this->redirectToRoute('myreviews');
//     }

//     ################################
//     ##########################
//     $userdetails = $this->getUser()->getUserInfo();
//     if ($userdetails == '') {
//         $test->checkuser();
//         return $this->redirectToRoute('researchworks');
//     }
//     // dd($userdetails);
//     if (
//         $userdetails->getFirstName() == '' || $userdetails->getMidleName() == '' ||
//         $userdetails->getLastName() == '' ||
//         $userdetails->getCollege() == '' ||
//         $userdetails->getEducationLevel() == '' || $userdetails->getAcademicRank() == ''
//     ) {

//         $this->addFlash("danger", "Please complete your profile first before you submit the proposal  !");

//         return $this->redirectToRoute('researchworks');
//     }

//     ########################## 

//     // $p_i_college = $this->getUser()->getUserInfo()->getCollege();

//     // if (!$p_i_college == $callForProposal->getCollege() and $callForProposal->getAllowPiFromOtherUniversity() == '') {

//     //     $this->addFlash("danger", "You are not allowed make a submission from" . $p_i_college . " !");

//     //     return $this->redirectToRoute('researchworks');
//     // }
//     ########################## Check submission exists #######################

//     // $entityManager = $this->getDoctrine()->getManager();
//     // $submission = $entityManager->getRepository('App:Submission')->findOneBy(['author' => $this->getUser(), 'callForProposal' => $callForProposal]);

//     // if ($p_i_college !== $callForProposal->getCollege() and $callForProposal->getAllowPiFromOtherUniversity()=='') {

//     //     $this->addFlash("danger", "You are not allowed to submit on this  call!");

//     //     return $this->redirectToRoute('myreviews');
//     // }
//     ########################## End Check submission exists #######################

//     $entityManager = $this->getDoctrine()->getManager();
//     $new = true;

//     //dd($request->request);
//         $submission = new Submission();

//     // $submission = $entityManager->getRepository(Submission::class)->findOneBy(['author' => $this->getUser(), 'callForProposal' => $callForProposal]);
//     // if (!$submission) {
//     //     $new = true;
//     //     $submission = new Submission();
//     // } else {
//     //     if ($submission->getStep() == 10) {
//     //         // $this->addFlash('warning', "You have a  submission with this call. Edit your submission instead.");
//     //         // $submission = new Submission();
//     //     $entityManager->persist($submission);
            
//     //                     //  return $this->redirectToRoute('editsubmission', ["id" => $submission->getId()]);

       
//     //     }
//     // }
//     $submission->setCallForProposal($callForProposal);
//     $submission->setUidentifier(md5(uniqid()));

//     $form = $this->createForm(SubmissionType::class, $submission);
//     $form->handleRequest($request);
//     $submission->setStatus(1);
//     if ($form->isSubmitted() && $form->isValid()) {
//         $submission->setAuthor($this->getUser());

//         if ($new) {
//             $entityManager->persist($submission);
//         }
//         $entityManager->persist($submission);
 
//         if ($submission->getStep() == 10) {
//             $submission->setSentAt(new \DateTime());

//             $submission->setComplete("completed");
//             $entityManager->flush();
//             $this->addFlash('success', "submission complete");

//             $invitation_url = 'submission/my-membership';
//             #####################################
//             ///////////// Let us email  co-pis    to  remind
//             $messages = $entityManager->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'SUBMISSION_CO_PI_INVITATION']);
//             $subject = $messages->getSubject();
//             $body = $messages->getBody();
//             $em = $this->getDoctrine()->getManager();
//             $query = $entityManager->createQuery(
//                 'SELECT u.email ,  u.username 
//                     FROM App:CoAuthor s
//                     JOIN s.researcher u
//                     WHERE s.submission = :submission' 

//             )
//                 ->setParameter('submission', $submission);
//             $recepients = $query->getResult();
//             $em = $this->getDoctrine()->getManager();
//             $qb = $em->createQueryBuilder();
//             $messages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'SUBMISSION_CO_PI_INVITATION']);
//             $subject = $messages->getSubject();
//             $body = $messages->getBody();
//             foreach ($recepients as $row) {
//                 $theEmails[] = $row['email'] . ' ';
//                 $theNames[] = $row['username'] . ' ';
//                 $theFirstNames[] = $row['username'] . ' ';
//             }
//             ////////////
//             $length = count($recepients);
//             for ($i = 0; $i < $length; $i++) {
//                 ///////////////
//                 $theFirstName = $theFirstNames[$i];
//                 if ($theFirstName == '') {
//                     $theFirstName = $theNames[$i];
//                     // dd($theFirstName);
//                 }
//                 $theEmail = $theEmails[$i];
//                 $email = (new TemplatedEmail())
//                     ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
//                      ->to(new Address($theEmails[$i], $theFirstNames[$i]))
//                     ->bcc(new Address($theEmails[$i], $theFirstNames[$i]))
//                     ->subject($subject)
//                     ->htmlTemplate('emails/co-authorship-invitation.html.twig')
//                     ->context([
//                         'subject' => $subject,
//                         'body' => $body,
//                         'title' => $submission->getTitle(),
//                         'submission_url' => $invitation_url,
//                         'name' => $theFirstName,
//                         'Authoremail' => $theEmail,
//                     ]);
//                 // $mailer->send($email);
//             }
//             ##########
//             $applicantmessages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'EMAIL_KEY_SUBMISSION_ACKNOWLEDGEMENT']);
//             $applicantsubject = $applicantmessages->getSubject();
//             $applicantbody = $applicantmessages->getBody();

//             $submission_url = 'submission/' . $submission->getId() . '/status';
//             $applicant = $submission->getAuthor()->getEmail();
//             $applicantname = $submission->getAuthor()->getUserInfo()->getFirstName();
//             $emailtwo = (new TemplatedEmail())
//                 ->from(new Address('research@ju.edu.et', $this->getParameter('app_name')))
//                 ->to($applicant)
//                 ->subject($applicantsubject)
//                 ->htmlTemplate('emails/application_ack.html.twig')
//                 ->context([
//                     'subject' => $applicantsubject,
//                     'body' => $applicantbody,
//                     'title' => $submission->getTitle(),
//                     'submission_url' => $submission_url,
//                     'name' => $applicantname,
//                     'Authoremail' => $applicant,
//                 ]);

//             // $mailer->send($emailtwo);

//             // $sendEmail = new SendEmailMessage([$this->getUser()->getEmail()], Constants::EMAIL_KEY_SUBMISSION_ACKNOWLEDGEMENT, "emails/application_ack.html.twig", [
//             // ]);
//             // $this->dispatchMessage($sendEmail);
//             // $emails = [];
//             // foreach ($submission->getCoAuthors() as $key => $author) {
//             //     $emails[] = $author->getEmail();
//             // }
//             // $sendEmail = new SendEmailMessage($emails, Constants::EMAIL_KEY_SUBMISSION_ACKNOWLEDGEMENT, "emails/application_ack.html.twig", [
//             // ]);
//             // $this->dispatchMessage($sendEmail);
//             return $this->redirectToRoute('submission_status', array('id' => $submission->getId()));

//             // return $this->redirectToRoute('myreviews');
//         }
//         $entityManager->flush();

//         ##############################

//         return $this->redirectToRoute('submission_firststepold', ["uidentifier" => $callForProposal->getUidentifier()]);
//     }
//     return $this->render('submission/metadata.html.twig', [
//         'submissionform' => $form->createView(),
//         'submission' => $submission,
//         'call' => $callForProposal,
//     ]);
// }

} 
