<?php

namespace App\Controller\CRP;

use App\Entity\CRP\ProjectAttachment;
use App\Entity\CRP\CollaborativeResearchProject;
use App\Form\CRP\ProjectAttachmentType;
use App\Repository\CRP\ProjectAttachmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crp/pattachment')]
class ProjectAttachmentController extends AbstractController
{
    #[Route('/', name: 'app_c_r_p_project_attachment_index', methods: ['GET'])]
    public function index(ProjectAttachmentRepository $projectAttachmentRepository): Response
    {
        return $this->render('crp/project_attachment/index.html.twig', [
            'project_attachments' => $projectAttachmentRepository->findAll(),
        ]);
    }

    #[Route('/attach', name: 'app_c_r_p_project_attachment_new', methods: ['GET', 'POST'])]
    public function new(Request $request,   ProjectAttachmentRepository $projectAttachmentRepository): Response
    {
        $projectAttachment = new ProjectAttachment();
        $form = $this->createForm(ProjectAttachmentType::class, $projectAttachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // $projectAttachment->setProject(2);
            $projectAttachmentRepository->add($projectAttachment);
            return $this->redirectToRoute('app_c_r_p_project_attachment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_attachment/new.html.twig', [
            'project_attachment' => $projectAttachment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_attachment_show', methods: ['GET'])]
    public function show(ProjectAttachment $projectAttachment): Response
    {
        return $this->render('crp/project_attachment/show.html.twig', [
            'project_attachment' => $projectAttachment,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_p_project_attachment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectAttachment $projectAttachment, ProjectAttachmentRepository $projectAttachmentRepository): Response
    {
        $form = $this->createForm(ProjectAttachmentType::class, $projectAttachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectAttachmentRepository->add($projectAttachment);
            return $this->redirectToRoute('app_c_r_p_project_attachment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/project_attachment/edit.html.twig', [
            'project_attachment' => $projectAttachment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_project_attachment_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectAttachment $projectAttachment, ProjectAttachmentRepository $projectAttachmentRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectAttachment->getId(), $request->request->get('_token'))) {
            $projectAttachmentRepository->remove($projectAttachment);
        }

        return $this->redirectToRoute('app_c_r_p_project_attachment_index', [], Response::HTTP_SEE_OTHER);
    }
}
