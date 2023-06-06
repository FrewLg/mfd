<?php

namespace App\Controller\IRB;

use App\Entity\IRB\ReviewChecklistGroup;
use App\Form\IRB\ReviewChecklistGroupType;
use App\Repository\IRB\ReviewChecklistGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb/setting/review-checklist-group')]
class ReviewChecklistGroupController extends AbstractController
{
    #[Route('/', name: 'i_r_b_review_checklist_group_index', methods: ['GET'])]
    public function index(ReviewChecklistGroupRepository $reviewChecklistGroupRepository): Response
    {
        return $this->render('irb/review_checklist_group/index.html.twig', [
            'review_checklist_groups' => $reviewChecklistGroupRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'i_r_b_review_checklist_group_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $reviewChecklistGroup = new ReviewChecklistGroup();
        $form = $this->createForm(ReviewChecklistGroupType::class, $reviewChecklistGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($reviewChecklistGroup);
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_review_checklist_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/review_checklist_group/new.html.twig', [
            'review_checklist_group' => $reviewChecklistGroup,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_review_checklist_group_show', methods: ['GET'])]
    public function show(ReviewChecklistGroup $reviewChecklistGroup): Response
    {
        return $this->render('irb/review_checklist_group/show.html.twig', [
            'review_checklist_group' => $reviewChecklistGroup,
        ]);
    }

    #[Route('/{id}/edit', name: 'i_r_b_review_checklist_group_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ReviewChecklistGroup $reviewChecklistGroup, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ReviewChecklistGroupType::class, $reviewChecklistGroup);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_review_checklist_group_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/review_checklist_group/edit.html.twig', [
            'review_checklist_group' => $reviewChecklistGroup,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_review_checklist_group_delete', methods: ['POST'])]
    public function delete(Request $request, ReviewChecklistGroup $reviewChecklistGroup, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$reviewChecklistGroup->getId(), $request->request->get('_token'))) {
            $entityManager->remove($reviewChecklistGroup);
            $entityManager->flush();
        }

        return $this->redirectToRoute('i_r_b_review_checklist_group_index', [], Response::HTTP_SEE_OTHER);
    }
}
