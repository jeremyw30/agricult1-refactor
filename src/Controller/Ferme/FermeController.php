<?php

declare(strict_types=1);

namespace App\Controller\Ferme;

use App\Service\Ferme\FermeService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur de gestion de la ferme
 */
#[Route('/ferme', name: 'ferme_')]
#[IsGranted('ROLE_USER')]
class FermeController extends AbstractController
{
    public function __construct(
        private readonly FermeService $fermeService
    ) {}

    /**
     * Vue d'ensemble de la ferme
     */
    #[Route('/', name: 'overview', methods: ['GET'])]
    public function overview(): Response
    {
        $fermeInfo = $this->fermeService->getFermeInfo($this->getUser());

        return $this->render('ferme/overview.html.twig', [
            'ferme' => $fermeInfo,
        ]);
    }
}
