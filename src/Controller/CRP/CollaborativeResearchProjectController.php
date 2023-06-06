<?php

namespace App\Controller\CRP;

use App\Entity\CRP\CollaborativeResearchProject;
use App\Entity\CRP\Deliverables;
use App\Entity\CRP\ProjectAttachment;
use App\Form\CRP\CollaborativeResearchProjectType;
use App\Form\CRP\ProjectAttachmentType;
use App\Repository\CRP\CollaborativeResearchProjectRepository;
use App\Repository\CRP\DeliverablesRepository;
use App\Repository\CRP\ProjectAttachmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crp/ju')]
class CollaborativeResearchProjectController extends AbstractController
{
    #[Route('/', name: 'app_c_r_p_collaborative_research_project_index', methods: ['GET'])]
    public function index(CollaborativeResearchProjectRepository $collaborativeResearchProjectRepository): Response
    {
        return $this->render('crp/collaborative_research_project/index.html.twig', [
            'collaborative_research_projects' => $collaborativeResearchProjectRepository->findAll(),
        ]);
    }
    #[Route('/my-projects', name: 'my_colla_projs', methods: ['GET'])]
    public function myprojects(CollaborativeResearchProjectRepository $collaborativeResearchProjectRepository): Response
    {
        return $this->render('crp/collaborative_research_project/my-projects.html.twig', [
            'collaborative_research_projects' => $collaborativeResearchProjectRepository->findBy(['PrincipalInvestigator'=>$this->getUser()]),
        ]);
    }

    #[Route('/new', name: 'app_c_r_p_collaborative_research_project_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CollaborativeResearchProjectRepository $collaborativeResearchProjectRepository): Response
    {
        $collaborativeResearchProject = new CollaborativeResearchProject();
        $form = $this->createForm(CollaborativeResearchProjectType::class, $collaborativeResearchProject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $collaborativeResearchProjectRepository->add($collaborativeResearchProject);
            return $this->redirectToRoute('app_c_r_p_collaborative_research_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/collaborative_research_project/new.html.twig', [
            'collaborative_research_project' => $collaborativeResearchProject,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/details', name: 'app_c_r_p_collaborative_research_project_show', methods: ['GET','POST'])]
    public function show(Request $request   ,  CollaborativeResearchProject $collaborativeResearchProject): Response
    {

        $entityManager = $this->getDoctrine()->getManager();

        $projectAttachment = new ProjectAttachment();
        $form = $this->createForm(ProjectAttachmentType::class, $projectAttachment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $projectAttachment->setProject($collaborativeResearchProject);
            // dd($collaborativeResearchProject);
            $file3 = $form->get('file')->getData();
            if ($file3 == '' ) {
            }
            else {
                $file3 = $form->get('file')->getData();
                $fileName3 = md5(uniqid()) . '.' . $file3->guessExtension();
                $file3->move($this->getParameter('upload_destination'), $fileName3);
                $projectAttachment->setFile($fileName3);
 
            }
            // $projectAttachmentRepository->add($projectAttachment);
            $entityManager->persist($projectAttachment);
            $entityManager->flush();
        return $this->redirectToRoute('app_c_r_p_collaborative_research_project_show', ['id'=>$collaborativeResearchProject->getId()]);

         }


        return $this->render('crp/collaborative_research_project/show.html.twig', [
            'collaborative_research_project' => $collaborativeResearchProject,
            'attachmentform' => $form->createView(),

        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_p_collaborative_research_project_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CollaborativeResearchProject $collaborativeResearchProject, CollaborativeResearchProjectRepository $collaborativeResearchProjectRepository): Response
    {
        $form = $this->createForm(CollaborativeResearchProjectType::class, $collaborativeResearchProject);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $collaborativeResearchProjectRepository->add($collaborativeResearchProject);
            return $this->redirectToRoute('app_c_r_p_collaborative_research_project_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/collaborative_research_project/edit.html.twig', [
            'collaborative_research_project' => $collaborativeResearchProject,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_collaborative_research_project_delete', methods: ['POST'])]
    public function delete(Request $request, CollaborativeResearchProject $collaborativeResearchProject, CollaborativeResearchProjectRepository $collaborativeResearchProjectRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$collaborativeResearchProject->getId(), $request->request->get('_token'))) {
            $collaborativeResearchProjectRepository->remove($collaborativeResearchProject);
        }

        return $this->redirectToRoute('app_c_r_p_collaborative_research_project_index', [], Response::HTTP_SEE_OTHER);
    }
      /**
     * @Route("/{id}/done", name="toggle_status", methods={"POST"})
     */
    public function done(Request $request, Deliverables $task, DeliverablesRepository $collaborativeResearchProjectRepository): Response
    {
        if ($this->isCsrfTokenValid('toggle'.$task->getId(), $request->request->get('_token'))) {
        $task->setStatus(1); 
        $collaborativeResearchProjectRepository->add($task);

        }

        return $this->redirectToRoute('app_c_r_p_collaborative_research_project_show', ['id'=>$task->getCollaborativeResearchProject()->getId()]);
    }
 


}
