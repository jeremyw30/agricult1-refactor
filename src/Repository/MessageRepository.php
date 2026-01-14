<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Message;
use App\Entity\ChatRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les messages de chat
 * 
 * @extends ServiceEntityRepository<Message>
 */
class MessageRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Message::class);
    }

    /**
     * Récupère les derniers messages d'un salon
     * 
     * @param ChatRoom $chatRoom
     * @param int $limit
     * @return Message[]
     */
    public function findRecentMessages(ChatRoom $chatRoom, int $limit = 50): array
    {
        return $this->createQueryBuilder('m')
            ->join('m.user', 'u')
            ->addSelect('u')
            ->where('m.chatRoom = :room')
            ->setParameter('room', $chatRoom)
            ->orderBy('m.createdAt', 'DESC')
            ->setMaxResults($limit)
            ->getQuery()
            ->getResult();
    }
}
