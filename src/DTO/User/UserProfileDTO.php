<?php

declare(strict_types=1);

namespace App\DTO\User;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO pour la mise à jour du profil utilisateur
 */
class UserProfileDTO
{
    #[Assert\NotBlank(message: 'Le nom d\'utilisateur est obligatoire')]
    #[Assert\Length(
        min: 3,
        max: 100,
        minMessage: 'Le nom d\'utilisateur doit contenir au moins {{ limit }} caractères'
    )]
    public string $username = '';

    #[Assert\Email(message: 'L\'email {{ value }} n\'est pas valide')]
    public ?string $email = null;

    public function __construct(string $username = '', ?string $email = null)
    {
        $this->username = $username;
        $this->email = $email;
    }
}
