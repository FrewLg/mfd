<?php

namespace App\Controller\CRP;

use App\Entity\CRP\ProjectProgress;
use App\Form\CRP\ProjectProgressType;
use App\Repository\CRP\ProjectProgressRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crp/setting/project-progress')]
class ProjectProgressController extends AbstractController
{
    #[Route('/', name: 'app_c_r_p_project_progress_index', methods: ['GET'])]
    public function index(ProjectProgressRepository $projectProgressRepository): Response
    {
        return $this->render('crp/project_progress/index.html.twig', [
            'project_progresses' => $projectProgressRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_c_r_p_project_progress_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectProgressRepository $projectProgressRepository): Response
    {
        $projectProgress = new ProjectProgress();
        $form = $this->createForm(ProjectProgressType::class, $projectProgress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectProgressRepository->add($projectProgress);
            return $this->redirectToRoute('app_c_r_p_project_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_progress/new.html.twig', [
            'project_progress' => $projectProgress,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_progress_show', methods: ['GET'])]
    public function show(ProjectProgress $projectProgress): Response
    {
        return $this->render('crp/project_progress/show.html.twig', [
            'project_progress' => $projectProgress,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_p_project_progress_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectProgress $projectProgress, ProjectProgressRepository $projectProgressRepository): Response
    {
        $form = $this->createForm(ProjectProgressType::class, $projectProgress);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectProgressRepository->add($projectProgress);
            return $this->redirectToRoute('app_c_r_p_project_progress_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_progress/edit.html.twig', [
            'project_progress' => $projectProgress,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_progress_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectProgress $projectProgress, ProjectProgressRepository $projectProgressRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectProgress->getId(), $request->request->get('_token'))) {
            $projectProgressRepository->remove($projectProgress);
        }

        return $this->redirectToRoute('app_c_r_p_project_progress_index', [], Response::HTTP_SEE_OTHER);
    }
}
