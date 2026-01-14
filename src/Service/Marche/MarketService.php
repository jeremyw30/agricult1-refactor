<?php

declare(strict_types=1);

namespace App\Service\Marche;

use App\Entity\User;

/**
 * Service de gestion du marché
 * 
 * Gère les prix, les offres et les échanges entre joueurs
 */
class MarketService
{
    /**
     * Récupère le prix d'achat d'une parcelle en fonction de la superficie
     * 
     * @param float $superficie Superficie en hectares
     * @return float Prix en euros
     */
    public function getParcellePrice(float $superficie): float
    {
        // Prix de base: 500€ par hectare
        return $superficie * 500;
    }

    /**
     * Récupère le prix d'un animal
     * 
     * @param string $type Type d'animal
     * @return float Prix
     */
    public function getAnimalPrice(string $type): float
    {
        $prices = [
            'poule' => 50,
            'vache' => 500,
            'cochon' => 200,
            'mouton' => 150,
            'chevre' => 180,
        ];

        return $prices[$type] ?? 100;
    }

    /**
     * Récupère le prix d'une machine
     * 
     * @param string $type Type de machine
     * @return float Prix
     */
    public function getMachinePrice(string $type): float
    {
        $prices = [
            'tracteur' => 5000,
            'moissonneuse' => 8000,
            'charrue' => 1000,
            'semoir' => 1500,
        ];

        return $prices[$type] ?? 1000;
    }

    /**
     * Récupère le prix d'un bâtiment
     * 
     * @param string $type Type de bâtiment
     * @return float Prix
     */
    public function getBatimentPrice(string $type): float
    {
        $prices = [
            'grange' => 2000,
            'silo' => 3000,
            'etable' => 4000,
            'poulailler' => 1500,
        ];

        return $prices[$type] ?? 2000;
    }

    /**
     * Calcule le coût d'amélioration d'un bâtiment
     * 
     * @param int $niveauActuel
     * @return float Coût
     */
    public function getUpgradeCost(int $niveauActuel): float
    {
        // Coût croissant avec le niveau
        return 1000 * $niveauActuel * 1.5;
    }
}
