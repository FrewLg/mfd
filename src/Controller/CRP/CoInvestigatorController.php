<?php

namespace App\Controller\CRP;

use App\Entity\CRP\CoInvestigator;
use App\Form\CRP\CoInvestigatorType;
use App\Repository\CRP\CoInvestigatorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crp/setting/copi')]
class CoInvestigatorController extends AbstractController
{
    #[Route('/', name: 'app_c_r_p_co_investigator_index', methods: ['GET'])]
    public function index(CoInvestigatorRepository $coInvestigatorRepository): Response
    {
        return $this->render('crp/co_investigator/index.html.twig', [
            'co_investigators' => $coInvestigatorRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_c_r_p_co_investigator_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CoInvestigatorRepository $coInvestigatorRepository): Response
    {
        $coInvestigator = new CoInvestigator();
        $form = $this->createForm(CoInvestigatorType::class, $coInvestigator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coInvestigatorRepository->add($coInvestigator);
            return $this->redirectToRoute('app_c_r_p_co_investigator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/co_investigator/new.html.twig', [
            'co_investigator' => $coInvestigator,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_co_investigator_show', methods: ['GET'])]
    public function show(CoInvestigator $coInvestigator): Response
    {
        return $this->render('crp/co_investigator/show.html.twig', [
            'co_investigator' => $coInvestigator,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_p_co_investigator_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, CoInvestigator $coInvestigator, CoInvestigatorRepository $coInvestigatorRepository): Response
    {
        $form = $this->createForm(CoInvestigatorType::class, $coInvestigator);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $coInvestigatorRepository->add($coInvestigator);
            return $this->redirectToRoute('app_c_r_p_co_investigator_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/co_investigator/edit.html.twig', [
            'co_investigator' => $coInvestigator,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_co_investigator_delete', methods: ['POST'])]
    public function delete(Request $request, CoInvestigator $coInvestigator, CoInvestigatorRepository $coInvestigatorRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$coInvestigator->getId(), $request->request->get('_token'))) {
            $coInvestigatorRepository->remove($coInvestigator);
        }

        return $this->redirectToRoute('app_c_r_p_co_investigator_index', [], Response::HTTP_SEE_OTHER);
    }
}
