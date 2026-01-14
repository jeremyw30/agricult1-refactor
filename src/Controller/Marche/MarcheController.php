<?php

declare(strict_types=1);

namespace App\Controller\Marche;

use App\Service\Marche\TransactionService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur du marché
 */
#[Route('/marche', name: 'marche_')]
#[IsGranted('ROLE_USER')]
class MarcheController extends AbstractController
{
    public function __construct(
        private readonly TransactionService $transactionService
    ) {}

    /**
     * Vue du marché
     */
    #[Route('/', name: 'index', methods: ['GET'])]
    public function index(): Response
    {
        return $this->render('marche/index.html.twig');
    }

    /**
     * Historique des transactions
     */
    #[Route('/historique', name: 'history', methods: ['GET'])]
    public function history(): Response
    {
        $transactions = $this->transactionService->getHistory($this->getUser());

        return $this->render('marche/history.html.twig', [
            'transactions' => $transactions,
        ]);
    }
}
