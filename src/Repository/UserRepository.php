<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\Security\Core\Exception\UnsupportedUserException;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\PasswordUpgraderInterface;

/**
 * Repository pour l'entité User
 * 
 * Contient les requêtes DQL optimisées pour les utilisateurs
 * 
 * @extends ServiceEntityRepository<User>
 */
class UserRepository extends ServiceEntityRepository implements PasswordUpgraderInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, User::class);
    }

    /**
     * Used to upgrade (rehash) the user's password automatically over time.
     */
    public function upgradePassword(PasswordAuthenticatedUserInterface $user, string $newHashedPassword): void
    {
        if (!$user instanceof User) {
            throw new UnsupportedUserException(sprintf('Instances of "%s" are not supported.', $user::class));
        }

        $user->setPassword($newHashedPassword);
        $this->getEntityManager()->persist($user);
        $this->getEntityManager()->flush();
    }

    /**
     * Trouve les utilisateurs actifs avec leurs ressources
     * 
     * @return User[]
     */
    public function findActiveUsersWithResources(): array
    {
        return $this->createQueryBuilder('u')
            ->leftJoin('u.parcelles', 'p')
            ->leftJoin('u.animals', 'a')
            ->leftJoin('u.machines', 'm')
            ->leftJoin('u.batiments', 'b')
            ->addSelect('p', 'a', 'm', 'b')
            ->where('u.isActive = :active')
            ->setParameter('active', true)
            ->getQuery()
            ->getResult();
    }

    /**
     * Recherche un utilisateur par email ou nom d'utilisateur
     * 
     * @param string $searchTerm Terme de recherche
     * @return User[]
     */
    public function searchUsers(string $searchTerm): array
    {
        return $this->createQueryBuilder('u')
            ->where('u.email LIKE :term OR u.username LIKE :term')
            ->setParameter('term', '%' . $searchTerm . '%')
            ->orderBy('u.username', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
