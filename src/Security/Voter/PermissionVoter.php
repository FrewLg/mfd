<?php

namespace App\Security\Voter;

use App\Entity\Permission;
use App\Entity\User;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authorization\AccessDecisionManagerInterface;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\User\UserInterface;

class PermissionVoter extends Voter
{
    private $session;
    private $security;


    public function __construct(SessionInterface $session, Security $security)
    {
        $this->session = $session;
        $this->security = $security;
    }
    protected function supports($attribute, $subject)
    {
        


        if ($this->security->getUser() &&   $this->security->getUser()->getIsSuperAdmin())
            return true;

        $permission = $this->session->get("PERMISSIONS");


        if (!$permission)
            $permission = array();


        return in_array($attribute, $permission);
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();


        /** 
         * @var \App\Entity\User|null $user
         */


        if (!$user instanceof UserInterface) {
            return false;
        }
        if ($user->getIsSuperAdmin())
            return true;

        $permission = $this->session->get("PERMISSIONS");

        if (!$permission)
            $permission = array();
        return in_array($attribute, $permission);
    }
}
