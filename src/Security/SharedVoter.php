<?php

declare(strict_types=1);

namespace App\Security;

use App\Entity\ToDo;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\User\UserInterface;

class SharedVoter extends Voter
{
    const IS_SHARED = 'IS_SHARED';
    protected function supports(string $attribute, $subject): bool
    {
        if ($attribute !== self::IS_SHARED) {
            return false;
        }
        return $subject instanceof ToDo;
    }
    /**
     * @param ToDo $subject
     */
    protected function voteOnAttribute(string $attribute, $subject, TokenInterface $token): bool
    {
        $currentUser = $token->getUser();
        if (!$currentUser instanceof UserInterface) {
            return false;
        }
        return $subject->getUsers()->contains($currentUser);
    }
}
