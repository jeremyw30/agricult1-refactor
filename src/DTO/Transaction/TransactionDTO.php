<?php

declare(strict_types=1);

namespace App\DTO\Transaction;

use Symfony\Component\Validator\Constraints as Assert;

/**
 * DTO pour une transaction
 */
class TransactionDTO
{
    #[Assert\NotBlank(message: 'Le type de transaction est obligatoire')]
    public string $type = '';

    #[Assert\NotBlank(message: 'Le montant est obligatoire')]
    #[Assert\Positive(message: 'Le montant doit être positif')]
    public float $montant = 0;

    public ?string $description = null;

    public function __construct(
        string $type = '',
        float $montant = 0,
        ?string $description = null
    ) {
        $this->type = $type;
        $this->montant = $montant;
        $this->description = $description;
    }
}
