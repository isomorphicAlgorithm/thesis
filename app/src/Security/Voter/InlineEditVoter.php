<?php

namespace App\Security\Voter;

use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;
use App\Entity\User;
use App\Entity\Album;
use App\Entity\Band;
use App\Entity\Song;
use App\Entity\Musician;

final class InlineEditVoter extends Voter
{
    //public const EDIT = 'POST_EDIT';
    public const EDIT = 'INLINE_EDIT';
    public const VIEW = 'POST_VIEW';

    protected function supports(string $attribute, mixed $subject): bool
    {

        return $attribute === self::EDIT && (
            $subject instanceof User ||
            $subject instanceof Album ||
            $subject instanceof Band ||
            $subject instanceof Song ||
            $subject instanceof Musician
        );
        // replace with your own logic
        // https://symfony.com/doc/current/security/voters.html
        /*return in_array($attribute, [self::EDIT, self::VIEW])
            && $subject instanceof \App\Entity\InlineEdit;*/
    }

    protected function voteOnAttribute(string $attribute, mixed $subject, TokenInterface $token): bool
    {
        $user = $token->getUser();

        if (!$user instanceof UserInterface) {
            return false;
        }

        if ($attribute !== self::EDIT) {
            return false;
        }

        // Admins can edit anything
        if (in_array('ROLE_ADMIN', $user->getRoles(), true)) {
            return true;
        }

        // Ownership logic
        if ($subject instanceof User) {
            return $subject === $user;
        }

        if (method_exists($subject, 'getOwner')) {
            return $subject->getOwner() === $user;
        }

        return false;
        /*
        $user = $token->getUser();

        // if the user is anonymous, do not grant access
        if (!$user instanceof UserInterface) {
            return false;
        }

        // ... (check conditions and return true to grant permission) ...
        switch ($attribute) {
            case self::EDIT:
                // logic to determine if the user can EDIT
                // return true or false
                break;

            case self::VIEW:
                // logic to determine if the user can VIEW
                // return true or false
                break;
        }

        return false;
        */
    }
}
