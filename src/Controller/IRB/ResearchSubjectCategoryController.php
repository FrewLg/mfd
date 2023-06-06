<?php

namespace App\Controller\IRB;

use App\Entity\IRB\ResearchSubjectCategory;
use App\Form\IRB\ResearchSubjectCategoryType;
use App\Repository\IRB\ResearchSubjectCategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb/setting/research-subject-category')]
class ResearchSubjectCategoryController extends AbstractController
{
    #[Route('/', name: 'i_r_b_research_subject_category_index', methods: ['GET'])]
    public function index(ResearchSubjectCategoryRepository $researchSubjectCategoryRepository): Response
    {
        $this->denyAccessUnlessGranted('mng_irb_rsub_cat');
        return $this->render('irb/research_subject_category/index.html.twig', [
            'research_subject_categories' => $researchSubjectCategoryRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'i_r_b_research_subject_category_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $researchSubjectCategory = new ResearchSubjectCategory();
        $form = $this->createForm(ResearchSubjectCategoryType::class, $researchSubjectCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($researchSubjectCategory);
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_research_subject_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/research_subject_category/new.html.twig', [
            'research_subject_category' => $researchSubjectCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_research_subject_category_show', methods: ['GET'])]
    public function show(ResearchSubjectCategory $researchSubjectCategory): Response
    {
        return $this->render('irb/research_subject_category/show.html.twig', [
            'research_subject_category' => $researchSubjectCategory,
        ]);
    }

    #[Route('/{id}/edit', name: 'i_r_b_research_subject_category_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ResearchSubjectCategory $researchSubjectCategory, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResearchSubjectCategoryType::class, $researchSubjectCategory);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_research_subject_category_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/research_subject_category/edit.html.twig', [
            'research_subject_category' => $researchSubjectCategory,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_research_subject_category_delete', methods: ['POST'])]
    public function delete(Request $request, ResearchSubjectCategory $researchSubjectCategory, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$researchSubjectCategory->getId(), $request->request->get('_token'))) {
            $entityManager->remove($researchSubjectCategory);
            $entityManager->flush();
        }

        return $this->redirectToRoute('i_r_b_research_subject_category_index', [], Response::HTTP_SEE_OTHER);
    }
}
