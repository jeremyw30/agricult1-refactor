<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserAnimalRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

/**
 * Entité représentant un animal appartenant à un utilisateur
 * 
 * Les animaux produisent des ressources (lait, œufs, laine, etc.)
 */
#[ORM\Entity(repositoryClass: UserAnimalRepository::class)]
#[ORM\Table(name: 'user_animal')]
class UserAnimal
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'animals')]
    #[ORM\JoinColumn(nullable: false)]
    private ?User $idUser = null;

    #[ORM\Column(length: 50)]
    private ?string $type = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $age = 0;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $sante = 100;

    #[ORM\Column(type: Types::SMALLINT)]
    private int $bonheur = 100;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $derniereProd = null;

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

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getAge(): int
    {
        return $this->age;
    }

    public function setAge(int $age): static
    {
        $this->age = $age;

        return $this;
    }

    public function getSante(): int
    {
        return $this->sante;
    }

    public function setSante(int $sante): static
    {
        $this->sante = $sante;

        return $this;
    }

    public function getBonheur(): int
    {
        return $this->bonheur;
    }

    public function setBonheur(int $bonheur): static
    {
        $this->bonheur = $bonheur;

        return $this;
    }

    public function getDerniereProd(): ?\DateTimeImmutable
    {
        return $this->derniereProd;
    }

    public function setDerniereProd(?\DateTimeImmutable $derniereProd): static
    {
        $this->derniereProd = $derniereProd;

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
