<?php

declare(strict_types=1);

namespace App\Exception;

/**
 * Exception de base pour les erreurs du jeu
 * 
 * Toutes les exceptions métier devraient étendre cette classe
 */
class GameException extends \RuntimeException
{
}
