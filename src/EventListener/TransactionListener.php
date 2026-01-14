<?php

declare(strict_types=1);

namespace App\EventListener;

use App\Entity\Transaction;
use Doctrine\Bundle\DoctrineBundle\Attribute\AsDoctrineListener;
use Doctrine\ORM\Event\PostPersistEventArgs;
use Doctrine\ORM\Events;
use Psr\Log\LoggerInterface;

/**
 * Listener pour les transactions
 * 
 * Enregistre les transactions dans les logs pour audit
 */
#[AsDoctrineListener(event: Events::postPersist, priority: 500, connection: 'default')]
class TransactionListener
{
    public function __construct(
        private readonly LoggerInterface $logger
    ) {}

    /**
     * Enregistre la transaction dans les logs
     */
    public function postPersist(PostPersistEventArgs $event): void
    {
        $entity = $event->getObject();

        if ($entity instanceof Transaction) {
            $this->logger->info('Nouvelle transaction enregistrée', [
                'id' => $entity->getId(),
                'user_id' => $entity->getUser()->getId(),
                'type' => $entity->getType(),
                'montant' => $entity->getMontant(),
                'description' => $entity->getDescription(),
            ]);
        }
    }
}
