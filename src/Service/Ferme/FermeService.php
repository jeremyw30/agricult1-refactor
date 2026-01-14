<?php

declare(strict_types=1);

namespace App\Service\Ferme;

use App\Entity\User;
use App\DTO\Ferme\FermeDTO;
use App\Repository\UserParcelleRepository;

/**
 * Service de gestion de la ferme
 * 
 * Gère les informations globales de la ferme d'un utilisateur
 */
class FermeService
{
    public function __construct(
        private readonly UserParcelleRepository $parcelleRepo,
        private readonly ParcelleService $parcelleService
    ) {}

    /**
     * Récupère les informations de la ferme d'un utilisateur
     * 
     * @param User $user
     * @return FermeDTO
     */
    public function getFermeInfo(User $user): FermeDTO
    {
        $superficieTotale = $this->parcelleService->getTotalSuperficie($user);
        
        return new FermeDTO(
            nom: sprintf('Ferme de %s', $user->getUsername()),
            description: 'Ferme agricole virtuelle',
            superficieTotale: $superficieTotale
        );
    }
}
