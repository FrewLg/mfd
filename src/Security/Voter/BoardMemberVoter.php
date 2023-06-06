<?php

namespace App\Security\Voter;

use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class BoardMemberVoter extends Voter
{
    private $session;
    private $security;


    public function __construct(SessionInterface $session, Security $security)
    {
        $this->session = $session;
        $this->security = $security;
    }
    
    protected function supports(string $attribute, $subject): bool
    {
        return in_array($attribute, ['BOARD_VIEW']);
    }

    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();
        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        /** 
         * @var \App\Entity\User|null $user
         */

        if ($user->getIsSuperAdmin())
        return true;


        if (($this->security->isGranted('ROLE_BOARD_MEMBER') || $this->security->isGranted('vw_irb_rqst')))
       return true;


        return false;
    }
}
