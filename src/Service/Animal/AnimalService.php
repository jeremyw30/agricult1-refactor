<?php

declare(strict_types=1);

namespace App\Service\Animal;

use App\Entity\User;
use App\Entity\UserAnimal;
use App\Repository\UserAnimalRepository;
use App\Service\Marche\TransactionService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion des animaux
 * 
 * Gère l'achat, la vente et le soin des animaux
 */
class AnimalService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserAnimalRepository $animalRepo,
        private readonly TransactionService $transactionService
    ) {}

    /**
     * Récupère tous les animaux d'un utilisateur
     * 
     * @param User $user
     * @return UserAnimal[]
     */
    public function getUserAnimals(User $user): array
    {
        return $this->animalRepo->findActiveByUser($user);
    }

    /**
     * Achète un nouvel animal
     * 
     * @param User $user
     * @param string $type Type d'animal
     * @param float $price Prix
     * @return UserAnimal
     */
    public function buyAnimal(User $user, string $type, float $price): UserAnimal
    {
        // Débiter le compte
        $this->transactionService->debit(
            $user,
            $price,
            sprintf('Achat animal: %s', $type)
        );

        // Créer l'animal
        $animal = new UserAnimal();
        $animal->setIdUser($user);
        $animal->setType($type);
        $animal->setAge(0);
        $animal->setSante(100);
        $animal->setBonheur(100);

        $this->em->persist($animal);
        $this->em->flush();

        return $animal;
    }

    /**
     * Nourrit un animal pour améliorer sa santé et son bonheur
     * 
     * @param UserAnimal $animal
     */
    public function feedAnimal(UserAnimal $animal): void
    {
        $sante = min(100, $animal->getSante() + 10);
        $bonheur = min(100, $animal->getBonheur() + 10);

        $animal->setSante($sante);
        $animal->setBonheur($bonheur);

        $this->em->persist($animal);
        $this->em->flush();
    }

    /**
     * Fait produire une ressource à un animal
     * 
     * @param UserAnimal $animal
     * @param User $user
     * @return float Montant de la production
     */
    public function produceResource(UserAnimal $animal, User $user): float
    {
        // Vérifier si l'animal peut produire
        $now = new \DateTimeImmutable();
        $lastProd = $animal->getDerniereProd();

        if ($lastProd !== null && $lastProd->diff($now)->h < 24) {
            throw new \RuntimeException('Cet animal a déjà produit aujourd\'hui');
        }

        // Production basée sur la santé et le bonheur
        $qualite = ($animal->getSante() + $animal->getBonheur()) / 2;
        $montantProduction = ($qualite / 100) * 50; // Max 50€ par production

        // Créditer le compte
        $this->transactionService->credit(
            $user,
            $montantProduction,
            sprintf('Production %s', $animal->getType())
        );

        $animal->setDerniereProd($now);
        $this->em->persist($animal);
        $this->em->flush();

        return $montantProduction;
    }
}
