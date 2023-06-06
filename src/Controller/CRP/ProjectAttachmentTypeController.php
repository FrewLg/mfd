<?php

namespace App\Controller\CRP;

use App\Entity\CRP\ProjectAttachmentType;
use App\Form\CRP\ProjectAttachmentTypeType;
use App\Repository\CRP\ProjectAttachmentTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crp/attachment-type')]
class ProjectAttachmentTypeController extends AbstractController
{
    #[Route('/', name: 'app_c_r_p_project_attachment_type_index', methods: ['GET'])]
    public function index(ProjectAttachmentTypeRepository $projectAttachmentTypeRepository): Response
    {
        return $this->render('crp/project_attachment_type/index.html.twig', [
            'project_attachment_types' => $projectAttachmentTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_c_r_p_project_attachment_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, ProjectAttachmentTypeRepository $projectAttachmentTypeRepository): Response
    {
        $projectAttachmentType = new ProjectAttachmentType();
        $form = $this->createForm(ProjectAttachmentTypeType::class, $projectAttachmentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectAttachmentTypeRepository->add($projectAttachmentType);
            return $this->redirectToRoute('app_c_r_p_project_attachment_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_attachment_type/new.html.twig', [
            'project_attachment_type' => $projectAttachmentType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_attachment_type_show', methods: ['GET'])]
    public function show(ProjectAttachmentType $projectAttachmentType): Response
    {
        return $this->render('crp/project_attachment_type/show.html.twig', [
            'project_attachment_type' => $projectAttachmentType,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_p_project_attachment_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectAttachmentType $projectAttachmentType, ProjectAttachmentTypeRepository $projectAttachmentTypeRepository): Response
    {
        $form = $this->createForm(ProjectAttachmentTypeType::class, $projectAttachmentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectAttachmentTypeRepository->add($projectAttachmentType);
            return $this->redirectToRoute('app_c_r_p_project_attachment_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_attachment_type/edit.html.twig', [
            'project_attachment_type' => $projectAttachmentType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_attachment_type_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectAttachmentType $projectAttachmentType, ProjectAttachmentTypeRepository $projectAttachmentTypeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectAttachmentType->getId(), $request->request->get('_token'))) {
            $projectAttachmentTypeRepository->remove($projectAttachmentType);
        }

        return $this->redirectToRoute('app_c_r_p_project_attachment_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
