<?php
/**
 * Created by PhpStorm.
 * User: samuelerb
 * Date: 25.12.17
 * Time: 12:53
 */

namespace App\Security;


use App\Entity\MyUserEntity;
use App\Entity\RunEntity;
use Psr\Log\LoggerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class RunVoter extends Voter
{
    const VIEW = 'view';
    const EDIT = 'edit';
    const DELETE = 'delete';
    const ADD = 'add';

    /**
     * RunVoter constructor.
     */
    public function __construct()
    {
    }


    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW, self::EDIT, self::DELETE, self::ADD))) {
            return false;
        }
        if (!$subject instanceof RunEntity) {
            return false;
        }
        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {
        $user = $token->getUser();

        if (!$user instanceof MyUserEntity) {
            // the user must be logged in; if not, deny access
            return false;
        }

        // you know $subject is a Post object, thanks to supports
        /** @var RunEntity $run */
        $run = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($run, $user);
            case self::EDIT:
                return $this->canEdit($run, $user);
            case self::DELETE:
                return $this->canDelete($run, $user);
            case self::ADD:
                return $this->canAdd($run, $user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(RunEntity $runEntity, MyUserEntity $userEntity)
    {
        // if they can edit, they can view
        if ($this->canEdit($runEntity, $userEntity)) {
            return true;
        }
        if ($userEntity->getRoles() === array('ROLE_USER')) {
            return true;
        }
        return false;
    }

    private function canEdit(RunEntity $runEntity, MyUserEntity $userEntity)
    {
        // this assumes that the data object has a getOwner() method
        // to get the entity of the user who owns this data object
        return $userEntity == $runEntity->getUser();
    }

    private function canDelete(RunEntity $runEntity, MyUserEntity $userEntity)
    {
        if ($userEntity == $runEntity->getUser()) {
            return true;
        }
        return false;
    }

    private function canAdd(RunEntity $runEntity, MyUserEntity $userEntity)
    {
        if ($this->canDelete($runEntity, $userEntity)) {
            return true;
        }
        return false;
    }
}