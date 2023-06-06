<?php

namespace App\Controller\CRP;

use App\Entity\CRP\ProjectType;
use App\Form\CRP\ProjectTypeType;
use App\Repository\CRP\ProjectTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crp/setting/project-type')]
class ProjectTypeController extends AbstractController
{
    #[Route('/', name: 'app_c_r_p_project_type_index', methods: ['GET'])]
    public function index(ProjectTypeRepository $projectTypeRepository): Response
    {
        return $this->render('crp/project_type/index.html.twig', [
            'project_types' => $projectTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_c_r_p_project_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectTypeRepository $projectTypeRepository): Response
    {
        $projectType = new ProjectType();
        $form = $this->createForm(ProjectTypeType::class, $projectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectTypeRepository->add($projectType);
            return $this->redirectToRoute('app_c_r_p_project_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_type/new.html.twig', [
            'project_type' => $projectType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_type_show', methods: ['GET'])]
    public function show(ProjectType $projectType): Response
    {
        return $this->render('crp/project_type/show.html.twig', [
            'project_type' => $projectType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_p_project_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectType $projectType, ProjectTypeRepository $projectTypeRepository): Response
    {
        $form = $this->createForm(ProjectTypeType::class, $projectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectTypeRepository->add($projectType);
            return $this->redirectToRoute('app_c_r_p_project_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_type/edit.html.twig', [
            'project_type' => $projectType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_type_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectType $projectType, ProjectTypeRepository $projectTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectType->getId(), $request->request->get('_token'))) {
            $projectTypeRepository->remove($projectType);
        }

        return $this->redirectToRoute('app_c_r_p_project_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
