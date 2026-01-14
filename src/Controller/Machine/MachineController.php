<?php

declare(strict_types=1);

namespace App\Controller\Machine;

use App\Service\Machine\MachineService;
use App\Service\Marche\MarketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur de gestion des machines
 */
#[Route('/machine', name: 'machine_')]
#[IsGranted('ROLE_USER')]
class MachineController extends AbstractController
{
    public function __construct(
        private readonly MachineService $machineService,
        private readonly MarketService $marketService
    ) {}

    /**
     * Liste toutes les machines de l'utilisateur
     */
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $machines = $this->machineService->getUserMachines($this->getUser());

        return $this->render('machine/list.html.twig', [
            'machines' => $machines,
        ]);
    }

    /**
     * Achète une nouvelle machine
     */
    #[Route('/acheter', name: 'buy', methods: ['POST'])]
    public function buy(Request $request): Response
    {
        $type = $request->request->get('type', '');
        $nom = $request->request->get('nom', '');

        if (empty($type) || empty($nom)) {
            $this->addFlash('error', 'Informations manquantes.');
            return $this->redirectToRoute('machine_list');
        }

        $price = $this->marketService->getMachinePrice($type);

        try {
            $this->machineService->buyMachine($this->getUser(), $type, $nom, $price);
            $this->addFlash('success', sprintf('Machine %s achetée avec succès !', $nom));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('machine_list');
    }

    /**
     * Répare une machine
     */
    #[Route('/{id}/reparer', name: 'repair', methods: ['POST'])]
    public function repair(int $id): Response
    {
        $machines = $this->machineService->getUserMachines($this->getUser());
        $machine = null;

        foreach ($machines as $m) {
            if ($m->getId() === $id) {
                $machine = $m;
                break;
            }
        }

        if (!$machine) {
            $this->addFlash('error', 'Machine non trouvée.');
            return $this->redirectToRoute('machine_list');
        }

        try {
            $this->machineService->repairMachine($machine, $this->getUser());
            $this->addFlash('success', 'Machine réparée avec succès !');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('machine_list');
    }
}
