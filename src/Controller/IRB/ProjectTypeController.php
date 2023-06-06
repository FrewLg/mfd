<?php

namespace App\Controller\IRB;

use App\Entity\IRB\ProjectType;
use App\Form\IRB\ProjectTypeType;
use App\Repository\IRB\ProjectTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb/setting/project-type')]
class ProjectTypeController extends AbstractController
{
    #[Route('/', name: 'i_r_b_project_type_index', methods: ['GET'])]
    public function index(ProjectTypeRepository $projectTypeRepository): Response
    {
        $this->denyAccessUnlessGranted('mng_irb_pr_typ');
        return $this->render('irb/project_type/index.html.twig', [
            'project_types' => $projectTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'i_r_b_project_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $projectType = new ProjectType();
        $form = $this->createForm(ProjectTypeType::class, $projectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($projectType);
            $entityManager->flush();
            $this->addFlash("success","Successfully saved");


            return $this->redirectToRoute('i_r_b_project_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/project_type/new.html.twig', [
            'project_type' => $projectType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_project_type_show', methods: ['GET'])]
    public function show(ProjectType $projectType): Response
    {
        return $this->render('irb/project_type/show.html.twig', [
            'project_type' => $projectType,
        ]);
    }

    #[Route('/{id}/edit', name: 'i_r_b_project_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ProjectType $projectType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ProjectTypeType::class, $projectType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash("success","Successfully updated");

            return $this->redirectToRoute('i_r_b_project_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/project_type/edit.html.twig', [
            'project_type' => $projectType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_project_type_delete', methods: ['POST'])]
    public function delete(Request $request, ProjectType $projectType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$projectType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($projectType);
            $entityManager->flush();
            $this->addFlash("success","Successfully deleted");

        }

        return $this->redirectToRoute('i_r_b_project_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
