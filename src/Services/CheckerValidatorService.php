<?php

namespace App\Services;

use App\Entity\Submission;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;

class CheckerValidator extends AbstractController {
    public function checkauthor(EntityManagerInterface $em, Submission $submission): Response {
        ////Ultimate reviewers page

        $me = $this->getUser()->getId();

        /////
        $em = $this->getDoctrine()->getManager();
        $thisUser = $this->getUser()->getId();
        $myapplications = $em->getRepository(Submission::class)->find($submission);
        $requesteduser = $myapplications->getAuthor()->getId();
        if ($requesteduser !== $thisUser) {

            $this->addFlash("danger", "Sorry you are not allowed for this service ! thank you!");
            return $this->redirectToRoute('myreviews');

        }
    }
}
