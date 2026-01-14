<?php

declare(strict_types=1);

namespace App\Controller\Chat;

use App\DTO\Chat\MessageDTO;
use App\Service\Chat\ChatService;
use App\Repository\ChatRoomRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Contrôleur du chat de jeu
 */
#[Route('/chat', name: 'chat_')]
#[IsGranted('ROLE_USER')]
class GameChatController extends AbstractController
{
    public function __construct(
        private readonly ChatService $chatService,
        private readonly ChatRoomRepository $chatRoomRepo,
        private readonly ValidatorInterface $validator
    ) {}

    /**
     * Liste des salons de chat
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        $rooms = $this->chatService->getPublicRooms();

        return $this->render('chat/index.html.twig', [
            'rooms' => $rooms,
        ]);
    }

    /**
     * Affiche un salon de chat
     */
    #[Route('/room/{id}', name: 'room', methods: ['GET'])]
    public function room(int $id): Response
    {
        $room = $this->chatRoomRepo->find($id);

        if (!$room) {
            $this->addFlash('error', 'Salon non trouvé.');
            return $this->redirectToRoute('chat_index');
        }

        $messages = $this->chatService->getRecentMessages($room);

        return $this->render('chat/room.html.twig', [
            'room' => $room,
            'messages' => $messages,
        ]);
    }

    /**
     * Envoie un message
     */
    #[Route('/room/{id}/send', name: 'send_message', methods: ['POST'])]
    public function sendMessage(int $id, Request $request): Response
    {
        $room = $this->chatRoomRepo->find($id);

        if (!$room) {
            $this->addFlash('error', 'Salon non trouvé.');
            return $this->redirectToRoute('chat_index');
        }

        $contenu = $request->request->get('message', '');
        $dto = new MessageDTO($contenu, $id);

        $errors = $this->validator->validate($dto);

        if (count($errors) === 0) {
            try {
                $this->chatService->sendMessage($this->getUser(), $room, $dto);
                $this->addFlash('success', 'Message envoyé !');
            } catch (\Exception $e) {
                $this->addFlash('error', 'Erreur lors de l\'envoi du message.');
            }
        } else {
            foreach ($errors as $error) {
                $this->addFlash('error', $error->getMessage());
            }
        }

        return $this->redirectToRoute('chat_room', ['id' => $id]);
    }
}
