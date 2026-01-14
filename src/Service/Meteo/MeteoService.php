<?php

declare(strict_types=1);

namespace App\Service\Meteo;

use App\Entity\MeteoData;
use App\Repository\MeteoDataRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion de la météo
 * 
 * Génère et gère les données météorologiques qui influencent les cultures
 */
class MeteoService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly MeteoDataRepository $meteoRepo
    ) {}

    /**
     * Récupère la météo du jour
     * 
     * @return MeteoData|null
     */
    public function getToday(): ?MeteoData
    {
        return $this->meteoRepo->findToday();
    }

    /**
     * Récupère l'historique météo
     * 
     * @param int $days
     * @return MeteoData[]
     */
    public function getHistory(int $days = 7): array
    {
        return $this->meteoRepo->findLastDays($days);
    }

    /**
     * Génère une nouvelle météo aléatoire pour le jour
     * 
     * @return MeteoData
     */
    public function generateToday(): MeteoData
    {
        $conditions = ['ensoleille', 'nuageux', 'pluvieux', 'orageux', 'neigeux'];
        $condition = $conditions[array_rand($conditions)];

        $meteo = new MeteoData();
        $meteo->setDate(new \DateTimeImmutable('today'));
        $meteo->setCondition($condition);
        $meteo->setTemperature((string) (rand(-5, 35)));
        $meteo->setHumidite(rand(20, 100));
        
        // Précipitation basée sur la condition
        $precipitation = match($condition) {
            'pluvieux' => (string) (rand(5, 20)),
            'orageux' => (string) (rand(20, 50)),
            'neigeux' => (string) (rand(10, 30)),
            default => '0.00'
        };
        $meteo->setPrecipitation($precipitation);

        $this->em->persist($meteo);
        $this->em->flush();

        return $meteo;
    }

    /**
     * Calcule l'impact de la météo sur les cultures
     * 
     * @param MeteoData $meteo
     * @return float Coefficient multiplicateur (0.5 à 1.5)
     */
    public function getCultureImpact(MeteoData $meteo): float
    {
        $impact = 1.0;

        // Impact basé sur la condition
        $impact *= match($meteo->getCondition()) {
            'ensoleille' => 1.2,
            'nuageux' => 1.0,
            'pluvieux' => 1.1,
            'orageux' => 0.7,
            'neigeux' => 0.5,
            default => 1.0
        };

        // Impact de la température (idéal: 15-25°C)
        $temp = (float) $meteo->getTemperature();
        if ($temp < 5 || $temp > 35) {
            $impact *= 0.6;
        } elseif ($temp >= 15 && $temp <= 25) {
            $impact *= 1.2;
        }

        return max(0.5, min(1.5, $impact));
    }
}
