<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Transaction;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les transactions
 * 
 * @extends ServiceEntityRepository<Transaction>
 */
class TransactionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Transaction::class);
    }

    /**
     * Récupère l'historique des transactions d'un utilisateur
     * 
     * @param User $user
     * @param int $limit
     * @return Transaction[]
     */
    public function findByUserHistory(User $user, int $limit = 50): array
    {
        return $this->createQueryBuilder('t')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->orderBy('t.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }

    /**
     * Calcule le total des transactions par type
     * 
     * @param User $user
     * @return array<string, string>
     */
    public function getTotalByType(User $user): array
    {
        $results = $this->createQueryBuilder('t')
            ->select('t.type', 'SUM(t.montant) as total')
            ->where('t.user = :user')
            ->setParameter('user', $user)
            ->groupBy('t.type')
            ->getQuery()
            ->getResult();

        $totals = [];
        foreach ($results as $result) {
            $totals[$result['type']] = $result['total'];
        }

        return $totals;
    }
}
