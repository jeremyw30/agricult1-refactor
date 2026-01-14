<?php

declare(strict_types=1);

namespace App\DTO\Chat;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO pour un message de chat
 */
class MessageDTO
{
    #[Assert\NotBlank(message: 'Le message ne peut pas être vide')]
    #[Assert\Length(
        max: 1000,
        maxMessage: 'Le message ne peut pas dépasser {{ limit }} caractères'
    )]
    public string $contenu = '';

    public int $chatRoomId = 0;

    public function __construct(string $contenu = '', int $chatRoomId = 0)
    {
        $this->contenu = $contenu;
        $this->chatRoomId = $chatRoomId;
    }
}
