<?php

namespace App\Controller;

use App\Entity\IRB\IRBReview;
use App\Entity\IRB\IRBReviewAssignment;
use App\Form\IRB\IRBReviewType;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class HomeController extends AbstractController
{
    /**
     * @Route("/home", name="home")
     */
    public function index(TranslatorInterface $translator): Response
    {

        $message = $translator->trans('text.message', [], null, 'am');
        return $this->render('home/index.html.twig', [
            'message' => $message
        ]);
    }
    /**
     * @Route("/external-irb-review", name="external_irb_review")
     */
    public function externalReview(Request $request): Response
    {
        $reviewAssignment=$this->getDoctrine()->getRepository(IRBReviewAssignment::class)->findOneBy(["token"=>$request->query->get('token')]);
        ////Ultimate reviewers page

        $entityManager = $this->getDoctrine()->getManager();
        $submissionOfreviewer = $entityManager->getRepository(IRBReviewAssignment::class)->find($reviewAssignment);
        $submissions = $submissionOfreviewer->getApplication();
        #######################
        if ($reviewAssignment->getClosed() == 1) {
            return $this->redirectToRoute('irb_myassigned');
        }
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
            $reviewfile = $form->get('attachment')->getData();
            if ($reviewfile == "") {
                $this->addFlash(
                    'danger',
                    'Review file  not uploaded!'
                );
            } else {
                $reviewfile = $form->get('attachment')->getData();
                $Areviewfile = md5(uniqid()) . '.' . $reviewfile->guessExtension();
                $reviewfile->move($this->getParameter('irb_uploads'), $Areviewfile);
                $review->setAttachment($Areviewfile);
            }
            ##########
            // $reviewfile2 = $form->get('evaluation_attachment')->getData();
            // // if ($reviewfile2 == "") {
            //     $this->addFlash(
            //         'danger',
            //         'Evaluation  file  not uploaded!'
            //     );
            // } else {
            //     $reviewfile2 = $form->get('evaluation_attachment')->getData();
            //     $Areviewfile2 = md5(uniqid()) . '.' . $reviewfile2->guessExtension();
            //     $reviewfile2->move($this->getParameter('irb_uploads'), $Areviewfile2);
            //     $review->setEvaluationAttachment($Areviewfile2);
            // }
            ###############
            $review->setCreatedAt(new \DateTime());
            $review->setReviewedBy($this->getUser());
            $reviewAssignment->setClosed(1);
            $entityManager->persist($review);
            $entityManager->flush();
            $this->addFlash(
                'success',
                'You have  completed a revision successfully!'
            );
            return $this->redirectToRoute('irb_myassigned', array('id' => $reviewAssignment->getId()));
        }

        $reviews = $entityManager->getRepository(IRBReview::class)->findBy(['application' => $reviewAssignment->getApplication(), 'reviewed_by' => $measareviewer]);

        return $this->render('application/exirb-revise.html.twig', [
            'review_assignment' => $reviewAssignment,
            'review_assignments' => $reviews,
            'submission' => $submissions,
            'form' => $form->createView(),
        ]);
    }
}
