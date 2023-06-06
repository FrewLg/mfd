<?php

namespace App\Controller\IRB;

use App\Entity\IRB\Application;
use App\Entity\IRB\ApplicationFeedback;
use App\Form\IRB\ApplicationFeedbackType;
use App\Repository\ApplicationFeedbackRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
 
/**
 * @Route("/irb/{id}/feedback")
 */

class ApplicationFeedbackController extends AbstractController
{ 
   

      /**
     * @Route("/", name="app_application_feedback_new", methods={"GET","POST"})
     */
     public function new(Request $request, ApplicationFeedbackRepository $applicationFeedbackRepository ,Application $application): Response
    {
        $applicationFeedback = new ApplicationFeedback();
        $feedbackForm = $this->createForm(ApplicationFeedbackType::class, $applicationFeedback);
        $feedbackForm->handleRequest($request);

        if ($feedbackForm->isSubmitted() && $feedbackForm->isValid()) {
            $applicationFeedback-> setApplication($application);
            $applicationFeedback-> setCreatedAt(new \DateTime());
            $applicationFeedback-> setFeedbackFrom($this->getUser());
            $applicationFeedbackRepository->add($applicationFeedback);
         }

        return $this->render('application_feedback/new.html.twig', [
            'application_feedback' => $applicationFeedback,
            'form' => $feedbackForm->createView(),
        ]);
    }

     

    

    // #[Route('/{id}', name: 'app_application_feedback_delete', methods: ['POST'])]
    // public function delete(Request $request, ApplicationFeedback $applicationFeedback, ApplicationFeedbackRepository $applicationFeedbackRepository): Response
    // {
    //     if ($this->isCsrfTokenValid('delete'.$applicationFeedback->getId(), $request->request->get('_token'))) {
    //         $applicationFeedbackRepository->remove($applicationFeedback);
    //     }

    //     return $this->redirectToRoute('app_application_feedback_index', [], Response::HTTP_SEE_OTHER);
    // }
}
