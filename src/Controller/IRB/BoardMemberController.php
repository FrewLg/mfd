<?php

namespace App\Controller\IRB;

use App\Form\IRB\BoardMemberType;
use App\Entity\IRB\BoardMember;
use App\Repository\IRB\BoardMemberRepository;
use App\Utils\Constants;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb/board-member')]
class BoardMemberController extends AbstractController
{
    #[Route('/', name: 'board_member_index', methods: ['GET', "POST"])]
    public function index(BoardMemberRepository $boardMemberRepository, Request $request, EntityManagerInterface $entityManager, PaginatorInterface $paginator): Response
    {
        $this->denyAccessUnlessGranted('vw_brd_mmbr');
        
        $boardMember = new BoardMember();
        $form = $this->createForm(BoardMemberType::class, $boardMember);

        $form->handleRequest($request);

        if ($request->request->get('change-role')) {



            $boardMember = $boardMemberRepository->find($request->request->get('board_member'));
            $boardMember->setRole($request->request->get('roles'));
            $boardMember->getUser()->addRole(Constants::ROLE_BOARD_MEMBER);
              
            $entityManager->flush();
            $this->addFlash("success", "Role changed");

            return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
        }


        if ($form->isSubmitted() && $form->isValid()) {

            if ($this->getUser()->getUserInfo()?->getCollege()) {
                $boardMember->setAssignedBy($this->getUser());
                $boardMember->getUser()->addRole(Constants::ROLE_BOARD_MEMBER);
                $boardMember->getUser()->addRole($form['role']->getData());
                $boardMember->setCollege($this->getUser()->getUserInfo()->getCollege());
          
                $entityManager->persist($boardMember);
                
                $entityManager->flush();
                $this->addFlash("success", "Registered successfully");
            } else {
                $this->addFlash("danger", "Your college is not set");
            }

            return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
        }
        $queryBulder = $boardMemberRepository->getData(["search" => $request->query->get('search')]);
        $board_members = $paginator->paginate(
            $queryBulder,
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('board_member/index.html.twig', [
            'board_members' => $board_members,
            'board_member' => $boardMember,
            'form' => $form->createView(),
        ]);
    }



    #[Route('/{id}', name: 'board_member_delete', methods: ['POST'])]
    public function delete(Request $request, BoardMember $boardMember, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $boardMember->getId(), $request->request->get('_token'))) {
            $boardMember->getUser()->removeRole(Constants::ROLE_BOARD_MEMBER);
            $entityManager->remove($boardMember);
            $entityManager->flush();

            $this->addFlash("success", "Removed successfully!!!");
        }

        return $this->redirectToRoute('board_member_index', [], Response::HTTP_SEE_OTHER);
    }
}
