<?php

namespace App\Controller\IRB;

use App\Entity\IRB\ResearchSubject;
use App\Form\IRB\ResearchSubjectType;
use App\Repository\IRB\ResearchSubjectRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb/setting/research-subject')]
class ResearchSubjectController extends AbstractController
{
    #[Route('/', name: 'i_r_b_research_subject_index', methods: ['GET'])]
    public function index(ResearchSubjectRepository $researchSubjectRepository): Response
    {
        $this->denyAccessUnlessGranted('mng_irb_rsub');
        return $this->render('irb/research_subject/index.html.twig', [
            'research_subjects' => $researchSubjectRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'i_r_b_research_subject_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $researchSubject = new ResearchSubject();
        $form = $this->createForm(ResearchSubjectType::class, $researchSubject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($researchSubject);
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_research_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/research_subject/new.html.twig', [
            'research_subject' => $researchSubject,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_research_subject_show', methods: ['GET'])]
    public function show(ResearchSubject $researchSubject): Response
    {
        return $this->render('irb/research_subject/show.html.twig', [
            'research_subject' => $researchSubject,
        ]);
    }

    #[Route('/{id}/edit', name: 'i_r_b_research_subject_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ResearchSubject $researchSubject, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ResearchSubjectType::class, $researchSubject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_research_subject_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/research_subject/edit.html.twig', [
            'research_subject' => $researchSubject,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_research_subject_delete', methods: ['POST'])]
    public function delete(Request $request, ResearchSubject $researchSubject, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$researchSubject->getId(), $request->request->get('_token'))) {
            $entityManager->remove($researchSubject);
            $entityManager->flush();
        }

        return $this->redirectToRoute('i_r_b_research_subject_index', [], Response::HTTP_SEE_OTHER);
    }
}
