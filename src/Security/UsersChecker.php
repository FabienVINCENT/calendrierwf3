<?php

namespace App\Security;

use App\Entity\User as AppUser;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserCheckerInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\Exception\CustomUserMessageAuthenticationException;

class UsersChecker implements UserCheckerInterface
{
    public function checkPreAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }

        if (
            !in_array('ROLE_ADMIN', $user->getRoles()) &&
            !in_array('ROLE_FORMATEUR', $user->getRoles())
        ) {
            // return new Response("");
            throw new CustomUserMessageAuthenticationException('Votre compte formateur n\'est pas activé. Veuillez contactez un administrateur.');
        }
    }

    public function checkPostAuth(UserInterface $user)
    {
        if (!$user instanceof AppUser) {
            return;
        }
    }
}
