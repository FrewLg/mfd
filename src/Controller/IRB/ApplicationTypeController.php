<?php

namespace App\Controller\IRB;

use App\Entity\IRB\ApplicationType;
use App\Form\IRB\ApplicationTypeType;
use App\Repository\IRB\ApplicationTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb/setting/application-type')]
class ApplicationTypeController extends AbstractController
{
    #[Route('/', name: 'i_r_b_application_type_index', methods: ['GET'])]
    public function index(ApplicationTypeRepository $applicationTypeRepository): Response
    {
        $this->denyAccessUnlessGranted('mng_irb_app_typ');
        
        return $this->render('irb/application_type/index.html.twig', [
            'application_types' => $applicationTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'i_r_b_application_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $applicationType = new ApplicationType();
        $form = $this->createForm(ApplicationTypeType::class, $applicationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($applicationType);
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_application_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/application_type/new.html.twig', [
            'application_type' => $applicationType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_application_type_show', methods: ['GET'])]
    public function show(ApplicationType $applicationType): Response
    {
        return $this->render('irb/application_type/show.html.twig', [
            'application_type' => $applicationType,
        ]);
    }

    #[Route('/{id}/edit', name: 'i_r_b_application_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, ApplicationType $applicationType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(ApplicationTypeType::class, $applicationType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_application_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/application_type/edit.html.twig', [
            'application_type' => $applicationType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_application_type_delete', methods: ['POST'])]
    public function delete(Request $request, ApplicationType $applicationType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$applicationType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($applicationType);
            $entityManager->flush();
        }

        return $this->redirectToRoute('i_r_b_application_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
