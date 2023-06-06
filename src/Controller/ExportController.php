<?php

namespace App\Controller;
use App\Entity\CallForProposal;
use App\Entity\College;
use App\Entity\Submission;
use App\Entity\ThematicArea;
use App\Entity\TrainingParticipant;
use App\Repository\SubmissionRepository;
use App\Utils\Constants;
use Knp\Component\Pager\PaginatorInterface;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
// use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\BinaryFileResponse; 
use Symfony\Component\HttpFoundation\File\File;
// use Symfony\Component\HttpFoundation\File\MimeType\FileinfoMimeTypeGuesser;
// use Symfony\Component\Mime\FileinfoMimeTypeGuesser;
use \ZipArchive;
/**
 * @Route("/export")
 */

class ExportController extends AbstractController {

    /**
     * @Route("/{id}/research-teams", name="export_researchersexcel", methods={"GET","POST"})
     */
    public function theams(CallForProposal $call) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $submissions = $em->getRepository(Submission::class)->findBy(['callForProposal'=>$call]);
        $spreadsheet = new Spreadsheet();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Title.');
        $sheet->setCellValue('C1', 'PI');
        $sheet->setCellValue('D1', 'Co-PI (s)');
        $sheet->setCellValue('E1', 'PI\'s Institute');
        $sheet->setCellValue('F1', 'Not confirmed');
        $sheet->setCellValue('G1', 'PI\'s Department');
        $sheet->setTitle("Researcher");
        $counter = 2;
        foreach ($submissions as $phoneNumber) {
            $sheet->setCellValue('A' . $counter, $phoneNumber->getId());
            $sheet->setCellValue('B' . $counter, $phoneNumber->getTitle());
            $counter2 = 2;
            ########################
            $sheet->setCellValue('C' . $counter, $phoneNumber->getAuthor()->getUserInfo());
            $sheet->setCellValue('E' . $counter, $phoneNumber->getAuthor()->getUserInfo()->getCollege());
            $sheet->setCellValue('G' . $counter, $phoneNumber->getAuthor()->getUserInfo()->getDepartment());
            foreach ($phoneNumber->getCoAuthors() as $CoAuthors) {
                $sheet->setCellValue('D' . $counter, $CoAuthors->getResearcher()->getUserInfo());

                if ($CoAuthors->getConfirmed() == NULL) {
                    $sheet->setCellValue('F' . $counter, $CoAuthors->getResearcher()->getUserInfo());

                }
                $counter++;
                $counter2++;
            }

############################
            $counter++;
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Researchers.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }
  


/* create a compressed zip file */
public function createZipArchive($files = array(), $destination = '', $overwrite = false) {

    if(file_exists($destination) && !$overwrite) { return false; }
 
    $validFiles = array();
    if(is_array($files)) {
       foreach($files as $file) {
          if(file_exists($file)) {
             $validFiles[] = $file;
          }
       }
    }
 
    if(count($validFiles)) {
       $zip = new ZipArchive();
       if($zip->open($destination,$overwrite ? ZIPARCHIVE::OVERWRITE : ZIPARCHIVE::CREATE) == true) {
          foreach($validFiles as $file) {
             $zip->addFile($file,$file);
          }
          $zip->close();
          return file_exists($destination);
       }else{
           return false;
       }
    }else{
       return false;
    }
 }

 /**
     * @Route("/{uidentifier}/filedownload", name="filedownload", methods={"GET","POST"})
     * 
    * Create and download some zip documents.
    *
    * @param array $documents
    * @return Symfony\Component\HttpFoundation\Response
    */

