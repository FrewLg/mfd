<?php

namespace App\Controller\CRP;

use App\Entity\CRP\Currency;
use App\Form\CRP\CurrencyType;
use App\Repository\CRP\CurrencyRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crp/setting/currency')]
class CurrencyController extends AbstractController
{
    #[Route('/', name: 'app_c_r_p_currency_index', methods: ['GET'])]
    public function index(CurrencyRepository $currencyRepository): Response
    {
        return $this->render('crp/currency/index.html.twig', [
            'currencies' => $currencyRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_c_r_p_currency_new', methods: ['GET', 'POST'])]
    public function new(Request $request, CurrencyRepository $currencyRepository): Response
    {
        $currency = new Currency();
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currencyRepository->add($currency);
            return $this->redirectToRoute('app_c_r_p_currency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/currency/new.html.twig', [
            'currency' => $currency,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_currency_show', methods: ['GET'])]
    public function show(Currency $currency): Response
    {
        return $this->render('crp/currency/show.html.twig', [
            'currency' => $currency,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_p_currency_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Currency $currency, CurrencyRepository $currencyRepository): Response
    {
        $form = $this->createForm(CurrencyType::class, $currency);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $currencyRepository->add($currency);
            return $this->redirectToRoute('app_c_r_p_currency_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/currency/edit.html.twig', [
            'currency' => $currency,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_currency_delete', methods: ['POST'])]
    public function delete(Request $request, Currency $currency, CurrencyRepository $currencyRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$currency->getId(), $request->request->get('_token'))) {
            $currencyRepository->remove($currency);
        }

        return $this->redirectToRoute('app_c_r_p_currency_index', [], Response::HTTP_SEE_OTHER);
    }
}
