<?php

declare(strict_types=1);

namespace App\Service\Batiment;

use App\Entity\User;
use App\Entity\UserBatiment;
use App\Repository\UserBatimentRepository;
use App\Service\Marche\TransactionService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion des bâtiments
 * 
 * Gère la construction et l'amélioration des bâtiments
 */
class BatimentService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserBatimentRepository $batimentRepo,
        private readonly TransactionService $transactionService
    ) {}

    /**
     * Récupère tous les bâtiments d'un utilisateur
     * 
     * @param User $user
     * @return UserBatiment[]
     */
    public function getUserBatiments(User $user): array
    {
        return $this->batimentRepo->findActiveByUser($user);
    }

    /**
     * Construit un nouveau bâtiment
     * 
     * @param User $user
     * @param string $type
     * @param string $nom
     * @param float $price
     * @return UserBatiment
     */
    public function buildBatiment(User $user, string $type, string $nom, float $price): UserBatiment
    {
        // Débiter le compte
        $this->transactionService->debit(
            $user,
            $price,
            sprintf('Construction bâtiment: %s', $nom)
        );

        // Créer le bâtiment
        $batiment = new UserBatiment();
        $batiment->setIdUser($user);
        $batiment->setType($type);
        $batiment->setNom($nom);
        $batiment->setNiveau(1);
        $batiment->setCapacite('1000.00');

        $this->em->persist($batiment);
        $this->em->flush();

        return $batiment;
    }

    /**
     * Améliore un bâtiment (augmente son niveau)
     * 
     * @param UserBatiment $batiment
     * @param User $user
     * @param float $coutAmelioration
     */
    public function upgradeBatiment(UserBatiment $batiment, User $user, float $coutAmelioration): void
    {
        // Débiter le compte
        $this->transactionService->debit(
            $user,
            $coutAmelioration,
            sprintf('Amélioration bâtiment: %s (niveau %d)', $batiment->getNom(), $batiment->getNiveau() + 1)
        );

        $nouveauNiveau = $batiment->getNiveau() + 1;
        $nouvelleCapacite = ((float) $batiment->getCapacite()) * 1.5;

        $batiment->setNiveau($nouveauNiveau);
        $batiment->setCapacite((string) $nouvelleCapacite);

        $this->em->persist($batiment);
        $this->em->flush();
    }
}
