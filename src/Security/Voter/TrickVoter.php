<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TrickVoter extends Voter
{
    public const EDIT = 'POST_EDIT';
    public const DELETE = 'POST_DELETE';

    protected function supports(string $attribute, mixed $subject): bool
    {
        return in_array($attribute, [self::EDIT, self::DELETE])
            && $subject instanceof \App\Entity\Trick;
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        // If the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        switch ($attribute) {
            case self::EDIT:
                if ($user == $subject->getUser()) {
                    return true;
                }
                if(in_array("ROLE_ADMIN", $user->getRoles())) {
                    return true;
                }
                break;
            case self::DELETE:
                if ($user == $subject->getUser()) {
                    return true;
                }
                if(in_array("ROLE_ADMIN", $user->getRoles())) {
                    return true;
                }
                break;
            default:
        }
        return false;
    }
}
