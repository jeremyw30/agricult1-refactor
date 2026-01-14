<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\UserAnimal;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les animaux utilisateur
 * 
 * @extends ServiceEntityRepository<UserAnimal>
 */
class UserAnimalRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, UserAnimal::class);
    }

    /**
     * Récupère les animaux actifs d'un utilisateur
     * 
     * @param User $user
     * @return UserAnimal[]
     */
    public function findActiveByUser(User $user): array
    {
        return $this->createQueryBuilder('a')
            ->where('a.idUser = :user')
            ->andWhere('a.active = :active')
            ->setParameter('user', $user)
            ->setParameter('active', true)
            ->orderBy('a.type', 'ASC')
            ->getQuery()
            ->getResult();
    }

    /**
     * Compte les animaux d'un utilisateur par type
     * 
     * @param User $user
     * @return array<string, int>
     */
    public function countByType(User $user): array
    {
        $results = $this->createQueryBuilder('a')
            ->select('a.type', 'COUNT(a.id) as total')
            ->where('a.idUser = :user')
            ->andWhere('a.active = :active')
            ->setParameter('user', $user)
            ->setParameter('active', true)
            ->groupBy('a.type')
            ->getQuery()
            ->getResult();

        $counts = [];
        foreach ($results as $result) {
            $counts[$result['type']] = (int) $result['total'];
        }

        return $counts;
    }
}
