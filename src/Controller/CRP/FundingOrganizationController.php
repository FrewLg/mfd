<?php

namespace App\Controller\CRP;

use App\Entity\CRP\FundingOrganization;
use App\Form\CRP\FundingOrganizationType;
use App\Repository\CRP\FundingOrganizationRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/crp/setting/funding-organization')]
class FundingOrganizationController extends AbstractController
{
    #[Route('/', name: 'app_c_r_p_funding_organization_index', methods: ['GET'])]
    public function index(FundingOrganizationRepository $fundingOrganizationRepository): Response
    {
        return $this->render('crp/funding_organization/index.html.twig', [
            'funding_organizations' => $fundingOrganizationRepository->findAll(),
        ]);
    }

    #[Route('/new', name: 'app_c_r_p_funding_organization_new', methods: ['GET', 'POST'])]
    public function new(Request $request, FundingOrganizationRepository $fundingOrganizationRepository): Response
    {
        $fundingOrganization = new FundingOrganization();
        $form = $this->createForm(FundingOrganizationType::class, $fundingOrganization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

             $file3 = $form->get('logo')->getData();

            if ($file3 == NULL) {
                
             }
            if ($file3) {
            $file3 = $form->get('logo')->getData();

                $fundeddocDocsfileName3 = 'CompanyLogo-'.  md5(uniqid()) . '.' . $file3->guessExtension();;
                $file3->move($this->getParameter('profile_pictures'), $fundeddocDocsfileName3);
                $fundingOrganization->setLogo($fundeddocDocsfileName3);
               
            }
            $fundingOrganizationRepository->add($fundingOrganization);
            
            return $this->redirectToRoute('app_c_r_p_funding_organization_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/funding_organization/new.html.twig', [
            'funding_organization' => $fundingOrganization,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_funding_organization_show', methods: ['GET'])]
    public function show(FundingOrganization $fundingOrganization): Response
    {
        return $this->render('crp/funding_organization/show.html.twig', [
            'funding_organization' => $fundingOrganization,
        ]);
    }

    #[Route('/{id}/edit', name: 'app_c_r_p_funding_organization_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, FundingOrganization $fundingOrganization, FundingOrganizationRepository $fundingOrganizationRepository): Response
    {
        $form = $this->createForm(FundingOrganizationType::class, $fundingOrganization);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
          
            $file3 = $form->get('logo')->getData();

            if ($file3 == NULL) {
                
             }
            if ($file3) {
            $file3 = $form->get('logo')->getData();

                $fundeddocDocsfileName3 = 'CompanyLogo-'.  md5(uniqid()) . '.' . $file3->guessExtension();;
                $file3->move($this->getParameter('profile_pictures'), $fundeddocDocsfileName3);
                $fundingOrganization->setLogo($fundeddocDocsfileName3);
               
            }
              $fundingOrganizationRepository->add($fundingOrganization);
            return $this->redirectToRoute('app_c_r_p_funding_organization_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('crp/funding_organization/edit.html.twig', [
            'funding_organization' => $fundingOrganization,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'app_c_r_p_funding_organization_delete', methods: ['POST'])]
    public function delete(Request $request, FundingOrganization $fundingOrganization, FundingOrganizationRepository $fundingOrganizationRepository): Response
    {
        if ($this->isCsrfTokenValid('delete'.$fundingOrganization->getId(), $request->request->get('_token'))) {
            $fundingOrganizationRepository->remove($fundingOrganization);
        }

        return $this->redirectToRoute('app_c_r_p_funding_organization_index', [], Response::HTTP_SEE_OTHER);
    }
}