    public function filedownload(CallForProposal $callForProposal ,array $documents=null)
{
    // Do your stuff with $files
    $em = $this->getDoctrine()->getManager();
    $documents = $em->getRepository('App:Submission')->findBy(['callForProposal' => $callForProposal]);
     $path=$this->getParameter('upload_destination');
     $files = [];
    $em = $this->getDoctrine()->getManager();

    foreach ($documents as $document) {
        if($document->getProposal()){

        array_push($files, $path.'/' . $document->getProposal());
    }
    else{
    $this->addFlash("danger", "File   " .$document->getUidentifier().' not found! of submission '.$document->getAuthor().$document->getTitle());
    return $this->redirectToRoute('callresponses',['id'=>$callForProposal->getId()]);

        
    }

    }
    // Create new Zip Archive.
    // $zip = new \ZipArchive();
    // // The name of the Zip documents.
    // $zipName = 'Documents.zip';
    // $zip->open($zipName,  \ZipArchive::CREATE);
    $colleges = $em->getRepository('App:College')->findAll();


    $zip = new \ZipArchive();
        // The name of the Zip documents.
        $date= $callForProposal->getPostDate()->format('Y-m-d');

        $zipName = 'Concept notes of a call '.$date.'-'.$callForProposal->getApprovedBy()->getUserInfo()->getCollege()->getPrefix().'.zip';
        $zip->open($zipName,  \ZipArchive::CREATE);
    //  foreach ($colleges as $college) {
    

   foreach ($files as $file) {
    // foreach ($college->getCollegeSubmissions(['callForProposal'=>$callForProposal]) as $file) {

             if($file!==NULL){
         try{

           $zip->addFromString(basename($file),  file_get_contents($file));
           }
           catch(\ZipStream\Exception\FileNotReadableException $e){    
            $this->addFlash(
               'danger',
               'There is an exception  occured '.$e.'error' );
               return $this->redirectToRoute('callresponses',['id'=>$callForProposal->getId()]);
        
   }
            // $zip->addFromString(basename($file),  file_get_contents($file));
        }
       
    }
    // }
    $zip->close();
    $response = new Response(file_get_contents($zipName));
    $response->headers->set('Content-Type', 'application/zip');
    $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
    $response->headers->set('Content-length', filesize($zipName));

    @unlink($zipName);

    return $response;
     
}

 /**
     * @Route("/{uidentifier}/attachements", name="attachements", methods={"GET","POST"})
     * 
* Create and download some zip documents.
*
* @param array $documents
* @return Symfony\Component\HttpFoundation\Response
*/

