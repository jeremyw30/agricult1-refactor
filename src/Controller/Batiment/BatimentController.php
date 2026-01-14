<?php

declare(strict_types=1);

namespace App\Controller\Batiment;

use App\Service\Batiment\BatimentService;
use App\Service\Marche\MarketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur de gestion des bâtiments
 */
#[Route('/batiment', name: 'batiment_')]
#[IsGranted('ROLE_USER')]
class BatimentController extends AbstractController
{
    public function __construct(
        private readonly BatimentService $batimentService,
        private readonly MarketService $marketService
    ) {}

    /**
     * Liste tous les bâtiments de l'utilisateur
     */
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $batiments = $this->batimentService->getUserBatiments($this->getUser());

        return $this->render('batiment/list.html.twig', [
            'batiments' => $batiments,
        ]);
    }

    /**
     * Construit un nouveau bâtiment
     */
    #[Route('/construire', name: 'build', methods: ['POST'])]
    public function build(Request $request): Response
    {
        $type = $request->request->get('type', '');
        $nom = $request->request->get('nom', '');

        if (empty($type) || empty($nom)) {
            $this->addFlash('error', 'Informations manquantes.');
            return $this->redirectToRoute('batiment_list');
        }

        $price = $this->marketService->getBatimentPrice($type);

        try {
            $this->batimentService->buildBatiment($this->getUser(), $type, $nom, $price);
            $this->addFlash('success', sprintf('Bâtiment %s construit avec succès !', $nom));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('batiment_list');
    }

    /**
     * Améliore un bâtiment
     */
    #[Route('/{id}/ameliorer', name: 'upgrade', methods: ['POST'])]
    public function upgrade(int $id): Response
    {
        $batiments = $this->batimentService->getUserBatiments($this->getUser());
        $batiment = null;

        foreach ($batiments as $b) {
            if ($b->getId() === $id) {
                $batiment = $b;
                break;
            }
        }

        if (!$batiment) {
            $this->addFlash('error', 'Bâtiment non trouvé.');
            return $this->redirectToRoute('batiment_list');
        }

        $coutAmelioration = $this->marketService->getUpgradeCost($batiment->getNiveau());

        try {
            $this->batimentService->upgradeBatiment($batiment, $this->getUser(), $coutAmelioration);
            $this->addFlash('success', sprintf('Bâtiment amélioré au niveau %d !', $batiment->getNiveau()));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('batiment_list');
    }
}
