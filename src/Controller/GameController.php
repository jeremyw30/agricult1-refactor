<?php

declare(strict_types=1);

namespace App\Controller;

use App\Service\Game\GameStateService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur principal du jeu
 * 
 * Gère la page d'accueil et le tableau de bord
 */
class GameController extends AbstractController
{
    public function __construct(
        private readonly GameStateService $gameStateService
    ) {}

    /**
     * Page d'accueil du jeu
     */
    #[Route('/', name: 'app_home')]
    public function home(): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        return $this->render('game/home.html.twig');
    }

    /**
     * Tableau de bord du joueur
     */
    #[Route('/dashboard', name: 'app_dashboard')]
    #[IsGranted('ROLE_USER')]
    public function dashboard(): Response
    {
        $gameState = $this->gameStateService->getGameState($this->getUser());

        return $this->render('game/dashboard.html.twig', [
            'gameState' => $gameState,
        ]);
    }
}
