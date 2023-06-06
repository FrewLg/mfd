<?php

namespace App\Helper;

use App\Entity\ResearchReport;
use App\Entity\ResearchReportComment;
use App\Entity\ResearchReportSetting;
use App\Entity\ResearchReportSubmissionSetting;
use App\Entity\Submission;
use App\Entity\SubmissionFinalReport;
use App\Entity\User;
use App\Service\FileUploader;
use App\Utils\Constants;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;


class SubmissionHelper
{

    private $containerInterface;
    private $tokenInterface;
    private $em;
    private $urlGenerator;
    private $flashBagInterface;
    private $mailHelper;
    private $fileUploader;
    private $user;
    public function __construct(ContainerInterface $containerInterface, FileUploader $fileUploader, TokenStorageInterface $tokenInterface, EntityManagerInterface $em, FlashBagInterface $flashBagInterface, UrlGeneratorInterface $urlGenerator, MailHelper $mailHelper)
    {
        $this->containerInterface = $containerInterface;
        $this->tokenInterface = $tokenInterface;
        $this->flashBagInterface = $flashBagInterface;

        $this->urlGenerator = $urlGenerator;
        $this->mailHelper = $mailHelper;
        $this->fileUploader = $fileUploader;
        $this->em = $em;

        $this->user = $this->tokenInterface->getToken()->getUser();
    }
    public function createResearchReport($research_report_form, ResearchReport $researchReport, Submission $submission)
    {
        foreach ($researchReport->getResearchReportChallenges() as $key => $value) {
            $value->setReport($researchReport);
        }
       $query = $this->em->createQuery(
            'UPDATE  App:ResearchReportSubmissionSetting r SET  r.isSubmitted=1 WHERE r.submission = :submission and r.phase =:phase'
         )
         ->setParameter('submission', $submission->getId())
         ->setParameter('phase', $submission->getResearchReports()?$submission->getResearchReports()->count()+1:1)
         ->execute();

        $uploadedFile = $research_report_form['file']->getData();
        $newFilename =   $this->fileUploader->upload($uploadedFile, "research-report");

        $researchReport->setFile($newFilename);

        $uploadedFile = $research_report_form['financial_clearance']->getData();
        $newFilename =   $this->fileUploader->upload($uploadedFile, "research-report");


        $researchReport->setFinancialClearance($newFilename);

        if (isset($research_report_form['manuscript'])) {

            $uploadedFile = $research_report_form['manuscript']->getData();
            $newFilename =   $this->fileUploader->upload($uploadedFile, "research-report");

            $submission->setManuscript($newFilename);
        }
        $researchReport->setFileType($uploadedFile->getClientOriginalExtension());
        $researchReport->setSubmission($submission);
        $researchReport->setSubmittedBy($this->user);

        $this->em->persist($researchReport);
        $this->em->flush();


        foreach ($submission->getCoAuthors() as $key => $value) {



            $this->mailHelper->sendEmail(
                $value->getResearcher()->getEmail(),
                "New research report submitted",
                "emails/general.html.twig",
                [
                    "info" => "Dear ".  $value->getResearcher()->getUserInfo()->getFirstName(),
                    "subject" => "New research report submitted",
                    "body" => "
                    The project titled as <b>'" . $submission->getTitle() . "'</b> you assigned as a CO-PI submitted a new report on <b>" . ((new \DateTime())->format('Y-m-d H:iA')) . "</b> .
                    <br> Please confirm that you are aware and agree on the report
                    following the link below <a href='" . $this->urlGenerator->generate("submission_status", ['id' => $submission->getId()], UrlGeneratorInterface::ABSOLUTE_URL) . "'>Click here to get the report</a>
                    ",
                ]
            );
        }


        $this->flashBagInterface->add("success", "Report submitted successfully!!");
        return $this->redirectBack($submission);
    }

    public function finalReport(Request $request, SubmissionFinalReport $terminal_report, Submission $submission)
    {

        $files = $request->files->get('submission_final_report');

        $uploadedFile = $files['fullReport'];
        $destination = $this->containerInterface->getParameter('kernel.project_dir') . '/public/uploads/research-report';
        $newFilename = $this->user->getId() . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
        $uploadedFile->move($destination, $newFilename);


        $terminal_report->setFullReport($newFilename);
        $uploadedFile = $files['manuscript'];
        $destination = $this->containerInterface->getParameter('kernel.project_dir') . '/public/uploads/research-report';
        $newFilename = $this->user->getId() . uniqid() . '.' . $uploadedFile->getClientOriginalExtension();
        $uploadedFile->move($destination, $newFilename);


        $terminal_report->setManuscript($newFilename);
        $terminal_report->setSubmission($submission);

        $this->em->persist($terminal_report);

        $this->em->flush();

        $this->flashBagInterface->add("success", "Report submitted successfully!!");
        return $this->redirectBack($submission);
    }
    public function createSubmissionReportSchedule(Request $request, Submission $submission)
    {
        $query = $this->em->createQuery(
            'DELETE FROM App:ResearchReportSubmissionSetting r WHERE r.submission = :submission'
         )->setParameter('submission', $submission->getId())->execute();

        if ($submission->getResearchReportSetting()) {

            $reportSetting = $submission->getResearchReportSetting();
            $reportSetting->setIsAltered(true);
            $reportSetting->setUpdatedAt(new \DateTime());
            $reportSetting->setUpdatedAt(new \DateTime());
        } else {

            $reportSetting = new ResearchReportSetting();
            $reportSetting->setSubmission($submission);
            $reportSetting->setIsAltered(false);
            $reportSetting->setIsApproved(false);
            $reportSetting->setCreatedAt(new \DateTime());
            $reportSetting->setCreatedBy($this->user);
        }
        $reportSetting->setStatus(ResearchReportSetting::UNEDITABLE);
        $this->em->persist($reportSetting);

        $this->em->flush();


        foreach ($request->request->get('research_report_submission_setting') as $key => $value) {

            if ($key != "_token") {
                $submission_report_schedule = new ResearchReportSubmissionSetting();

                $submission_report_schedule->setSubmission($submission);
                $submission_report_schedule->setPhase(explode("_", $key)[1]);
                $submission_report_schedule->setSubmissionDate(new \DateTime($value));
                $submission_report_schedule->setIsSubmitted(1);
                $submission_report_schedule->setSetting($reportSetting);

                $this->em->persist($submission_report_schedule);

                $this->em->flush();
            }
        }
        $this->flashBagInterface->add("success", "Report Schedule submitted successfully!!");
        return $this->redirectBack($submission);
    }

