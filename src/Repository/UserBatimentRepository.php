<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserBatiment;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les bâtiments utilisateur
 * 
 * @extends ServiceEntityRepository<UserBatiment>
 */
class UserBatimentRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserBatiment::class);
    }

    /**
     * Récupère les bâtiments actifs d'un utilisateur
     * 
     * @param User $user
     * @return UserBatiment[]
     */
    public function findActiveByUser(User $user): array
    {
        return $this->createQueryBuilder('b')
            ->where('b.idUser = :user')
            ->andWhere('b.active = :active')
            ->setParameter('user', $user)
            ->setParameter('active', true)
            ->orderBy('b.type', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
