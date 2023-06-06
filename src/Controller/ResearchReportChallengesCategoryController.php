<?php

namespace App\Controller;

use App\Entity\ResearchReportChallengesCategory;
use App\Form\ResearchReportChallengesCategoryType;
use App\Repository\ResearchReportChallengesCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/research-report-challenges-category')]
class ResearchReportChallengesCategoryController extends AbstractController
{
    #[Route('/', name: 'research_report_challenges_category_index', methods: ['GET'])]
    public function index(ResearchReportChallengesCategoryRepository $researchReportChallengesCategoryRepository): Response
    {
        return $this->render('research_report_challenges_category/index.html.twig', [
            'research_report_challenges_categories' => $researchReportChallengesCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'research_report_challenges_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $researchReportChallengesCategory = new ResearchReportChallengesCategory();
        $form = $this->createForm(ResearchReportChallengesCategoryType::class, $researchReportChallengesCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($researchReportChallengesCategory);
            $entityManager->flush();

            return $this->redirectToRoute('research_report_challenges_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('research_report_challenges_category/new.html.twig', [
            'research_report_challenges_category' => $researchReportChallengesCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'research_report_challenges_category_show', methods: ['GET'])]
    public function show(ResearchReportChallengesCategory $researchReportChallengesCategory): Response
    {
        return $this->render('research_report_challenges_category/show.html.twig', [
            'research_report_challenges_category' => $researchReportChallengesCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'research_report_challenges_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ResearchReportChallengesCategory $researchReportChallengesCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResearchReportChallengesCategoryType::class, $researchReportChallengesCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('research_report_challenges_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('research_report_challenges_category/edit.html.twig', [
            'research_report_challenges_category' => $researchReportChallengesCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'research_report_challenges_category_delete', methods: ['POST'])]
    public function delete(Request $request, ResearchReportChallengesCategory $researchReportChallengesCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$researchReportChallengesCategory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($researchReportChallengesCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('research_report_challenges_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
