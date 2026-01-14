<?php

declare(strict_types=1);

namespace App\Service\Machine;

use App\Entity\User;
use App\Entity\UserMachine;
use App\Repository\UserMachineRepository;
use App\Service\Marche\TransactionService;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion des machines agricoles
 * 
 * Gère l'achat, l'entretien et l'utilisation des machines
 */
class MachineService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserMachineRepository $machineRepo,
        private readonly TransactionService $transactionService
    ) {}

    /**
     * Récupère toutes les machines d'un utilisateur
     * 
     * @param User $user
     * @return UserMachine[]
     */
    public function getUserMachines(User $user): array
    {
        return $this->machineRepo->findActiveByUser($user);
    }

    /**
     * Achète une nouvelle machine
     * 
     * @param User $user
     * @param string $type
     * @param string $nom
     * @param float $price
     * @return UserMachine
     */
    public function buyMachine(User $user, string $type, string $nom, float $price): UserMachine
    {
        // Débiter le compte
        $this->transactionService->debit(
            $user,
            $price,
            sprintf('Achat machine: %s', $nom)
        );

        // Créer la machine
        $machine = new UserMachine();
        $machine->setIdUser($user);
        $machine->setType($type);
        $machine->setNom($nom);
        $machine->setEtat(100);

        $this->em->persist($machine);
        $this->em->flush();

        return $machine;
    }

    /**
     * Utilise une machine (diminue son état)
     * 
     * @param UserMachine $machine
     */
    public function useMachine(UserMachine $machine): void
    {
        $nouvelEtat = max(0, $machine->getEtat() - 5);
        $machine->setEtat($nouvelEtat);
        $machine->setDerniereUtilisation(new \DateTimeImmutable());

        $this->em->persist($machine);
        $this->em->flush();
    }

    /**
     * Répare une machine
     * 
     * @param UserMachine $machine
     * @param User $user
     * @param float $coutReparation
     */
    public function repairMachine(UserMachine $machine, User $user, float $coutReparation = 50.0): void
    {
        // Débiter le compte
        $this->transactionService->debit(
            $user,
            $coutReparation,
            sprintf('Réparation machine: %s', $machine->getNom())
        );

        $machine->setEtat(100);

        $this->em->persist($machine);
        $this->em->flush();
    }
}
