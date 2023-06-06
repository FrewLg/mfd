<?php

namespace App\Controller;

use App\Entity\CallForProposal;
use App\Entity\TemplateAndForm;
use App\Form\TemplateAndFormType;
use App\Repository\TemplateAndFormRepository;
use App\Service\FileUploader;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/template-and-form')]
class TemplateAndFormController extends AbstractController
{
    #[Route('{id}/call', name: 'template_and_form_index', methods: ['GET', "POST"])]
    public function index(CallForProposal $callForProposal, FileUploader $fileUploader, PaginatorInterface $paginator, Request $request, TemplateAndFormRepository $templateAndFormRepository): Response
    {
        $templateAndForm = new TemplateAndForm();
        $form = $this->createForm(TemplateAndFormType::class, $templateAndForm);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();

            $templateFile = $form->get('fileName')->getData();
            if ($templateFile) {
                $templateFileName = $fileUploader->upload($templateFile);
                $templateAndForm->setFileName($templateFileName);
            }

            $templateAndForm->setCallFor($callForProposal);
            $templateAndForm->setUploadedBy($this->getUser());
            $templateAndForm->setCollege($this->getUser()->getUserInfo()->getCollege());
            $entityManager->persist($templateAndForm);
            $entityManager->flush();
            $this->addFlash("success", "Registered successfully!!");

            return $this->redirectToRoute('template_and_form_index', ["id" => $callForProposal->getId()], Response::HTTP_SEE_OTHER);
        }
        $queryBulder = $templateAndFormRepository->getData(["call"=>$callForProposal,"search" => $request->query->get('search')]);
        $template_and_forms = $paginator->paginate(
            $queryBulder,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('template_and_form/index.html.twig', [
            'template_and_forms' => $template_and_forms,
            'call' => $callForProposal,
            'template_and_form' => $templateAndForm,
            'form' => $form->createView(),
        ]);
    }

  

    #[Route('/{id}', name: 'template_and_form_delete', methods: ['POST'])]
    public function delete(Request $request, TemplateAndForm $templateAndForm, EntityManagerInterface $entityManager): Response
    {
        $call=$templateAndForm->getCallFor();
        if ($this->isCsrfTokenValid('delete' . $templateAndForm->getId(), $request->request->get('_token'))) {
           
            $entityManager->remove($templateAndForm);
            $entityManager->flush();
        }

        return $this->redirectToRoute('template_and_form_index', ["id"=>$call->getId()], Response::HTTP_SEE_OTHER);
    }
}
