<?php

namespace App\Controller;

use App\Entity\SubmissionStatus;
use App\Form\SubmissionStatusType;
use App\Repository\SubmissionStatusRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/submission-status')]
class SubmissionStatusController extends AbstractController
{
    #[Route('/', name: 'app_submission_status_index', methods: ['GET'])]
    public function index(SubmissionStatusRepository $submissionStatusRepository): Response
    {
        return $this->render('submission_status/index.html.twig', [
            'submission_statuses' => $submissionStatusRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_submission_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $submissionStatus = new SubmissionStatus();
        $form = $this->createForm(SubmissionStatusType::class, $submissionStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($submissionStatus);
            $entityManager->flush();

            return $this->redirectToRoute('app_submission_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('submission_status/new.html.twig', [
            'submission_status' => $submissionStatus,
            'form' => $form->createView(),
        ]);
    }
    #[Route('/newtwo', name: 'app_submission_status_test', methods: ['GET', 'POST'])]
    public function autosave(Request $request, EntityManagerInterface $entityManager): Response
    {
        $submissionStatus = new SubmissionStatus();
        $form = $this->createForm(SubmissionStatusType::class, $submissionStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($submissionStatus);
            $entityManager->flush();

            return $this->redirectToRoute('app_submission_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('submission_status/new.html.twig', [
            'submission_status' => $submissionStatus,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_submission_status_show', methods: ['GET'])]
    public function show(SubmissionStatus $submissionStatus): Response
    {
        return $this->render('submission_status/show.html.twig', [
            'submission_status' => $submissionStatus,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_submission_status_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, SubmissionStatus $submissionStatus, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(SubmissionStatusType::class, $submissionStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('app_submission_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('submission_status/edit.html.twig', [
            'submission_status' => $submissionStatus,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_submission_status_delete', methods: ['POST'])]
    public function delete(Request $request, SubmissionStatus $submissionStatus, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$submissionStatus->getId(), $request->request->get('_token'))) {
            $entityManager->remove($submissionStatus);
            $entityManager->flush();
        }

        return $this->redirectToRoute('app_submission_status_index', [], Response::HTTP_SEE_OTHER);
    }
}
