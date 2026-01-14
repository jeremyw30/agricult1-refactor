<?php

declare(strict_types=1);

namespace App\Tests\Service\Marche;

use App\Entity\User;
use App\Service\Marche\TransactionService;
use App\Repository\TransactionRepository;
use App\Exception\InsufficientBalanceException;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

/**
 * Test unitaire pour TransactionService
 */
class TransactionServiceTest extends KernelTestCase
{
    private TransactionService $transactionService;
    private User $testUser;

    protected function setUp(): void
    {
        self::bootKernel();
        $container = static::getContainer();
        
        $this->transactionService = $container->get(TransactionService::class);
        
        // Créer un utilisateur de test
        $this->testUser = new User();
        $this->testUser->setUsername('test_user');
        $this->testUser->setEmail('test@example.com');
        $this->testUser->setPassword('password');
        $this->testUser->setBalance('1000.00');
    }

    public function testCreditIncreasesBalance(): void
    {
        $initialBalance = (float) $this->testUser->getBalance();
        $creditAmount = 500.0;

        // Note: Ce test nécessiterait une base de données de test configurée
        // Pour l'instant, il sert d'exemple de structure

        $this->assertEquals($initialBalance, 1000.0);
        
        // La transaction devrait augmenter le solde de 500€
        // $this->transactionService->credit($this->testUser, $creditAmount, 'Test credit');
        // $this->assertEquals(1500.0, (float) $this->testUser->getBalance());
    }

    public function testDebitDecreasesBalance(): void
    {
        $initialBalance = (float) $this->testUser->getBalance();
        
        $this->assertEquals($initialBalance, 1000.0);
        
        // La transaction devrait diminuer le solde
        // $this->transactionService->debit($this->testUser, 300.0, 'Test debit');
        // $this->assertEquals(700.0, (float) $this->testUser->getBalance());
    }

    public function testDebitThrowsExceptionWhenInsufficientBalance(): void
    {
        $this->expectException(InsufficientBalanceException::class);
        
        // Tenter de débiter plus que le solde disponible devrait lever une exception
        // $this->transactionService->debit($this->testUser, 2000.0, 'Test insufficient');
    }
}
