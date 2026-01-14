<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserParcelleRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité représentant une parcelle agricole appartenant à un utilisateur
 * 
 * Une parcelle peut être cultivée et produit des récoltes
 */
#[ORM\Entity(repositoryClass: UserParcelleRepository::class)]
#[ORM\Table(name: 'user_parcelle')]
class UserParcelle
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'parcelles')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $superficie = '0.00';

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $typesSol = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $cultureActuelle = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $datePlantation = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateRecolte = null;

    #[ORM\Column]
    private bool $active = true;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

    public function getSuperficie(): string
    {
        return $this->superficie;
    }

    public function setSuperficie(string $superficie): static
    {
        $this->superficie = $superficie;

        return $this;
    }

    public function getTypesSol(): ?string
    {
        return $this->typesSol;
    }

    public function setTypesSol(?string $typesSol): static
    {
        $this->typesSol = $typesSol;

        return $this;
    }

    public function getCultureActuelle(): ?string
    {
        return $this->cultureActuelle;
    }

    public function setCultureActuelle(?string $cultureActuelle): static
    {
        $this->cultureActuelle = $cultureActuelle;

        return $this;
    }

    public function getDatePlantation(): ?\DateTimeImmutable
    {
        return $this->datePlantation;
    }

    public function setDatePlantation(?\DateTimeImmutable $datePlantation): static
    {
        $this->datePlantation = $datePlantation;

        return $this;
    }

    public function getDateRecolte(): ?\DateTimeImmutable
    {
        return $this->dateRecolte;
    }

    public function setDateRecolte(?\DateTimeImmutable $dateRecolte): static
    {
        $this->dateRecolte = $dateRecolte;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->active;
    }

    public function setActive(bool $active): static
    {
        $this->active = $active;

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
