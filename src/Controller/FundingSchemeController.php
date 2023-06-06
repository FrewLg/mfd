<?php

namespace App\Controller;

use App\Entity\FundingScheme;
use App\Form\FundingSchemeType;
use App\Repository\FundingSchemeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/funding-scheme')]
class FundingSchemeController extends AbstractController
{
    #[Route('/', name: 'app_funding_scheme_index', methods: ['GET'])]
    public function index(FundingSchemeRepository $fundingSchemeRepository): Response
    {
        return $this->render('funding_scheme/index.html.twig', [
            'funding_schemes' => $fundingSchemeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_funding_scheme_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FundingSchemeRepository $fundingSchemeRepository): Response
    {
        $fundingScheme = new FundingScheme();
        $form = $this->createForm(FundingSchemeType::class, $fundingScheme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fundingSchemeRepository->add($fundingScheme);
            return $this->redirectToRoute('app_funding_scheme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('funding_scheme/new.html.twig', [
            'funding_scheme' => $fundingScheme,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_funding_scheme_show', methods: ['GET'])]
    public function show(FundingScheme $fundingScheme): Response
    {
        return $this->render('funding_scheme/show.html.twig', [
            'funding_scheme' => $fundingScheme,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_funding_scheme_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FundingScheme $fundingScheme, FundingSchemeRepository $fundingSchemeRepository): Response
    {
        $form = $this->createForm(FundingSchemeType::class, $fundingScheme);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $fundingSchemeRepository->add($fundingScheme);
            return $this->redirectToRoute('app_funding_scheme_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('funding_scheme/edit.html.twig', [
            'funding_scheme' => $fundingScheme,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_funding_scheme_delete', methods: ['POST'])]
    public function delete(Request $request, FundingScheme $fundingScheme, FundingSchemeRepository $fundingSchemeRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fundingScheme->getId(), $request->request->get('_token'))) {
            $fundingSchemeRepository->remove($fundingScheme);
        }

        return $this->redirectToRoute('app_funding_scheme_index', [], Response::HTTP_SEE_OTHER);
    }
}
