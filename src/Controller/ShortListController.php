<?php

namespace App\Controller;

use App\Entity\CallForProposal;
use App\Entity\Review;
use App\Entity\ReviewAssignment;
use App\Entity\Submission;
use App\Entity\SubmissionAttachement;
use App\Form\ShortlistDecisionType;
use Lexik\Bundle\TranslationBundle\Util\Csrf\CsrfCheckerTrait;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Address;
use Symfony\Component\Routing\Annotation\Route;

// use Lexik\Bundle\TranslationBundle\Util\Csrf\CsrfCheckerTrait;

/**
 * @Route("/short-list")
 */
class ShortListController extends AbstractController {
    use CsrfCheckerTrait;

    // private $filename;

    // public function __construct($filePath) {
    //     $this->filename = $filePath;
    // }

    // public function __construct($filePath) {
    //     $this->filename = $filePath;
    // }/home/ghost/Desktop/projects/research-ju/public/uploads/submission/football-betting-and-its-impact-proposal-final-619602dda84ba146154715.docx

    /**
     * @Route("/{id}/find", name="short_list", methods={"DELETE", "GET","POST"})
     */
    public function findall(CallForProposal $callForProposal, ReviewAssignment $reviewAssignment): Response {
        $this->denyAccessUnlessGranted('short_list_view');

        $entityManager = $this->getDoctrine()->getManager();
        // $entityManager->remove($reviewAssignment);

        $this->addFlash(
            'success',
            'Reviewer allowed to edit the review  successfully!'
        );
        $entityManager->flush();

        return $this->redirectToRoute('review_assignment_new', array('id' => $reviewAssignment->getSubmission()->getId()));

    }

