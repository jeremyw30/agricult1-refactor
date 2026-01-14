<?php

declare(strict_types=1);

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO pour l'inscription d'un nouvel utilisateur
 */
class UserRegistrationDTO
{
    #[Assert\NotBlank(message: 'Le nom d\'utilisateur est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le nom d\'utilisateur doit contenir au moins {{ limit }} caractères',
        maxMessage: 'Le nom d\'utilisateur ne peut pas dépasser {{ limit }} caractères'
    )]
    public string $username = '';

    #[Assert\NotBlank(message: 'L\'email est obligatoire')]
    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide')]
    public string $email = '';

    #[Assert\NotBlank(message: 'Le mot de passe est obligatoire')]
    #[Assert\Length(
        min: 6,
        minMessage: 'Le mot de passe doit contenir au moins {{ limit }} caractères'
    )]
    public string $password = '';

    #[Assert\NotBlank(message: 'Veuillez confirmer le mot de passe')]
    #[Assert\EqualTo(
        propertyPath: 'password',
        message: 'Les mots de passe ne correspondent pas'
    )]
    public string $confirmPassword = '';
}
