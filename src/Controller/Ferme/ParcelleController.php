<?php

declare(strict_types=1);

namespace App\Controller\Ferme;

use App\DTO\Ferme\ParcelleDTO;
use App\Service\Ferme\ParcelleService;
use App\Service\Marche\MarketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur de gestion des parcelles
 */
#[Route('/parcelle', name: 'parcelle_')]
#[IsGranted('ROLE_USER')]
class ParcelleController extends AbstractController
{
    public function __construct(
        private readonly ParcelleService $parcelleService,
        private readonly MarketService $marketService
    ) {}

    /**
     * Liste toutes les parcelles de l'utilisateur connecté
     */
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $parcelles = $this->parcelleService->getUserParcelles($this->getUser());
        
        return $this->render('parcelle/list.html.twig', [
            'parcelles' => $parcelles,
        ]);
    }

    /**
     * Page d'achat d'une parcelle
     */
    #[Route('/acheter', name: 'buy_form', methods: ['GET'])]
    public function buyForm(): Response
    {
        return $this->render('parcelle/buy.html.twig');
    }

    /**
     * Achète une nouvelle parcelle
     */
    #[Route('/acheter', name: 'buy', methods: ['POST'])]
    public function buy(Request $request): Response
    {
        $superficie = (float) $request->request->get('superficie', 0);
        $typesSol = $request->request->get('types_sol');

        if ($superficie <= 0) {
            $this->addFlash('error', 'La superficie doit être positive.');
            return $this->redirectToRoute('parcelle_buy_form');
        }

        $price = $this->marketService->getParcellePrice($superficie);
        $dto = new ParcelleDTO($superficie, $price, $typesSol);

        try {
            $this->parcelleService->buyParcelle($this->getUser(), $dto);
            $this->addFlash('success', sprintf('Parcelle de %.2f ha achetée avec succès !', $superficie));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('parcelle_list');
    }

    /**
     * Cultive une parcelle
     */
    #[Route('/{id}/cultiver', name: 'cultivate', methods: ['POST'])]
    public function cultivate(int $id, Request $request): Response
    {
        $parcelles = $this->parcelleService->getUserParcelles($this->getUser());
        $parcelle = null;

        foreach ($parcelles as $p) {
            if ($p->getId() === $id) {
                $parcelle = $p;
                break;
            }
        }

        if (!$parcelle) {
            $this->addFlash('error', 'Parcelle non trouvée.');
            return $this->redirectToRoute('parcelle_list');
        }

        $cultureType = $request->request->get('culture_type', 'ble');

        try {
            $this->parcelleService->cultivate($parcelle, $cultureType);
            $this->addFlash('success', sprintf('Culture de %s plantée avec succès !', $cultureType));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('parcelle_list');
    }

    /**
     * Récolte une parcelle
     */
    #[Route('/{id}/recolter', name: 'harvest', methods: ['POST'])]
    public function harvest(int $id): Response
    {
        $parcelles = $this->parcelleService->getUserParcelles($this->getUser());
        $parcelle = null;

        foreach ($parcelles as $p) {
            if ($p->getId() === $id) {
                $parcelle = $p;
                break;
            }
        }

        if (!$parcelle) {
            $this->addFlash('error', 'Parcelle non trouvée.');
            return $this->redirectToRoute('parcelle_list');
        }

        try {
            $montant = $this->parcelleService->harvest($parcelle, $this->getUser());
            $this->addFlash('success', sprintf('Récolte effectuée ! Vous avez gagné %.2f €', $montant));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('parcelle_list');
    }
}
