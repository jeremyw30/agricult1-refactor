<?php

declare(strict_types=1);

namespace App\Service\Game;

use App\Entity\User;
use App\Service\Ferme\FermeService;
use App\Service\Animal\AnimalService;
use App\Service\Machine\MachineService;
use App\Service\Batiment\BatimentService;
use App\Service\Meteo\MeteoService;

/**
 * Service de gestion de l'état du jeu
 * 
 * Centralise les informations sur l'état global du jeu pour un utilisateur
 */
class GameStateService
{
    public function __construct(
        private readonly FermeService $fermeService,
        private readonly AnimalService $animalService,
        private readonly MachineService $machineService,
        private readonly BatimentService $batimentService,
        private readonly MeteoService $meteoService
    ) {}

    /**
     * Récupère l'état complet du jeu pour un utilisateur
     * 
     * @param User $user
     * @return array<string, mixed>
     */
    public function getGameState(User $user): array
    {
        return [
            'user' => [
                'username' => $user->getUsername(),
                'balance' => $user->getBalance(),
                'createdAt' => $user->getCreatedAt(),
            ],
            'ferme' => $this->fermeService->getFermeInfo($user),
            'animals' => $this->animalService->getUserAnimals($user),
            'machines' => $this->machineService->getUserMachines($user),
            'batiments' => $this->batimentService->getUserBatiments($user),
            'meteo' => $this->meteoService->getToday(),
        ];
    }

    /**
     * Vérifie si l'utilisateur peut effectuer une action
     * 
     * @param User $user
     * @param string $action
     * @param float $cost
     * @return bool
     */
    public function canPerformAction(User $user, string $action, float $cost = 0): bool
    {
        // Vérifier le solde si l'action a un coût
        if ($cost > 0) {
            $balance = (float) $user->getBalance();
            if ($balance < $cost) {
                return false;
            }
        }

        // Vérifier que l'utilisateur est actif
        if (!$user->isActive()) {
            return false;
        }

        return true;
    }
}
