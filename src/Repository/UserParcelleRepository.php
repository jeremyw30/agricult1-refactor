<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use App\Entity\UserParcelle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les parcelles utilisateur
 * 
 * Contient les requêtes DQL optimisées
 * 
 * @extends ServiceEntityRepository<UserParcelle>
 */
class UserParcelleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserParcelle::class);
    }

    /**
     * Récupère les parcelles actives d'un utilisateur
     * 
     * @param User $user Utilisateur propriétaire
     * @return UserParcelle[]
     */
    public function findActiveByUser(User $user): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.idUser = :user')
            ->andWhere('p.active = :active')
            ->setParameter('user', $user)
            ->setParameter('active', true)
            ->orderBy('p.createdAt', 'DESC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcule la superficie totale des parcelles d'un utilisateur
     * 
     * @param User $user Utilisateur
     * @return float Superficie totale
     */
    public function getTotalSuperficie(User $user): float
    {
        $result = $this->createQueryBuilder('p')
            ->select('SUM(p.superficie) as total')
            ->where('p.idUser = :user')
            ->andWhere('p.active = :active')
            ->setParameter('user', $user)
            ->setParameter('active', true)
            ->getQuery()
            ->getSingleScalarResult();

        return (float) ($result ?? 0);
    }

    /**
     * Trouve les parcelles prêtes à être récoltées
     * 
     * @return UserParcelle[]
     */
    public function findReadyToHarvest(): array
    {
        $now = new \DateTimeImmutable();
        
        return $this->createQueryBuilder('p')
            ->where('p.dateRecolte IS NOT NULL')
            ->andWhere('p.dateRecolte <= :now')
            ->andWhere('p.active = :active')
            ->setParameter('now', $now)
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();
    }
}
