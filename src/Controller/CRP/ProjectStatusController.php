<?php

namespace App\Controller\CRP;

use App\Entity\CRP\ProjectStatus;
use App\Form\CRP\ProjectStatusType;
use App\Repository\CRP\ProjectStatusRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crp/setting/project-status')]
class ProjectStatusController extends AbstractController
{
    #[Route('/', name: 'app_c_r_p_project_status_index', methods: ['GET'])]
    public function index(ProjectStatusRepository $projectStatusRepository): Response
    {
        return $this->render('crp/project_status/index.html.twig', [
            'project_statuses' => $projectStatusRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_c_r_p_project_status_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectStatusRepository $projectStatusRepository): Response
    {
        $projectStatus = new ProjectStatus();
        $form = $this->createForm(ProjectStatusType::class, $projectStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectStatusRepository->add($projectStatus);
            return $this->redirectToRoute('app_c_r_p_project_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_status/new.html.twig', [
            'project_status' => $projectStatus,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_status_show', methods: ['GET'])]
    public function show(ProjectStatus $projectStatus): Response
    {
        return $this->render('crp/project_status/show.html.twig', [
            'project_status' => $projectStatus,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_p_project_status_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectStatus $projectStatus, ProjectStatusRepository $projectStatusRepository): Response
    {
        $form = $this->createForm(ProjectStatusType::class, $projectStatus);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectStatusRepository->add($projectStatus);
            return $this->redirectToRoute('app_c_r_p_project_status_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_status/edit.html.twig', [
            'project_status' => $projectStatus,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_status_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectStatus $projectStatus, ProjectStatusRepository $projectStatusRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectStatus->getId(), $request->request->get('_token'))) {
            $projectStatusRepository->remove($projectStatus);
        }

        return $this->redirectToRoute('app_c_r_p_project_status_index', [], Response::HTTP_SEE_OTHER);
    }
}
