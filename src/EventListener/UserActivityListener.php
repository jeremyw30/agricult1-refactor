<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PreUpdateEventArgs;
use Doctrine\ORM\Events;

/**
 * Listener pour suivre les activités utilisateur
 * 
 * Met à jour automatiquement les dates de modification
 */
#[AsDoctrineListener(event: Events::preUpdate, priority: 500, connection: 'default')]
class UserActivityListener
{
    /**
     * Met à jour automatiquement la date de dernière modification
     */
    public function preUpdate(PreUpdateEventArgs $event): void
    {
        $entity = $event->getObject();

        if ($entity instanceof User) {
            $entity->setUpdatedAt(new \DateTimeImmutable());
        }
    }
}
