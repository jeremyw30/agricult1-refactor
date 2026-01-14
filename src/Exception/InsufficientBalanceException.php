<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Exception levée quand le solde est insuffisant pour une transaction
 */
class InsufficientBalanceException extends GameException
{
}
