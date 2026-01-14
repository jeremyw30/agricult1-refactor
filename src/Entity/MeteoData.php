<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\MeteoDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité représentant les données météorologiques
 * 
 * La météo influence les cultures et les rendements
 */
#[ORM\Entity(repositoryClass: MeteoDataRepository::class)]
#[ORM\Table(name: 'meteo_data')]
class MeteoData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATE_IMMUTABLE)]
    private ?\DateTimeImmutable $date = null;

    #[ORM\Column(length: 50)]
    private ?string $condition = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private string $temperature = '0.00';

    #[ORM\Column(type: Types::SMALLINT)]
    private int $humidite = 0;

    #[ORM\Column(type: Types::DECIMAL, precision: 5, scale: 2)]
    private string $precipitation = '0.00';

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
        $this->date = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDate(): ?\DateTimeImmutable
    {
        return $this->date;
    }

    public function setDate(\DateTimeImmutable $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCondition(): ?string
    {
        return $this->condition;
    }

    public function setCondition(string $condition): static
    {
        $this->condition = $condition;

        return $this;
    }

    public function getTemperature(): string
    {
        return $this->temperature;
    }

    public function setTemperature(string $temperature): static
    {
        $this->temperature = $temperature;

        return $this;
    }

    public function getHumidite(): int
    {
        return $this->humidite;
    }

    public function setHumidite(int $humidite): static
    {
        $this->humidite = $humidite;

        return $this;
    }

    public function getPrecipitation(): string
    {
        return $this->precipitation;
    }

    public function setPrecipitation(string $precipitation): static
    {
        $this->precipitation = $precipitation;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): static
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
