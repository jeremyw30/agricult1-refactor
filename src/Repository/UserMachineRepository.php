<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserMachine;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les machines utilisateur
 * 
 * @extends ServiceEntityRepository<UserMachine>
 */
class UserMachineRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserMachine::class);
    }

    /**
     * Récupère les machines actives d'un utilisateur
     * 
     * @param User $user
     * @return UserMachine[]
     */
    public function findActiveByUser(User $user): array
    {
        return $this->createQueryBuilder('m')
            ->where('m.idUser = :user')
            ->andWhere('m.active = :active')
            ->setParameter('user', $user)
            ->setParameter('active', true)
            ->orderBy('m.type', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
