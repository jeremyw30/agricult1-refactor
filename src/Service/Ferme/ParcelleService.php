<?php

declare(strict_types=1);

namespace App\Service\Ferme;

use App\Entity\User;
use App\Entity\UserParcelle;
use App\DTO\Ferme\ParcelleDTO;
use App\Repository\UserParcelleRepository;
use App\Service\Marche\TransactionService;
use App\Exception\InsufficientBalanceException;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion des parcelles agricoles
 * 
 * Gère l'achat, la vente et les activités sur les parcelles
 */
class ParcelleService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserParcelleRepository $parcelleRepo,
        private readonly TransactionService $transactionService
    ) {}

    /**
     * Récupère toutes les parcelles d'un utilisateur
     * 
     * @param User $user Utilisateur propriétaire
     * @return UserParcelle[] Liste des parcelles
     */
    public function getUserParcelles(User $user): array
    {
        return $this->parcelleRepo->findActiveByUser($user);
    }

    /**
     * Achète une nouvelle parcelle pour l'utilisateur
     * 
     * @param User $user Utilisateur acheteur
     * @param ParcelleDTO $dto Données de la parcelle
     * @return UserParcelle Parcelle créée
     * @throws InsufficientBalanceException Si solde insuffisant
     */
    public function buyParcelle(User $user, ParcelleDTO $dto): UserParcelle
    {
        // Débiter le compte
        $this->transactionService->debit(
            $user, 
            $dto->price, 
            sprintf('Achat parcelle %.2f ha', $dto->superficie)
        );

        // Créer la parcelle
        $parcelle = new UserParcelle();
        $parcelle->setIdUser($user);
        $parcelle->setSuperficie((string) $dto->superficie);
        $parcelle->setTypesSol($dto->typesSol);
        $parcelle->setCreatedAt(new \DateTimeImmutable());
        
        $this->em->persist($parcelle);
        $this->em->flush();

        return $parcelle;
    }

    /**
     * Cultive une parcelle
     * 
     * @param UserParcelle $parcelle Parcelle à cultiver
     * @param string $cultureType Type de culture
     */
    public function cultivate(UserParcelle $parcelle, string $cultureType): void
    {
        $now = new \DateTimeImmutable();
        
        $parcelle->setCultureActuelle($cultureType);
        $parcelle->setDatePlantation($now);
        
        // Calculer la date de récolte (exemple: 7 jours plus tard)
        $dateRecolte = $now->modify('+7 days');
        $parcelle->setDateRecolte($dateRecolte);
        
        $this->em->persist($parcelle);
        $this->em->flush();
    }

    /**
     * Récolte une parcelle
     * 
     * @param UserParcelle $parcelle
     * @param User $user
     * @return float Montant de la récolte
     */
    public function harvest(UserParcelle $parcelle, User $user): float
    {
        if ($parcelle->getDateRecolte() === null) {
            throw new \RuntimeException('Cette parcelle n\'a pas de culture à récolter');
        }

        if ($parcelle->getDateRecolte() > new \DateTimeImmutable()) {
            throw new \RuntimeException('La culture n\'est pas encore prête à être récoltée');
        }

        // Calculer le montant de la récolte (exemple simple)
        $superficie = (float) $parcelle->getSuperficie();
        $montantRecolte = $superficie * 100; // 100€ par hectare

        // Créditer le compte de l'utilisateur
        $this->transactionService->credit(
            $user,
            $montantRecolte,
            sprintf('Récolte parcelle %s (%.2f ha)', $parcelle->getCultureActuelle(), $superficie)
        );

        // Réinitialiser la parcelle
        $parcelle->setCultureActuelle(null);
        $parcelle->setDatePlantation(null);
        $parcelle->setDateRecolte(null);

        $this->em->persist($parcelle);
        $this->em->flush();

        return $montantRecolte;
    }

    /**
     * Calcule la superficie totale des parcelles d'un utilisateur
     * 
     * @param User $user
     * @return float
     */
    public function getTotalSuperficie(User $user): float
    {
        return $this->parcelleRepo->getTotalSuperficie($user);
    }
}