    public function copiReportResponse(Request $request, Submission $submission)
    {


        if ($request->request->get("pi_action")) {
            $request_data = $request->request->get("pi_action");

            $researchReport = $this->em->getRepository(ResearchReport::class)->find($request_data['research_report_id']);
            $copi_response = $this->em->getRepository(ResearchReportComment::class)->find($request_data['research_report_comment_id']);


            $copi_response->setPIResponse($request_data['reason']);
            $copi_response->setPIRespondedAt(new \DateTime());

            $this->em->flush();
        }
        if ($request->request->get("copi_response")) {
            $copi_response = new ResearchReportComment();

            $request_data = $request->request->get("copi_action");

            $researchReport = $this->em->getRepository(ResearchReport::class)->find($request_data['research_report_id']);





            $copi_response->setReport($researchReport);
            if ($request_data['agree'] == 0)
                $copi_response->setRejectionReason($request_data['reason']);
            $copi_response->setWasAgreed($request_data['agree']);
            $copi_response->setCommentedBy($this->user);

            $this->em->persist($copi_response);


            $this->em->flush();
            if (!$copi_response->getWasAgreed()) {
                $this->mailHelper->sendEmail(
                    $submission->getAuthor()->getEmail(),
                    "Co-PI response on your submission",
                    "emails/general.html.twig",
                    [
                        "info" => "Co-PI response on your submission",
                        "subject" => "Co-PI response on your submission",
                        "body" => "Co-PI response on your submission <a href='" . $this->urlGenerator->generate("submission_status", ['id' => $submission->getId()], UrlGeneratorInterface::ABSOLUTE_URL) . "'>Click here to get your submission</a>",
                    ]
                );
            }
        }
        $this->flashBagInterface->add("success", "your response submitted successfully!!");
        return $this->redirectBack($submission);
    }

    public function approveResearchReport(Request $request, Submission $submission)
    {

        if ($request->request->get('approve_research_report')) {

            $researchReport = $this->em->getRepository(ResearchReport::class)->find($request->request->get('research_report_id'));

            $researchReport->setSubmissionStatus(ResearchReport::STATUS_APPROVED);
            $researchReport->setApprovedBy($this->user);
            $researchReport->setApprovedAt(new \DateTime());

            //note here
            // send email for all members

            if ($request->request->get('approve_research_generate')) {

                //  $submission->setUidentifier(rand(1000,1000000));
                $submission->setStatus(Constants::SUBMISSION_STATUS_CLOSED);
                $this->mailHelper->sendEmail(
                    $submission->getAuthor()->getEmail(),
                    "Your submission is Complete",
                    "emails/general.html.twig",
                    [
                        "info" => "Your submission is Complete",
                        "subject" => "Your submission is Complete",
                        "body" => "your research is complete <a href='" . $this->urlGenerator->generate("submission_status", ['id' => $submission->getId()], UrlGeneratorInterface::ABSOLUTE_URL) . "'>Click here to view the submission</a>",
                    ]
                );
                foreach ($submission->getCoAuthors() as $key => $value) {
                    $this->mailHelper->sendEmail(
                        $value->getEmail(),
                        "Your submission is Complete",
                        "emails/general.html.twig",
                        [
                            "info" => "Your submission is Complete",
                            "subject" => "Your submission is Complete",
                            "body" => "your research is complete <a href='" . $this->urlGenerator->generate("submission_status", ['id' => $submission->getId()], UrlGeneratorInterface::ABSOLUTE_URL) . "'>Click here to view the submission</a>",
                        ]
                    );
                }
            }

            $this->em->flush();
            $this->flashBagInterface->add("success", "Approved successfully!!");



            return $this->redirectBack($submission);
        }
    }



    public function redirectBack(Submission $submission)
    {
        return new RedirectResponse($this->urlGenerator->generate("submission_status", ["id" => $submission->getId()]));
    }
}
