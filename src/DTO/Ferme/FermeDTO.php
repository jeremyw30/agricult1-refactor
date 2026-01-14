<?php

declare(strict_types=1);

namespace App\DTO\Ferme;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO pour les informations de la ferme
 */
class FermeDTO
{
    #[Assert\NotBlank]
    public string $nom = '';

    public ?string $description = null;

    public float $superficieTotale = 0;

    public function __construct(
        string $nom = '',
        ?string $description = null,
        float $superficieTotale = 0
    ) {
        $this->nom = $nom;
        $this->description = $description;
        $this->superficieTotale = $superficieTotale;
    }
}
