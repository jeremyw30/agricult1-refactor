<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Entité représentant un utilisateur/joueur dans le jeu Agri-Cult
 * 
 * Gère l'authentification, le profil et les ressources du joueur
 */
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    private ?string $email = null;

    /**
     * @var list<string> Les rôles de l'utilisateur
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string Le mot de passe hashé
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 100)]
    private ?string $username = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 2)]
    private string $balance = '1000.00';

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $updatedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $lastLoginAt = null;

    #[ORM\Column]
    private bool $isActive = true;

    /**
     * @var Collection<int, UserParcelle>
     */
    #[ORM\OneToMany(targetEntity: UserParcelle::class, mappedBy: 'idUser')]
    private Collection $parcelles;

    /**
     * @var Collection<int, UserAnimal>
     */
    #[ORM\OneToMany(targetEntity: UserAnimal::class, mappedBy: 'idUser')]
    private Collection $animals;

    /**
     * @var Collection<int, UserMachine>
     */
    #[ORM\OneToMany(targetEntity: UserMachine::class, mappedBy: 'idUser')]
    private Collection $machines;

    /**
     * @var Collection<int, UserBatiment>
     */
    #[ORM\OneToMany(targetEntity: UserBatiment::class, mappedBy: 'idUser')]
    private Collection $batiments;

    public function __construct()
    {
        $this->parcelles = new ArrayCollection();
        $this->animals = new ArrayCollection();
        $this->machines = new ArrayCollection();
        $this->batiments = new ArrayCollection();
        $this->createdAt = new \DateTimeImmutable();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    /**
     * L'identifiant visuel utilisé pour l'utilisateur.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // garantit que chaque utilisateur a au moins ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // Si vous stockez des données sensibles temporaires, nettoyez-les ici
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getBalance(): string
    {
        return $this->balance;
    }

    public function setBalance(string $balance): static
    {
        $this->balance = $balance;

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

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(?\DateTimeImmutable $updatedAt): static
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getLastLoginAt(): ?\DateTimeImmutable
    {
        return $this->lastLoginAt;
    }

    public function setLastLoginAt(?\DateTimeImmutable $lastLoginAt): static
    {
        $this->lastLoginAt = $lastLoginAt;

        return $this;
    }

    public function isActive(): bool
    {
        return $this->isActive;
    }

    public function setIsActive(bool $isActive): static
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * @return Collection<int, UserParcelle>
     */
    public function getParcelles(): Collection
    {
        return $this->parcelles;
    }

    public function addParcelle(UserParcelle $parcelle): static
    {
        if (!$this->parcelles->contains($parcelle)) {
            $this->parcelles->add($parcelle);
            $parcelle->setIdUser($this);
        }

        return $this;
    }

    public function removeParcelle(UserParcelle $parcelle): static
    {
        if ($this->parcelles->removeElement($parcelle)) {
            if ($parcelle->getIdUser() === $this) {
                $parcelle->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserAnimal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(UserAnimal $animal): static
    {
        if (!$this->animals->contains($animal)) {
            $this->animals->add($animal);
            $animal->setIdUser($this);
        }

        return $this;
    }

    public function removeAnimal(UserAnimal $animal): static
    {
        if ($this->animals->removeElement($animal)) {
            if ($animal->getIdUser() === $this) {
                $animal->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserMachine>
     */
    public function getMachines(): Collection
    {
        return $this->machines;
    }

    public function addMachine(UserMachine $machine): static
    {
        if (!$this->machines->contains($machine)) {
            $this->machines->add($machine);
            $machine->setIdUser($this);
        }

        return $this;
    }

    public function removeMachine(UserMachine $machine): static
    {
        if ($this->machines->removeElement($machine)) {
            if ($machine->getIdUser() === $this) {
                $machine->setIdUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, UserBatiment>
     */
    public function getBatiments(): Collection
    {
        return $this->batiments;
    }

    public function addBatiment(UserBatiment $batiment): static
    {
        if (!$this->batiments->contains($batiment)) {
            $this->batiments->add($batiment);
            $batiment->setIdUser($this);
        }

        return $this;
    }

    public function removeBatiment(UserBatiment $batiment): static
    {
        if ($this->batiments->removeElement($batiment)) {
            if ($batiment->getIdUser() === $this) {
                $batiment->setIdUser(null);
            }
        }

        return $this;
    }
}
