<?php

namespace App\Repository;

use Symfony\Bridge\Doctrine\Security\User\UserLoaderInterface;
use Doctrine\ORM\EntityRepository;

class UserRepository extends EntityRepository implements UserLoaderInterface
{
    public function loadUserByUsername($username)
    {
        return $this->createQueryBuilder('u')
            ->where('u.username = :username OR u.email = :email')
            ->setParameter('username', $username)
            ->setParameter('email', $username)
            ->getQuery()
            ->getOneOrNullResult();
    }

    public function loadUserByID($user_id) {
        return $this->createQueryBuilder('u')
            ->where('u.id = :id')
            ->setParameter('id', $user_id)
            ->getQuery()
            ->getOneOrNullResult();

    }

    public function searchForUserByName($query) {
        return $this->createQueryBuilder('u')
            ->where('u.username LIKE :query')
            ->setParameter('query', $query.'%')
            ->getQuery()
            ->getResult();
    }


}