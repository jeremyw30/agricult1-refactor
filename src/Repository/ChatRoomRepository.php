<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\ChatRoom;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * Repository pour les salons de chat
 * 
 * @extends ServiceEntityRepository<ChatRoom>
 */
class ChatRoomRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ChatRoom::class);
    }

    /**
     * Récupère tous les salons publics
     * 
     * @return ChatRoom[]
     */
    public function findPublicRooms(): array
    {
        return $this->createQueryBuilder('c')
            ->where('c.isPublic = :public')
            ->setParameter('public', true)
            ->orderBy('c.nom', 'ASC')
            ->getQuery()
            ->getResult();
    }
}