    /**
     * @Route("/{id}/findone", name="short_list_findone", methods={"DELETE", "GET","POST"})
     */
    public function findone(Submission $submission, Request $request, MailerInterface $mailer): Response {
        // $this->denyAccessUnlessGranted('short_list_view');
        $entityManager = $this->getDoctrine()->getManager(); 

        $fileName = $submission->getProposal(); 
if ($fileName==""){
$fileName = $entityManager->getRepository(SubmissionAttachement::class)->
            findOneBy(['submission' => $submission ]);
        $filePath = $this->getParameter('upload_destination') . '/' . $fileName->getFile(1);

}

        $filePath = $this->getParameter('upload_destination') . '/' . $submission->getProposal();
#$found = NULL; 
#$result=NULL;
        if (!$filePath) {
            $this->addFlash(
                'danger',
                'File not found!'
            );
            return $this->redirectToRoute('submission_show', array('id' => $submission->getId()));

        }

        $content = file_get_contents($filePath);

        $handle = fopen($filePath, "r");
        $zip = zip_open($filePath);

        $striped_content = '';
        $content = '';

        if (!$filePath || !file_exists($filePath)) {
            $this->addFlash(
                'danger',
                'File not found!'
            );
            return $this->redirectToRoute('submission_show', array('id' => $submission->getId()));

        }

        $zip = zip_open($filePath);

        if (!$zip) {
            $this->addFlash(
                'danger',
                'File not found! or invalid    file format'
            );
            return $this->redirectToRoute('submission_show', array('id' => $submission->getId()));

        }

        if (!$zip || is_numeric($zip)) {
            $this->addFlash(
                'danger',
                'Unable to read document data! Invalid document  file'
            );
            return $this->redirectToRoute('submission_show', array('id' => $submission->getId()));

        }

        while ($zip_entry = zip_read($zip)) {

            if (zip_entry_open($zip, $zip_entry) == FALSE) {
                continue;
            }

            if (zip_entry_name($zip_entry) != "word/document.xml") {
                continue;
            }

            $content .= zip_entry_read($zip_entry, zip_entry_filesize($zip_entry));

            zip_entry_close($zip_entry);
        } // end while

        zip_close($zip);

        $content = str_replace('</w:r></w:p></w:tc><w:tc>', " ", $content);
        $content = str_replace('</w:r></w:p>', "\r\n", $content);
        $striped_content = strip_tags($content);
        $count = 0;

        $copis = $submission->getCoAuthors();
        if ($copis) {
            foreach ($copis as $value) {
                $count = 0;

                $patternsc = array();
                $patternsc[0] = $value->getResearcher()->getUserInfo()->getFirstName();
                $patternsc[1] = $value->getResearcher()->getUserInfo()->getMidleName();
                $patternsc[2] = $value->getResearcher()->getUserInfo()->getLastName();

                $replacementsc = array();
                $replacementsc[0] = "<b class='text-danger' style='background-color: rgb(255, 255, 102); color: rgb(0, 0, 0);'> " . $patternsc[0] . "</b>";
                $replacementsc[1] = "<b class='text-danger' style='background-color: rgb(255, 255, 102); color: rgb(0, 0, 0);'> " . $patternsc[1] . "</b>";
                $replacementsc[2] = "<b class='text-danger' style='background-color: rgb(255, 255, 102); color: rgb(0, 0, 0);'> " . $patternsc[2] . "</b>";

                $patterns = array();

                $patterns[0] = $submission->getAuthor()->getUserInfo()->getFirstName();
                $patterns[1] = $submission->getAuthor()->getUserInfo()->getMidleName();
                $patterns[2] = $submission->getAuthor()->getUserInfo()->getLastName();
                // $patterns[1] = 'considera';
                $replacements = array();
                $replacements[0] = "<b class='text-danger' style='background-color: rgb(255, 255, 102); color: rgb(0, 0, 0);'> " . $patterns[0] . "</b>";
                $replacements[1] = "<b class='text-danger' style='background-color: rgb(255, 255, 102); color: rgb(0, 0, 0);'> " . $patterns[1] . "</b>";
                $replacements[2] = "<b class='text-danger' style='background-color: rgb(255, 255, 102); color: rgb(0, 0, 0);'> " . $patterns[2] . "</b>";

                if (
                    preg_match("/{$patternsc[0]}\b/i", $striped_content)
                    || preg_match("/{$patternsc[1]}\b/i", $striped_content)
                    || preg_match("/{$patternsc[2]}\b/i", $striped_content)
                    || preg_match("/{$patterns[0]}\b/i", $striped_content)
                    || preg_match("/{$patterns[1]}\b/i", $striped_content)
                    || preg_match("/{$patterns[2]}\b/i", $striped_content)

                ) {
                     $striped_content = str_replace($patternsc, $replacementsc, $striped_content, $count);
                    $striped_content = str_replace($patterns, $replacements, $striped_content, $count);
                     $result = "Name(s) of the research team members has appeared in a  proposal file  <a href='#' class='avatar-box thumb-xxs align-self-center'>  <span class='avatar-title bg-soft-danger rounded-circle font-13 font-weight-normal'>  ".$count."   </span>  </a> times !";

                    $found = 1;

                }

            }
        } else {
//////////////////////////
            $patterns = array();
            $patterns[0] = $submission->getAuthor()->getUserInfo()->getFirstName();
            $patterns[1] = $submission->getAuthor()->getUserInfo()->getMidleName();
            $patterns[2] = $submission->getAuthor()->getUserInfo()->getLastName();
// $patterns[1] = 'considera';
            $replacements = array();
            $replacements[0] = "<b class='text-danger' style='background-color: rgb(255, 255, 102); color: rgb(0, 0, 0);'> " . $patterns[0] . "</b>";
            $replacements[1] = "<b class='text-danger' style='background-color: rgb(255, 255, 102); color: rgb(0, 0, 0);'> " . $patterns[1] . "</b>";
            $replacements[2] = "<b class='text-danger' style='background-color: rgb(255, 255, 102); color: rgb(0, 0, 0);'> " . $patterns[2] . "</b>";

            if (preg_match("/{$patterns[0]}\b/i", $striped_content)
                || preg_match("/{$patterns[1]}\b/i", $striped_content)
                || preg_match("/{$patterns[2]}\b/i", $striped_content)

            ) {
                $striped_content = str_replace($patterns, $replacements, $striped_content, $count);
                $result = "Name(s) of the research team members has appeared  in a  proposal file  <a href='#' class='avatar-box thumb-xxs align-self-center'>  <span class='avatar-title bg-soft-danger rounded-circle font-13 font-weight-normal'>  ".$count."   </span>  </a> times !";

                $found = 1;

            }
        }

        if (!$found ==NULL) {
            $this->addFlash(
                'danger',
                "Some of the name(s) of the research team members has been found in a  proposal file! Hence the submission violates the  guideline!"
            );
        } else {

            $result = "Researcher's name is not found in proposal  file!";
            $this->addFlash(
                'success',
                "Clear! the researcher's name is not found in the proposal document"
            );
        }

        if ($striped_content !== false) {
            $docu = nl2br($striped_content);
        } else {
            echo 'Couldn\'t the file. Please check that file.';
        }

################################### Short list Decision #################################################
        $em = $this->getDoctrine()->getManager();

        $review = new Review();
        $review->setSubmission($submission);
        $review->setReviewedBy($this->getUser()); 
//////allow reviewer if he is only assigned to this submission 
        $form = $this->createForm(ShortlistDecisionType::class, $review);
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
            $review->setRemark(1);
            $review->setAllowToView(1);
            ######################
            ########### Let us mail it ###########
            $applicantmessages = $em->getRepository('App:EmailMessage')->findOneBy(['email_key' => 'EMAIL_KEY_SUBMISSION_STATUS_DECLINED']);
            $applicantsubject = $applicantmessages->getSubject();
            $applicantbody = $applicantmessages->getBody();
            $submission_url = 'submission/' . $submission->getId() . '/status';
            $applicant = $submission->getAuthor()->getEmail();
            $applicantname = $submission->getAuthor()->getUserInfo()->getFirstName();
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

            $mailer->send($emailtwo);

            ########### End Let us mail it ###########

            $entityManager->persist($review);
            $entityManager->flush();

            $this->addFlash("success", "Decision sent successfully!");
            return $this->redirectToRoute('submission_show', array('id' => $submission->getId()));
        }

###################################Decision#################################################
         return $this->render('submission/doc.html.twig', [
            'document' => $docu,
            'result' => $result,
            'count' => $count, 
            'file' => $fileName,
            'submission' => $submission,
        ]);

    }

}
