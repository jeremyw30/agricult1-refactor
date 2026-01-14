<?php

declare(strict_types=1);

namespace App\Service\Marche;

use App\Entity\User;
use App\Entity\Transaction;
use App\Exception\InsufficientBalanceException;
use App\Repository\TransactionRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion des transactions financières
 * 
 * Gère les débits, crédits et l'historique des transactions
 */
class TransactionService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly TransactionRepository $transactionRepo
    ) {}

    /**
     * Débite le compte d'un utilisateur
     * 
     * @param User $user Utilisateur à débiter
     * @param float $montant Montant à débiter
     * @param string $description Description de la transaction
     * @throws InsufficientBalanceException Si le solde est insuffisant
     */
    public function debit(User $user, float $montant, string $description = ''): Transaction
    {
        $currentBalance = (float) $user->getBalance();
        
        if ($currentBalance < $montant) {
            throw new InsufficientBalanceException(
                sprintf('Solde insuffisant. Solde actuel: %.2f, Montant requis: %.2f', $currentBalance, $montant)
            );
        }

        $newBalance = $currentBalance - $montant;
        $user->setBalance((string) $newBalance);

        $transaction = new Transaction();
        $transaction->setUser($user);
        $transaction->setType('debit');
        $transaction->setMontant((string) $montant);
        $transaction->setDescription($description);

        $this->em->persist($transaction);
        $this->em->persist($user);
        $this->em->flush();

        return $transaction;
    }

    /**
     * Crédite le compte d'un utilisateur
     * 
     * @param User $user Utilisateur à créditer
     * @param float $montant Montant à créditer
     * @param string $description Description de la transaction
     */
    public function credit(User $user, float $montant, string $description = ''): Transaction
    {
        $currentBalance = (float) $user->getBalance();
        $newBalance = $currentBalance + $montant;
        $user->setBalance((string) $newBalance);

        $transaction = new Transaction();
        $transaction->setUser($user);
        $transaction->setType('credit');
        $transaction->setMontant((string) $montant);
        $transaction->setDescription($description);

        $this->em->persist($transaction);
        $this->em->persist($user);
        $this->em->flush();

        return $transaction;
    }

    /**
     * Récupère l'historique des transactions d'un utilisateur
     * 
     * @param User $user
     * @param int $limit Nombre maximum de transactions à retourner
     * @return Transaction[]
     */
    public function getHistory(User $user, int $limit = 50): array
    {
        return $this->transactionRepo->findByUserHistory($user, $limit);
    }
}
