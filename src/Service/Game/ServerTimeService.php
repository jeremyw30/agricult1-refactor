<?php

declare(strict_types=1);

namespace App\Service\Game;

/**
 * Service de gestion du temps serveur
 * 
 * Gère le temps de jeu et la synchronisation
 */
class ServerTimeService
{
    /**
     * Récupère l'heure actuelle du serveur
     * 
     * @return \DateTimeImmutable
     */
    public function getCurrentTime(): \DateTimeImmutable
    {
        return new \DateTimeImmutable();
    }

    /**
     * Calcule la durée écoulée entre deux dates
     * 
     * @param \DateTimeImmutable $start
     * @param \DateTimeImmutable|null $end
     * @return \DateInterval
     */
    public function getElapsedTime(\DateTimeImmutable $start, ?\DateTimeImmutable $end = null): \DateInterval
    {
        $end = $end ?? $this->getCurrentTime();
        return $start->diff($end);
    }

    /**
     * Vérifie si une date est dans le futur
     * 
     * @param \DateTimeImmutable $date
     * @return bool
     */
    public function isFuture(\DateTimeImmutable $date): bool
    {
        return $date > $this->getCurrentTime();
    }

    /**
     * Vérifie si une date est dans le passé
     * 
     * @param \DateTimeImmutable $date
     * @return bool
     */
    public function isPast(\DateTimeImmutable $date): bool
    {
        return $date < $this->getCurrentTime();
    }

    /**
     * Formate une date pour l'affichage
     * 
     * @param \DateTimeImmutable $date
     * @param string $format
     * @return string
     */
    public function format(\DateTimeImmutable $date, string $format = 'd/m/Y H:i:s'): string
    {
        return $date->format($format);
    }
}
