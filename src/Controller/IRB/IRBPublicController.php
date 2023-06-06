<?php

namespace App\Controller\IRB;

use App\Entity\IrbCertificate;
use App\Helper\DomPrint;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;
use Symfony\Component\Routing\Annotation\Route;

class IRBPublicController extends AbstractController
{
    #[Route('/irb-clearance/{certificateCode}', name: 'irb_validate2')]
    #[Route('/irb-clearance/', name: 'irb_validate')]
    public function index(Request $request,IrbCertificate $irbCertificate=null,DomPrint $domPrint): Response
    {
        $em=$this->getDoctrine()->getManager();
        if($request->request->get('validate')){
           $irbCertificate= $em->getRepository(IrbCertificate::class)->findOneBy(['certificateCode'=>$request->request->get('validate')]);
            if(!$irbCertificate){
                 $this->addFlash('danger','No IRB clearance was issued with "'
                 .$request->request->get('validate').'" code');
                
                }

            else{

                $this->addFlash('success',' IRB ethical clearance certificate  found "');
               
                return $this->render('irb/clearance.html.twig', [
                    'irb'=>$irbCertificate
                ]);
            }
        }
        if($request->query->get('export')){
            if($irbCertificate->getIrbApplication()->getSubmittedBy() != $this->getUser()){
               return new AccessDeniedHttpException(); 
            }
           return  new Response($domPrint->print("irb/print.html.twig",["certificate"=>$irbCertificate],"PRINT",DomPrint::ORIENTATION_PORTRAIT,DomPrint::PAPER_A4,true));
        }

        return $this->render('irb/clearance.html.twig', [
           
        ]);
    }
}
