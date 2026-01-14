<?php

declare(strict_types=1);

namespace App\Service\Chat;

use App\Entity\User;
use App\Entity\ChatRoom;
use App\Entity\Message;
use App\DTO\Chat\MessageDTO;
use App\Repository\ChatRoomRepository;
use App\Repository\MessageRepository;
use Doctrine\ORM\EntityManagerInterface;

/**
 * Service de gestion du chat
 * 
 * Gère les salons de chat et les messages en temps réel via Mercure
 */
class ChatService
{
    public function __construct(
        private readonly EntityManagerInterface $em,
        private readonly ChatRoomRepository $chatRoomRepo,
        private readonly MessageRepository $messageRepo
    ) {}

    /**
     * Récupère tous les salons publics
     * 
     * @return ChatRoom[]
     */
    public function getPublicRooms(): array
    {
        return $this->chatRoomRepo->findPublicRooms();
    }

    /**
     * Récupère les messages récents d'un salon
     * 
     * @param ChatRoom $chatRoom
     * @param int $limit
     * @return Message[]
     */
    public function getRecentMessages(ChatRoom $chatRoom, int $limit = 50): array
    {
        return $this->messageRepo->findRecentMessages($chatRoom, $limit);
    }

    /**
     * Envoie un message dans un salon
     * 
     * @param User $user
     * @param ChatRoom $chatRoom
     * @param MessageDTO $messageDTO
     * @return Message
     */
    public function sendMessage(User $user, ChatRoom $chatRoom, MessageDTO $messageDTO): Message
    {
        $message = new Message();
        $message->setUser($user);
        $message->setChatRoom($chatRoom);
        $message->setContenu($messageDTO->contenu);

        $this->em->persist($message);
        $this->em->flush();

        // TODO: Publier le message via Mercure pour la mise à jour en temps réel

        return $message;
    }

    /**
     * Crée un nouveau salon de chat
     * 
     * @param string $nom
     * @param bool $isPublic
     * @return ChatRoom
     */
    public function createRoom(string $nom, bool $isPublic = true): ChatRoom
    {
        $room = new ChatRoom();
        $room->setNom($nom);
        $room->setIsPublic($isPublic);

        $this->em->persist($room);
        $this->em->flush();

        return $room;
    }
}
