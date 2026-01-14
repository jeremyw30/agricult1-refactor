<?php

declare(strict_types=1);

namespace App;

use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

/**
 * Kernel principal de l'application Agri-Cult
 * 
 * Gère le chargement des bundles et la configuration
 */
class Kernel extends BaseKernel
{
    use MicroKernelTrait;
}
