<?php

declare(strict_types=1);

namespace App\Controller\Auth;

use App\DTO\User\UserRegistrationDTO;
use App\Service\Auth\AuthenticationService;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Validator\ValidatorInterface;

/**
 * Contrôleur d'inscription
 */
class RegisterController extends AbstractController
{
    public function __construct(
        private readonly AuthenticationService $authService,
        private readonly ValidatorInterface $validator
    ) {}

    /**
     * Page d'inscription
     */
    #[Route('/register', name: 'app_register')]
    public function register(Request $request): Response
    {
        // Rediriger si déjà connecté
        if ($this->getUser()) {
            return $this->redirectToRoute('app_dashboard');
        }

        if ($request->isMethod('POST')) {
            $dto = new UserRegistrationDTO();
            $dto->username = $request->request->get('username', '');
            $dto->email = $request->request->get('email', '');
            $dto->password = $request->request->get('password', '');
            $dto->confirmPassword = $request->request->get('confirm_password', '');

            $errors = $this->validator->validate($dto);

            if (count($errors) === 0) {
                // Vérifier si l'email existe déjà
                if ($this->authService->emailExists($dto->email)) {
                    $this->addFlash('error', 'Cet email est déjà utilisé.');
                } elseif ($this->authService->usernameExists($dto->username)) {
                    $this->addFlash('error', 'Ce nom d\'utilisateur est déjà pris.');
                } else {
                    try {
                        $this->authService->register($dto);
                        $this->addFlash('success', 'Inscription réussie ! Vous pouvez maintenant vous connecter.');
                        return $this->redirectToRoute('app_login');
                    } catch (\Exception $e) {
                        $this->addFlash('error', 'Une erreur est survenue lors de l\'inscription.');
                    }
                }
            } else {
                foreach ($errors as $error) {
                    $this->addFlash('error', $error->getMessage());
                }
            }
        }

        return $this->render('auth/register.html.twig');
    }
}