    public function attachements(CallForProposal $callForProposal ,array $documents=null)
{
    // Do your stuff with $files
    $em = $this->getDoctrine()->getManager();
   // $documents = $em->getRepository('App:Submission')->findBy(['callForProposal' => $callForProposal]);
    $documents = $em->getRepository('App:SubmissionAttachement')->submissionAttachementByCall( $callForProposal);
    // dd($documents);
     $path=$this->getParameter('upload_destination');
     $files = [];
    $em = $this->getDoctrine()->getManager();

    foreach ($documents as $document) {
        if($document->getFile()){

        array_push($files, $path.'/' . $document->getFile());
    }
    else{
        $this->addFlash("danger", "File   " .$document->getSubmission()->getUidentifier().' not found! of submission '.$document->getSubmission()->getAuthor().$document->getSubmission()->getTitle().'<br>');
        return $this->redirectToRoute('callresponses',['id'=>$callForProposal->getId()]);
    // contine;
   //break; exit();
   break;
            
        }
    }
    // Create new Zip Archive.
    // $zip = new \ZipArchive();
    // // The name of the Zip documents.
    // $zipName = 'Documents.zip';
    // $zip->open($zipName,  \ZipArchive::CREATE);
    $colleges = $em->getRepository('App:College')->findAll();


    $zip = new \ZipArchive();
        // The name of the Zip documents.
        $date= $callForProposal->getPostDate()->format('Y-m-d');

        $zipName = 'Attachements of a call '.$date.'-'.$callForProposal->getApprovedBy()->getUserInfo()->getCollege()->getPrefix().'.zip';
        $zip->open($zipName,  \ZipArchive::CREATE);
    //  foreach ($colleges as $college) {
    

   foreach ($files as $file) {
    // foreach ($college->getCollegeSubmissions(['callForProposal'=>$callForProposal]) as $file) {

             if($file!==NULL){
         try{
         $fileexists=   require $file;
            if ($fileexists){
                
           $zip->addFromString(basename($file),  file_get_contents($file));
           }
           }
           catch(\ZipStream\Exception\FileNotReadableException $e){    
            $this->addFlash(
               'danger',
               'There is an exception  occured '.$e.'error' );
               //exit();
               break;
               return $this->redirectToRoute('callresponses',['id'=>$callForProposal->getId()]);
        
   }
            // $zip->addFromString(basename($file),  file_get_contents($file));
        }
       
    }
    // }
    $zip->close();
    $response = new Response(file_get_contents($zipName));
    $response->headers->set('Content-Type', 'application/zip');
    $response->headers->set('Content-Disposition', 'attachment;filename="' . $zipName . '"');
    $response->headers->set('Content-length', filesize($zipName));

    @unlink($zipName);

    return $response;
     
}
    /**
     * @Route("/participant", name="exportexcelparticipant", methods={"GET","POST"})
     */
    public function trainingparticipant() {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $em = $this->getDoctrine()->getManager();

        $submissions = $em->getRepository(TrainingParticipant::class)->findAll();
        $spreadsheet = new Spreadsheet();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Full name');
        $sheet->setCellValue('C1', 'Participant\'s Institute');
        $sheet->setCellValue('D1', 'Participant\'s College');
        $sheet->setTitle("Participants");

        $counter = 2;
        foreach ($submissions as $phoneNumber) {
            $sheet->setCellValue('A' . $counter, $phoneNumber->getId());
            $sheet->setCellValue('B' . $counter, $phoneNumber->getParticipant()->getUserInfo());
            $sheet->setCellValue('C' . $counter, $phoneNumber->getParticipant()->getUserInfo()->getCollege());
            $sheet->setCellValue('D' . $counter, $phoneNumber->getParticipant()->getUserInfo()->getDepartment());
            $counter++;
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Traninig participant.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @Route("/{id}/external-rev", name="alexternal_rev", methods={"GET","POST"})
     */
    public function externalreviewers(CallForProposal $call): Response {
        $this->denyAccessUnlessGranted('assn_clg_cntr');

        $entityManager = $this->getDoctrine()->getManager();
        #######################
        $query2 = $entityManager->createQuery(
            'SELECT  u.email , u.id, pi.last_name , pi.first_name, pi.midle_name,  pi.image, u.is_reviewer,   count(b.id) as subs,  count(u.id) as review_assignment
            FROM App:ReviewAssignment s
            JOIN s.reviewer u
            JOIN u.userInfo pi
            JOIN s.submission b
            JOIN b.callForProposal c
            where  u.is_reviewer =:external and c.id=:call   GROUP BY u.id ORDER BY  pi.first_name
        ')
            ->setParameter('call', $call)
            ->setParameter('external', 1);
        $recepientextrnal = $query2->getResult();
        $spreadsheet = new Spreadsheet();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Full name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Number of assignments');
        $sheet->setCellValue('E1', 'Staff Membership');
        $sheet->setTitle("External reviewers");
        $idcounter = 1;

        $counter = 2;
        foreach ($recepientextrnal as $phoneNumber) {
            $sheet->setCellValue('A' . $counter, $idcounter);
            $sheet->setCellValue('B' . $counter, $phoneNumber['first_name'] . $phoneNumber['midle_name'] . $phoneNumber['last_name']);
            $sheet->setCellValue('C' . $counter, $phoneNumber['email']);
            $sheet->setCellValue('D' . $counter, $phoneNumber['review_assignment']);
            if ($phoneNumber['is_reviewer'] == 1) {
                $sheet->setCellValue('E' . $counter, "External reviewer");

            }

            $idcounter++;
            $counter++;
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'External reviewers.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @Route("/{id}/allaccepted", name="allaccepted", methods={"GET","POST"})
     */
    public function allaccepted(Request $request, CallForProposal $call, PaginatorInterface $paginator) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $entityManager = $this->getDoctrine()->getManager();
        $filterform = $this->createFormBuilder()->add("status", ChoiceType::class, [
            "multiple" => true,
            "required" => true,
            "expanded" => true,
            "choices" => [
                "Accepted" => Constants::SUBMISSION_STATUS_ACCEPTED,
                "Accepted with minor revision" => Constants::SUBMISSION_STATUS_ACCEPTED_WITH_MINOR_REVISION,
                "Accepted with major revision" => Constants::SUBMISSION_STATUS_ACCEPTED_WITH_MAJOR_REVISION,
                "Decline" => Constants::SUBMISSION_STATUS_DECLINED,
            ],
        ])->getForm();
        $status = $request->query->get("status");

        $filterform->handleRequest($request);
        if ($filterform->isSubmitted() && $filterform->isValid()) {

            $submissions = $this->getDoctrine()->getRepository(Submission::class)->getSubmissions(["status" => $filterform->getData()['status']]);
            if ($request->query->get("export")) {
                $spreadsheet = new Spreadsheet();
                /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
                $sheet = $spreadsheet->getActiveSheet();
                $sheet->setCellValue('A1', 'No.');
                $sheet->setCellValue('B1', 'Title');
                $sheet->setCellValue('C1', 'Decision ');
                $sheet->setTitle("Editorial decisions");

                $idcounter = 1;
                $counter = 2;
                foreach ($submissions as $phoneNumber) {
                    $sheet->setCellValue('A' . $counter, $idcounter);
                    $sheet->setCellValue('B' . $counter, $phoneNumber['title']);
                    $sheet->setCellValue('C' . $counter, $phoneNumber['remark']);

                    $counter++;
                    $idcounter++;
                }
                $writer = new Xlsx($spreadsheet);
                $fileName = 'Editorial decisions.xlsx';
                $temp_file = tempnam(sys_get_temp_dir(), $fileName);

                $writer->save($temp_file);

                return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

            }
        } else {
            $submissions = $this->getDoctrine()->getRepository(Submission::class)->getSubmissions();

        }

        $Allsubmissions = $paginator->paginate(
            // Doctrine Query, not results
            $submissions,
            // Define the page parameter
            $request->query->getInt('page', 1),
            // Items per page
            25, array('wrap-queries' => true)
        );
        $info = 'All Accepted';
        ################################
        return $this->render('dashboard/submissions.html.twig', [
            'submissions' => $Allsubmissions,
            'info' => $info,
            'call' => $call,
            'filterform' => $filterform->createView(),
        ]);

    }

    /**
     * @Route("/{id}/theme", name="allbythemeex", methods={"GET","POST"})
     */
    public function allbythemeex(CallForProposal $call) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $calls = $em->getRepository(CallForProposal::class)->find($call);
        //  $submissions = $call->getSubmissions();
        $themes = $calls->getThematicArea();
        $spreadsheet = new Spreadsheet();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */

        $submissions = $em->getRepository(ThematicArea::class)->submissionByThemeCall($call);
        // dd($submissions);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Thematic area.');
        $sheet->setCellValue('C1', 'Title');
        $sheet->setCellValue('D1', 'Funding Scheme');
        $sheet->setCellValue('E1', 'College');
        $sheet->setCellValue('F1', 'PI');
        $sheet->setCellValue('G1', 'PI\'s Affiliation');
        $sheet->setTitle("Researchs by Thematic areas ");
        $counter = 2; 
        foreach ($submissions  as $theme  ) {
            $sheet->setCellValue('A' . $counter, $counter);
            $sheet->setCellValue('B' . $counter, $theme->getName());
            $counter2 = 2;
            
            $title[]=$theme;
            ######################## 
            foreach ($theme->getSubmissions(['callForProposal'=>$call]) as $CoAuthors) {
                $sheet->setCellValue('C' . $counter, $CoAuthors->getTitle());
                $sheet->setCellValue('D' . $counter, $CoAuthors->getFundingScheme());
                $sheet->setCellValue('E' . $counter, $CoAuthors->getCollege());
                $sheet->setCellValue('F' . $counter, $CoAuthors->getAuthor());
                if( $CoAuthors->getAuthor()->getUserInfo()->getCollege() !==''){
                $dep=  ' Department of'. $CoAuthors->getAuthor()->getUserInfo()->getDepartment();
                }
                else{
                    $dep='';
                } 
                $sheet->setCellValue('G' . $counter, $CoAuthors->getAuthor()->getUserInfo()->getCollege().$dep);
                //  $counter++;
                $counter2++; 
                $counter++;
                $counter2++;
            }

############################
            // $counter++;
        }
        // dd($submissions );
        $writer = new Xlsx($spreadsheet);
        $date= $call->getPostDate()->format('Y-m-d');
        $fileName = 'All submissions catagorized by theme of this call from '.$date.'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }
    /**
     * @Route("/{id}/collegesubs", name="collegesubs", methods={"GET","POST"})
     */
    public function allbyCollege(CallForProposal $call) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        $calls = $em->getRepository(CallForProposal::class)->find($call);
        //  $submissions = $call->getSubmissions();
        $themes = $calls->getThematicArea();
        $spreadsheet = new Spreadsheet();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */

        // $submissions = $em->getRepository(ThematicArea::class)->submissionByThemeCall($call);
        $submissions = $em->getRepository(College::class)->findAll();
        // dd($submissions);
        // dd($submissions);
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'College');
        $sheet->setCellValue('C1', 'Title');
        $sheet->setCellValue('D1', 'Thematic area');
        $sheet->setCellValue('E1', 'Funding Scheme');
        $sheet->setCellValue('F1', 'PI');
        $sheet->setCellValue('G1', 'PI\'s Affiliation');
        $sheet->setTitle("Researchs by college ");
        $counter = 2; 
        foreach ($submissions  as $college  ) {
            $sheet->setCellValue('A' . $counter, $counter);
            $sheet->setCellValue('B' . $counter, $college->getName());
            $counter2 = 2;
            
            // $title[]=$college;
            ######################## 
            foreach ($college->getCollegeSubmissions(['callForProposal'=>$call]) as $CoAuthors) {
                // dd($CoAuthors);
                $sheet->setCellValue('C' . $counter, $CoAuthors->getTitle());
                $sheet->setCellValue('D' . $counter, $CoAuthors->getThematicArea());
                $sheet->setCellValue('E' . $counter, $CoAuthors->getFundingScheme());
                $sheet->setCellValue('F' . $counter, $CoAuthors->getAuthor());
                if( $CoAuthors->getAuthor()->getUserInfo()->getCollege() !==''){
                $dep=  ' Department of'. $CoAuthors->getAuthor()->getUserInfo()->getDepartment();
                }
                else{
                    $dep='';
                } 
                $sheet->setCellValue('G' . $counter, $CoAuthors->getAuthor()->getUserInfo()->getCollege().$dep);
                //  $counter++;
                $counter2++; 
                $counter++;
                $counter2++;
            }

############################
            // $counter++;
        }
        // dd($submissions );
        $writer = new Xlsx($spreadsheet);
        $date= $call->getPostDate()->format('Y-m-d');
        $fileName = 'All submissions catagorized by college of this call from '.$date.'.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    function myfunction($value,$key)
{
echo "The key $key has the value $value<br>";
} 
    /**
     * @Route("/{id}/rev-result", name="review_result", methods={"GET","POST"})
     */

    public function results(CallForProposal $call) {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        $em = $this->getDoctrine()->getManager();
        //  $submissions = $em->getRepository(Submission::class)->findAll();

        $submissions = $em->getRepository(Submission::class)->submissionByCall($call);

        $spreadsheet = new Spreadsheet();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */

        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Title');
        $sheet->setCellValue('C1', 'Review decision');
        $sheet->setCellValue('D1', 'Reviewer');
        $sheet->setTitle("Research review result ");
        //  dd($submissions);
        $counter = 2;
        foreach ($submissions as $phoneNumber) {
            $sheet->setCellValue('A' . $counter, $counter);
            $sheet->setCellValue('B' . $counter, $phoneNumber->getTitle());
            $counter2 = 2;
            ########################
            // $sheet->setCellValue('C' . $counter, $phoneNumber->getAuthor()->getUserInfo());
            foreach ($phoneNumber->getReviews() as $CoAuthors) {

                //  $sheet->setCellValue('C' . $counter, $CoAuthors->getReviewedBy()->getIsReviewer());

                if ($CoAuthors->getRemark() == 1) {

                    $sheet->setCellValue('C' . $counter, "Declined");

                } elseif ($CoAuthors->getRemark() == 2) {
                    $sheet->setCellValue('C' . $counter, "Accepted with major revision");

                } elseif ($CoAuthors->getRemark() == 3) {
                    $sheet->setCellValue('C' . $counter, "Accepted with minor revision");

                } elseif ($CoAuthors->getRemark() == 4) {
                    $sheet->setCellValue('C' . $counter, "Accepted");

                }

                if ($CoAuthors->getReviewedBy()->getIsReviewer() == NULL) {
                    $sheet->setCellValue('D' . $counter, "Internal");

                } elseif ($CoAuthors->getReviewedBy()->getIsReviewer() == 1) {
                    $sheet->setCellValue('D' . $counter, "External");

                }
                $counter++;
                $counter2++;
            }

############################
            $counter++;
        }
        $counter = 2;
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Review result of call from ' . $call->getCollege() . ' announced  on ' . $call->getPostDate()->format('Y-m-d') . '.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);
        $writer->save($temp_file);
        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    public function exportall($query) {

        $spreadsheet = new Spreadsheet();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Full name');
        $sheet->setCellValue('C1', 'Email ');
        $sheet->setCellValue('D1', 'Number of assignments');
        $sheet->setCellValue('E1', 'Staff Membership');
        $sheet->setTitle("Internal reviewers");

        $idcounter = 1;
        $counter = 2;
        foreach ($$query as $phoneNumber) {
            $sheet->setCellValue('A' . $counter, $idcounter);
            $sheet->setCellValue('B' . $counter, $phoneNumber['first_name'] . $phoneNumber['midle_name'] . $phoneNumber['last_name']);
            $sheet->setCellValue('C' . $counter, $phoneNumber['email']);
            $sheet->setCellValue('D' . $counter, $phoneNumber['review_assignment']);
            if ($phoneNumber['is_reviewer'] == NULL) {

                $sheet->setCellValue('E' . $counter, "Internal reviewer");

            }
            $counter++;
            $idcounter++;
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Internal reviewers.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }

    /**
     * @Route("/{id}/internal-rev", name="internal_rev", methods={"GET","POST"})
     */
    public function internalreviewers(CallForProposal $call): Response {
        $this->denyAccessUnlessGranted('assn_clg_cntr');

        $entityManager = $this->getDoctrine()->getManager();
        #######################
        $query2 = $entityManager->createQuery(
            'SELECT  u.email , u.id, pi.last_name , pi.first_name, pi.midle_name,  pi.image, u.is_reviewer,   count(b.id) as subs,  count(u.id) as review_assignment
            FROM App:ReviewAssignment s
            JOIN s.reviewer u
            JOIN u.userInfo pi
            JOIN s.submission b
            JOIN b.callForProposal c
            where  u.is_reviewer is NULL and c.id =:id    GROUP BY u.id ORDER BY  pi.first_name
        ')
            ->setParameter('id', $call);
        $recepientextrnal = $query2->getResult();
        ########################

        //   dd($recepientextrnal);
        $spreadsheet = new Spreadsheet();
        /* @var $sheet \PhpOffice\PhpSpreadsheet\Writer\Xlsx\Worksheet */
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'No.');
        $sheet->setCellValue('B1', 'Full name');
        $sheet->setCellValue('C1', 'Email ');
        $sheet->setCellValue('D1', 'Number of assignments');
        $sheet->setCellValue('E1', 'Staff Membership');
        $sheet->setTitle("Internal reviewers");

        $idcounter = 1;
        $counter = 2;
        foreach ($recepientextrnal as $phoneNumber) {
            $sheet->setCellValue('A' . $counter, $idcounter);
            $sheet->setCellValue('B' . $counter, $phoneNumber['first_name'] . $phoneNumber['midle_name'] . $phoneNumber['last_name']);
            $sheet->setCellValue('C' . $counter, $phoneNumber['email']);
            $sheet->setCellValue('D' . $counter, $phoneNumber['review_assignment']);
            if ($phoneNumber['is_reviewer'] == NULL) {

                $sheet->setCellValue('E' . $counter, "Internal reviewer");

            }
            $counter++;
            $idcounter++;
        }
        $writer = new Xlsx($spreadsheet);
        $fileName = 'Internal reviewers.xlsx';
        $temp_file = tempnam(sys_get_temp_dir(), $fileName);

        $writer->save($temp_file);

        return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);

    }


/**
 * @Route("/{uidentifier}/winners", name="call_export_winner", methods={"GET"})
 */
public function winners(CallForProposal $callForProposal, SubmissionRepository $submissionRepository): Response {

    $results = $submissionRepository->filterApproved($callForProposal, true);

    $spreadsheet = new Spreadsheet();
    $no=1;

    $sheet = $spreadsheet->getActiveSheet();
    $sheet->setCellValue('A1', '#');
    $sheet->setCellValue('B1', 'Title');
    $sheet->setCellValue('C1', 'Funding Scheme');
    $sheet->setCellValue('D1', 'College');
    $sheet->setCellValue('E1', 'Thematic Area');
    $sheet->setCellValue('F1', 'PI');
    $sheet->setCellValue('G1', 'Email');
    $sheet->setCellValue('H1', 'Co-PI Name');
    $sheet->setCellValue('I1', 'Co-PI Email');
    $sheet->setTitle("Winner's list");
    $counter = 2;
    foreach ($results as $submission) {
        $sheet->setCellValue('A' . $counter, $no);
        $sheet->setCellValue('B' . $counter, $submission->getTitle());
        $sheet->setCellValue('C' . $counter, $submission->getFundingScheme());
        $sheet->setCellValue('D' . $counter, $submission->getCollege());
        $sheet->setCellValue('E' . $counter, $submission->getThematicArea());
        ########################
        $sheet->setCellValue('F' . $counter, $submission->getAuthor()->getUserInfo());
        $sheet->setCellValue('G' . $counter, $submission->getAuthor()->getEmail());
        // $counter2 = 2;
        // foreach ($submission->getCoAuthors() as $CoAuthors) {
        //     $sheet->setCellValue('H' . $counter, $CoAuthors->getResearcher()->getUserInfo());
        //     $sheet->setCellValue('I' . $counter, $CoAuthors->getResearcher()->getEmail());
           
        //     $counter++;
        //     $counter2++;
        // }
        $counter++;
        $no++;
    }
    $writer = new Xlsx($spreadsheet);
    $date= $callForProposal->getPostDate()->format('Y-m-d');

    $fileName = 'Winners list of '.$date.' call.xlsx';
    $temp_file = tempnam(sys_get_temp_dir(), $fileName);
    $writer->save($temp_file);
    return $this->file($temp_file, $fileName, ResponseHeaderBag::DISPOSITION_INLINE);
}
}
