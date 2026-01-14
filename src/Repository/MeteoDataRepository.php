<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\MeteoData;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les données météorologiques
 * 
 * @extends ServiceEntityRepository<MeteoData>
 */
class MeteoDataRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MeteoData::class);
    }

    /**
     * Récupère les données météo du jour
     * 
     * @return MeteoData|null
     */
    public function findToday(): ?MeteoData
    {
        $today = new \DateTimeImmutable('today');
        
        return $this->createQueryBuilder('m')
            ->where('m.date = :today')
            ->setParameter('today', $today)
            ->getQuery()
            ->getOneOrNullResult();
    }

    /**
     * Récupère l'historique météo sur N jours
     * 
     * @param int $days
     * @return MeteoData[]
     */
    public function findLastDays(int $days = 7): array
    {
        return $this->createQueryBuilder('m')
            ->orderBy('m.date', 'DESC')
            ->setMaxResults($days)
            ->getQuery()
            ->getResult();
    }
}
