# 📚 Documentation des Services

Cette documentation détaille tous les services métier de l'application Agri-Cult.

## Table des Matières

- [Services d'Authentification](#services-dauthentification)
- [Services de Ferme](#services-de-ferme)
- [Services d'Animaux](#services-danimaux)
- [Services de Machines](#services-de-machines)
- [Services de Bâtiments](#services-de-bâtiments)
- [Services de Marché](#services-de-marché)
- [Services de Chat](#services-de-chat)
- [Services de Météo](#services-de-météo)
- [Services de Jeu](#services-de-jeu)

---

## Services d'Authentification

### AuthenticationService

**Namespace** : `App\Service\Auth\AuthenticationService`

Gère l'inscription, la connexion et les opérations liées à l'authentification.

#### Méthodes

##### `register(UserRegistrationDTO $dto): User`

Inscrit un nouvel utilisateur.

**Paramètres** :
- `$dto` : DTO contenant les informations d'inscription

**Retour** : L'utilisateur créé

**Exemple** :
```php
$dto = new UserRegistrationDTO();
$dto->username = 'joueur1';
$dto->email = 'joueur1@example.com';
$dto->password = 'password';
$dto->confirmPassword = 'password';

$user = $authService->register($dto);
```

##### `emailExists(string $email): bool`

Vérifie si un email existe déjà.

##### `usernameExists(string $username): bool`

Vérifie si un nom d'utilisateur existe déjà.

---

## Services de Ferme

### ParcelleService

**Namespace** : `App\Service\Ferme\ParcelleService`

Gère l'achat, la vente et les activités sur les parcelles.

#### Méthodes

##### `getUserParcelles(User $user): array`

Récupère toutes les parcelles actives d'un utilisateur.

##### `buyParcelle(User $user, ParcelleDTO $dto): UserParcelle`

Achète une nouvelle parcelle.

**Exceptions** :
- `InsufficientBalanceException` : Si le solde est insuffisant

##### `cultivate(UserParcelle $parcelle, string $cultureType): void`

Cultive une parcelle avec un type de culture spécifique.

##### `harvest(UserParcelle $parcelle, User $user): float`

Récolte une parcelle et crédite le compte de l'utilisateur.

**Retour** : Montant gagné

**Exceptions** :
- `RuntimeException` : Si la culture n'est pas prête

### FermeService

**Namespace** : `App\Service\Ferme\FermeService`

Gère les informations globales de la ferme.

#### Méthodes

##### `getFermeInfo(User $user): FermeDTO`

Récupère les informations de la ferme d'un utilisateur.

---

## Services d'Animaux

### AnimalService

**Namespace** : `App\Service\Animal\AnimalService`

Gère l'achat, la vente et le soin des animaux.

#### Méthodes

##### `getUserAnimals(User $user): array`

Récupère tous les animaux actifs d'un utilisateur.

##### `buyAnimal(User $user, string $type, float $price): UserAnimal`

Achète un nouvel animal.

**Exceptions** :
- `InsufficientBalanceException` : Si le solde est insuffisant

##### `feedAnimal(UserAnimal $animal): void`

Nourrit un animal pour améliorer sa santé et son bonheur.

##### `produceResource(UserAnimal $animal, User $user): float`

Fait produire une ressource à un animal.

**Retour** : Montant de la production

**Exceptions** :
- `RuntimeException` : Si l'animal a déjà produit aujourd'hui

---

## Services de Machines

### MachineService

**Namespace** : `App\Service\Machine\MachineService`

Gère l'achat, l'entretien et l'utilisation des machines.

#### Méthodes

##### `getUserMachines(User $user): array`

Récupère toutes les machines actives d'un utilisateur.

##### `buyMachine(User $user, string $type, string $nom, float $price): UserMachine`

Achète une nouvelle machine.

##### `useMachine(UserMachine $machine): void`

Utilise une machine (diminue son état).

##### `repairMachine(UserMachine $machine, User $user, float $coutReparation = 50.0): void`

Répare une machine.

---

## Services de Bâtiments

### BatimentService

**Namespace** : `App\Service\Batiment\BatimentService`

Gère la construction et l'amélioration des bâtiments.

#### Méthodes

##### `getUserBatiments(User $user): array`

Récupère tous les bâtiments actifs d'un utilisateur.

##### `buildBatiment(User $user, string $type, string $nom, float $price): UserBatiment`

Construit un nouveau bâtiment.

##### `upgradeBatiment(UserBatiment $batiment, User $user, float $coutAmelioration): void`

Améliore un bâtiment (augmente son niveau).

---

## Services de Marché

### TransactionService

**Namespace** : `App\Service\Marche\TransactionService`

Gère les débits, crédits et l'historique des transactions.

#### Méthodes

##### `debit(User $user, float $montant, string $description = ''): Transaction`

Débite le compte d'un utilisateur.

**Exceptions** :
- `InsufficientBalanceException` : Si le solde est insuffisant

##### `credit(User $user, float $montant, string $description = ''): Transaction`

Crédite le compte d'un utilisateur.

##### `getHistory(User $user, int $limit = 50): array`

Récupère l'historique des transactions.

### MarketService

**Namespace** : `App\Service\Marche\MarketService`

Gère les prix, les offres et les échanges.

#### Méthodes

##### `getParcellePrice(float $superficie): float`

Calcule le prix d'achat d'une parcelle.

##### `getAnimalPrice(string $type): float`

Récupère le prix d'un animal.

##### `getMachinePrice(string $type): float`

Récupère le prix d'une machine.

##### `getBatimentPrice(string $type): float`

Récupère le prix d'un bâtiment.

##### `getUpgradeCost(int $niveauActuel): float`

Calcule le coût d'amélioration d'un bâtiment.

---

## Services de Chat

### ChatService

**Namespace** : `App\Service\Chat\ChatService`

Gère les salons de chat et les messages en temps réel.

#### Méthodes

##### `getPublicRooms(): array`

Récupère tous les salons publics.

##### `getRecentMessages(ChatRoom $chatRoom, int $limit = 50): array`

Récupère les messages récents d'un salon.

##### `sendMessage(User $user, ChatRoom $chatRoom, MessageDTO $messageDTO): Message`

Envoie un message dans un salon.

##### `createRoom(string $nom, bool $isPublic = true): ChatRoom`

Crée un nouveau salon de chat.

---

## Services de Météo

### MeteoService

**Namespace** : `App\Service\Meteo\MeteoService`

Génère et gère les données météorologiques.

#### Méthodes

##### `getToday(): ?MeteoData`

Récupère la météo du jour.

##### `getHistory(int $days = 7): array`

Récupère l'historique météo sur N jours.

##### `generateToday(): MeteoData`

Génère une nouvelle météo aléatoire pour le jour.

##### `getCultureImpact(MeteoData $meteo): float`

Calcule l'impact de la météo sur les cultures.

**Retour** : Coefficient multiplicateur (0.5 à 1.5)

---

## Services de Jeu

### GameStateService

**Namespace** : `App\Service\Game\GameStateService`

Centralise les informations sur l'état global du jeu.

#### Méthodes

##### `getGameState(User $user): array`

Récupère l'état complet du jeu pour un utilisateur.

**Retour** : Tableau contenant :
- Informations utilisateur
- Informations ferme
- Animaux
- Machines
- Bâtiments
- Météo

##### `canPerformAction(User $user, string $action, float $cost = 0): bool`

Vérifie si l'utilisateur peut effectuer une action.

### ServerTimeService

**Namespace** : `App\Service\Game\ServerTimeService`

Gère le temps serveur et la synchronisation.

#### Méthodes

##### `getCurrentTime(): \DateTimeImmutable`

Récupère l'heure actuelle du serveur.

##### `getElapsedTime(\DateTimeImmutable $start, ?\DateTimeImmutable $end = null): \DateInterval`

Calcule la durée écoulée entre deux dates.

##### `isFuture(\DateTimeImmutable $date): bool`

Vérifie si une date est dans le futur.

##### `isPast(\DateTimeImmutable $date): bool`

Vérifie si une date est dans le passé.

---

## Exemples d'Utilisation

### Acheter une Parcelle

```php
// Dans un controller
public function buyParcelle(Request $request): Response
{
    $superficie = (float) $request->request->get('superficie');
    $price = $this->marketService->getParcellePrice($superficie);
    
    $dto = new ParcelleDTO($superficie, $price);
    
    try {
        $parcelle = $this->parcelleService->buyParcelle(
            $this->getUser(),
            $dto
        );
        $this->addFlash('success', 'Parcelle achetée !');
    } catch (InsufficientBalanceException $e) {
        $this->addFlash('error', $e->getMessage());
    }
    
    return $this->redirectToRoute('parcelle_list');
}
```

### Nourrir un Animal

```php
try {
    $this->animalService->feedAnimal($animal);
    $this->addFlash('success', 'Animal nourri !');
} catch (\Exception $e) {
    $this->addFlash('error', $e->getMessage());
}
```

---

Pour plus d'informations, consultez le code source des services dans `src/Service/`.
