<?php

declare(strict_types=1);

namespace App\Service\Auth;

use App\Entity\User;
use App\DTO\User\UserRegistrationDTO;
use App\Repository\UserRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

/**
 * Service d'authentification
 * 
 * Gère l'inscription, la connexion et les opérations liées à l'authentification
 */
class AuthenticationService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly UserRepository $userRepo,
        private readonly UserPasswordHasherInterface $passwordHasher
    ) {}

    /**
     * Inscrit un nouvel utilisateur
     * 
     * @param UserRegistrationDTO $dto
     * @return User
     */
    public function register(UserRegistrationDTO $dto): User
    {
        $user = new User();
        $user->setUsername($dto->username);
        $user->setEmail($dto->email);
        
        // Hasher le mot de passe
        $hashedPassword = $this->passwordHasher->hashPassword($user, $dto->password);
        $user->setPassword($hashedPassword);
        
        // Balance de départ
        $user->setBalance('1000.00');
        
        $this->em->persist($user);
        $this->em->flush();

        return $user;
    }

    /**
     * Met à jour la date de dernière connexion
     * 
     * @param User $user
     */
    public function updateLastLogin(User $user): void
    {
        $user->setLastLoginAt(new \DateTimeImmutable());
        $this->em->persist($user);
        $this->em->flush();
    }

    /**
     * Vérifie si un email existe déjà
     * 
     * @param string $email
     * @return bool
     */
    public function emailExists(string $email): bool
    {
        return $this->userRepo->findOneBy(['email' => $email]) !== null;
    }

    /**
     * Vérifie si un nom d'utilisateur existe déjà
     * 
     * @param string $username
     * @return bool
     */
    public function usernameExists(string $username): bool
    {
        return $this->userRepo->findOneBy(['username' => $username]) !== null;
    }
}
