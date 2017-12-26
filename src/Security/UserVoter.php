<?php
/**
 * Created by PhpStorm.
 * User: samuelerb
 * Date: 25.12.17
 * Time: 19:02
 */

namespace App\Security;


use App\Entity\MyUserEntity;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;

class UserVoter extends Voter
{

    const VIEW = 'view';

    protected function supports($attribute, $subject)
    {
        if (!in_array($attribute, array(self::VIEW))) {
            return false;
        }
        if (!$subject instanceof MyUserEntity) {
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
        /**
         * @var MyUserEntity $view_user is the user to be viewed by someone
         * */
        $view_user = $subject;

        switch ($attribute) {
            case self::VIEW:
                return $this->canView($view_user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    private function canView(MyUserEntity $view_user)
    {
        if (in_array('ROLE_USER', $view_user->getRoles()))
        {
           return true;
        }
        return false;
    }
}