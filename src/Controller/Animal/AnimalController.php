<?php

declare(strict_types=1);

namespace App\Controller\Animal;

use App\Service\Animal\AnimalService;
use App\Service\Marche\MarketService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;

/**
 * Contrôleur de gestion des animaux
 */
#[Route('/animal', name: 'animal_')]
#[IsGranted('ROLE_USER')]
class AnimalController extends AbstractController
{
    public function __construct(
        private readonly AnimalService $animalService,
        private readonly MarketService $marketService
    ) {}

    /**
     * Liste tous les animaux de l'utilisateur
     */
    #[Route('/', name: 'list', methods: ['GET'])]
    public function list(): Response
    {
        $animals = $this->animalService->getUserAnimals($this->getUser());

        return $this->render('animal/list.html.twig', [
            'animals' => $animals,
        ]);
    }

    /**
     * Achète un nouvel animal
     */
    #[Route('/acheter', name: 'buy', methods: ['POST'])]
    public function buy(Request $request): Response
    {
        $type = $request->request->get('type', '');
        
        if (empty($type)) {
            $this->addFlash('error', 'Type d\'animal invalide.');
            return $this->redirectToRoute('animal_list');
        }

        $price = $this->marketService->getAnimalPrice($type);

        try {
            $this->animalService->buyAnimal($this->getUser(), $type, $price);
            $this->addFlash('success', sprintf('Animal %s acheté avec succès !', $type));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('animal_list');
    }

    /**
     * Nourrit un animal
     */
    #[Route('/{id}/nourrir', name: 'feed', methods: ['POST'])]
    public function feed(int $id): Response
    {
        $animals = $this->animalService->getUserAnimals($this->getUser());
        $animal = null;

        foreach ($animals as $a) {
            if ($a->getId() === $id) {
                $animal = $a;
                break;
            }
        }

        if (!$animal) {
            $this->addFlash('error', 'Animal non trouvé.');
            return $this->redirectToRoute('animal_list');
        }

        try {
            $this->animalService->feedAnimal($animal);
            $this->addFlash('success', 'Animal nourri avec succès !');
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('animal_list');
    }

    /**
     * Fait produire un animal
     */
    #[Route('/{id}/produire', name: 'produce', methods: ['POST'])]
    public function produce(int $id): Response
    {
        $animals = $this->animalService->getUserAnimals($this->getUser());
        $animal = null;

        foreach ($animals as $a) {
            if ($a->getId() === $id) {
                $animal = $a;
                break;
            }
        }

        if (!$animal) {
            $this->addFlash('error', 'Animal non trouvé.');
            return $this->redirectToRoute('animal_list');
        }

        try {
            $montant = $this->animalService->produceResource($animal, $this->getUser());
            $this->addFlash('success', sprintf('Production réalisée ! Vous avez gagné %.2f €', $montant));
        } catch (\Exception $e) {
            $this->addFlash('error', $e->getMessage());
        }

        return $this->redirectToRoute('animal_list');
    }
}
