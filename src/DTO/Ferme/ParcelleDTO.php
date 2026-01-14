<?php

declare(strict_types=1);

namespace App\DTO\Ferme;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO pour l'achat/création d'une parcelle
 */
class ParcelleDTO
{
    #[Assert\NotBlank(message: 'La superficie est obligatoire')]
    #[Assert\Positive(message: 'La superficie doit être positive')]
    public float $superficie = 0;

    #[Assert\NotBlank(message: 'Le prix est obligatoire')]
    #[Assert\Positive(message: 'Le prix doit être positif')]
    public float $price = 0;

    public ?string $typesSol = null;

    public function __construct(
        float $superficie = 0,
        float $price = 0,
        ?string $typesSol = null
    ) {
        $this->superficie = $superficie;
        $this->price = $price;
        $this->typesSol = $typesSol;
    }
}
