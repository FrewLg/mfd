<?php

namespace App\Controller\IRB;

use App\Entity\IRB\Amendment;
use App\Entity\IRB\Application;
use App\Form\IRB\AmendmentType;
use App\Repository\IRB\AmendmentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb/amendment')]
class AmendmentController extends AbstractController
{
    #[Route('/', name: 'i_r_b_amendment_index', methods: ['GET'])]
    public function index(AmendmentRepository $amendmentRepository): Response
    {
        return $this->render('irb/amendment/index.html.twig', [
            'amendments' => $amendmentRepository->findAll(),
        ]);
    }

    #[Route('/new/{id}', name: 'i_r_b_amendment_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager,Application $application): Response
    {
        $amendment = new Amendment();
        $amendment->setApplication($application);
        $form = $this->createForm(AmendmentType::class, $amendment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($amendment);
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_amendment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/amendment/new.html.twig', [
            'amendment' => $amendment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_amendment_show', methods: ['GET'])]
    public function show(Amendment $amendment): Response
    {
        return $this->render('irb/amendment/show.html.twig', [
            'amendment' => $amendment,
        ]);
    }

    #[Route('/{id}/edit', name: 'i_r_b_amendment_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Amendment $amendment, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AmendmentType::class, $amendment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_amendment_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/amendment/edit.html.twig', [
            'amendment' => $amendment,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_amendment_delete', methods: ['POST'])]
    public function delete(Request $request, Amendment $amendment, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$amendment->getId(), $request->request->get('_token'))) {
            $entityManager->remove($amendment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('i_r_b_amendment_index', [], Response::HTTP_SEE_OTHER);
    }
}
