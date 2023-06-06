<?php

namespace App\Controller\IRB;

use App\Entity\IRB\AttachmentType;
use App\Form\IRB\AttachmentTypeType;
use App\Repository\IRB\AttachmentTypeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb/setting/attachment-type')]
class AttachmentTypeController extends AbstractController
{
    #[Route('/', name: 'i_r_b_attachment_type_index', methods: ['GET'])]
    public function index(AttachmentTypeRepository $attachmentTypeRepository): Response
    {
        $this->denyAccessUnlessGranted('mng_irb_atch_typ');
        
        return $this->render('irb/attachment_type/index.html.twig', [
            'attachment_types' => $attachmentTypeRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'i_r_b_attachment_type_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $attachmentType = new AttachmentType();
        $form = $this->createForm(AttachmentTypeType::class, $attachmentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->persist($attachmentType);
            $entityManager->flush();
            $this->addFlash("success","Successfully saved");

            return $this->redirectToRoute('i_r_b_attachment_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/attachment_type/new.html.twig', [
            'attachment_type' => $attachmentType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_attachment_type_show', methods: ['GET'])]
    public function show(AttachmentType $attachmentType): Response
    {
        return $this->render('irb/attachment_type/show.html.twig', [
            'attachment_type' => $attachmentType,
        ]);
    }

    #[Route('/{id}/edit', name: 'i_r_b_attachment_type_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, AttachmentType $attachmentType, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(AttachmentTypeType::class, $attachmentType);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager->flush();
            $this->addFlash("success","Successfully updated");


            return $this->redirectToRoute('i_r_b_attachment_type_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/attachment_type/edit.html.twig', [
            'attachment_type' => $attachmentType,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_attachment_type_delete', methods: ['POST'])]
    public function delete(Request $request, AttachmentType $attachmentType, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete'.$attachmentType->getId(), $request->request->get('_token'))) {
            $entityManager->remove($attachmentType);
            $entityManager->flush();
            $this->addFlash("success","Successfully deleted");

        }

        return $this->redirectToRoute('i_r_b_attachment_type_index', [], Response::HTTP_SEE_OTHER);
    }
}
