<?php

namespace App\Repository;

use App\Entity\RunEntity;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

class RunEntityRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, RunEntity::class);
    }


    public function findByOwner($user_id)
    {
        return $this->createQueryBuilder('r')
            ->where('r.user_id = :user_id')
            ->setParameter('user_id', $user_id)
            ->orderBy('r.date', 'DESC')
            ->getQuery()
            ->getResult();
    }

    public function deleteRun($run_id) {
        return $this->createQueryBuilder('r')
            ->where('r.id = :run_id')
            ->setParameter('run_id', $run_id)
            ->delete()
            ->getQuery()
            ->getResult();
    }

}
