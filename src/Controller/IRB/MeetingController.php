<?php

namespace App\Controller\IRB;

use App\Entity\IRB\Application;
use App\Entity\IRB\BoardMember;
use App\Entity\IRB\Meeting;
use App\Form\IRB\MeetingType;
use App\Repository\IRB\MeetingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/irb-meeting')]
class MeetingController extends AbstractController
{
    #[Route('/', name: 'i_r_b_meeting_index', methods: ['GET'])]
    public function index(MeetingRepository $meetingRepository, PaginatorInterface $paginator, Request $request): Response
    {
        $this->denyAccessUnlessGranted('mng_brd_mtng');

        $query = $meetingRepository->getData();
        $meetings = $paginator->paginate(
            $query,
            $request->query->getInt('page', 1),
            10
        );
        return $this->render('irb/meeting/index.html.twig', [
            'meetings' => $meetings,
        ]);
    }

    #[Route('/new', name: 'i_r_b_meeting_new', methods: ['GET', 'POST'])]
    public function new(Request $request, EntityManagerInterface $entityManager): Response
    {
        $meeting = new Meeting();
        $form = $this->createForm(MeetingType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $meeting->setCreatedBy($this->getUser());
            $meeting->setCollege($this->getUser()->getUserInfo()?->getCollege());
            $meeting->setStatus(Meeting::STATUS_ACTIVE);
            /**
             * close meeting
             */
            $query = $entityManager->createQuery(
                'UPDATE  App:IRB\Meeting m SET  m.status=2 where m.status=1'
            )
                ->execute();

            /**
             * send email
             */

            // foreach ($entityManager->getRepository(BoardMember::class)->findAll()  as $key => $value) {



            //     $this->mailHelper->sendEmail(
            //         $value->getUser()->getEmail(),
            //         "New board meeting is scheduled",
            //         "emails/general.html.twig",
            //         [
            //             "info" => "New board meeting is scheduled",
            //             "subject" => "New board meeting is scheduled",
            //             "body" => "
            //             the meeting will held at <b>" . $meeting->getHeldAt()->format('Y-m-d H:iA') . "</b>
            //             <hr/>
            //             the meeting code <b>".$meeting->getNumber()."</b>
            //             ",
            //         ]
            //     );
            // }

            $entityManager->persist($meeting);
            $entityManager->flush();
            $this->addFlash("success", "Meeting Created");


            return $this->redirectToRoute('i_r_b_meeting_show', ["id" => $meeting->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/meeting/new.html.twig', [
            'meeting' => $meeting,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}/take-minute', name: 'i_r_b_meeting_minute', methods: ['GET', 'POST'])]
    public function minute(Meeting $meeting, Request $request, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeetingType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $meeting->setCreatedBy($this->getUser());
            $meeting->setCollege($this->getUser()->getUserInfo()?->getCollege());
            $meeting->setStatus(Meeting::STATUS_CLOSED);
            $meeting->setMinuteTakenBy($this->getUser());
            $meeting->setMinuteTakenAt($meeting->getMinuteTakenAt()?:new \DateTime());


            /**
             * send email
             */

            // foreach ($entityManager->getRepository(BoardMember::class)->findAll()  as $key => $value) {



            //     $this->mailHelper->sendEmail(
            //         $value->getUser()->getEmail(),
            //         "New board meeting is scheduled",
            //         "emails/general.html.twig",
            //         [
            //             "info" => "New board meeting is scheduled",
            //             "subject" => "New board meeting is scheduled",
            //             "body" => "
            //             the meeting will held at <b>" . $meeting->getHeldAt()->format('Y-m-d H:iA') . "</b>
            //             <hr/>
            //             the meeting code <b>".$meeting->getNumber()."</b>
            //             ",
            //         ]
            //     );
            // }

            $entityManager->flush();
            $this->addFlash("success", "Meeting Minute taken");
            
           return $this->redirectToRoute('i_r_b_meeting_show', ["id" => $meeting->getId()], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/meeting/minute.html.twig', [
            'meeting' => $meeting,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_meeting_show', methods: ['GET'])]
    public function show(Meeting $meeting): Response
    {
        // dd($meeting->getApplications()->toArray());

        return $this->render('irb/meeting/show.html.twig', [
            'meeting' => $meeting,
        ]);
    }

    #[Route('/{id}/edit', name: 'i_r_b_meeting_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, Meeting $meeting, EntityManagerInterface $entityManager): Response
    {
        $form = $this->createForm(MeetingType::class, $meeting);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $entityManager->flush();
            $meeting->getApplications()->map(function ($app) use ($meeting) {

                $app->setMeeting($meeting);
            });
            $entityManager->flush();

            return $this->redirectToRoute('i_r_b_meeting_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('irb/meeting/edit.html.twig', [
            'meeting' => $meeting,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/{id}', name: 'i_r_b_meeting_delete', methods: ['POST'])]
    public function delete(Request $request, Meeting $meeting, EntityManagerInterface $entityManager): Response
    {
        if ($this->isCsrfTokenValid('delete' . $meeting->getId(), $request->request->get('_token'))) {
            $entityManager->remove($meeting);
            $entityManager->flush();
        }

        return $this->redirectToRoute('i_r_b_meeting_index', [], Response::HTTP_SEE_OTHER);
    }
}
