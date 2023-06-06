<?php

namespace App\Controller\IRB;

use App\Entity\IRB\ReviewChecklist;
use App\Form\IRB\ReviewChecklistType;
use App\Repository\IRB\ReviewChecklistRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb/setting/review-checklist')]
class ReviewChecklistController extends AbstractController
{
    #[Route('/', name: 'i_r_b_review_checklist_index', methods: ['GET'])]
    public function index(ReviewChecklistRepository $reviewChecklistRepository): Response
    {
        return $this->render('irb/review_checklist/index.html.twig', [
            'review_checklists' => $reviewChecklistRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'i_r_b_review_checklist_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewChecklist = new ReviewChecklist();
        $form = $this->createForm(ReviewChecklistType::class, $reviewChecklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewChecklist);
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_review_checklist_new', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/review_checklist/new.html.twig', [
            'review_checklist' => $reviewChecklist,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_review_checklist_show', methods: ['GET'])]
    public function show(ReviewChecklist $reviewChecklist): Response
    {
        return $this->render('irb/review_checklist/show.html.twig', [
            'review_checklist' => $reviewChecklist,
        ]);
    }

    #[Route('/{id}/edit', name: 'i_r_b_review_checklist_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewChecklist $reviewChecklist, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewChecklistType::class, $reviewChecklist);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_review_checklist_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/review_checklist/edit.html.twig', [
            'review_checklist' => $reviewChecklist,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_review_checklist_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewChecklist $reviewChecklist, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewChecklist->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reviewChecklist);
            $entityManager->flush();
        }

        return $this->redirectToRoute('i_r_b_review_checklist_index', [], Response::HTTP_SEE_OTHER);
    }
}
