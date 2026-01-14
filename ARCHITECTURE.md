# 🏗️ Architecture du Projet Agri-Cult

## Vue d'ensemble

Agri-Cult suit une **architecture clean** inspirée des principes SOLID et des meilleures pratiques Symfony.

## Structure du Projet

```
src/
├── Controller/          # Controllers légers (routing uniquement)
├── Service/            # Logique métier
├── DTO/                # Data Transfer Objects
├── Entity/             # Entités Doctrine
├── Repository/         # Repositories optimisés
├── EventListener/      # Event listeners Doctrine
├── Exception/          # Exceptions personnalisées
└── Validator/          # Validateurs personnalisés
```

## Principes Architecturaux

### 1. Séparation des Responsabilités

**Controllers** : 
- Gèrent uniquement le routing HTTP
- Pas de logique métier
- Délèguent tout aux services

**Services** :
- Contiennent toute la logique métier
- Sont testables unitairement
- Sont réutilisables

**Repositories** :
- Gèrent les requêtes en base de données
- Contiennent les requêtes DQL optimisées
- Ne contiennent pas de logique métier

### 2. Injection de Dépendances

Tous les services utilisent l'injection par constructeur :

```php
public function __construct(
    private readonly ServiceDependency $service
) {}
```

### 3. DTOs (Data Transfer Objects)

Les DTOs encapsulent et valident les données :

```php
class ParcelleDTO
{
    #[Assert\NotBlank]
    #[Assert\Positive]
    public float $superficie;
}
```

### 4. Event-Driven Architecture

Les événements Doctrine automatisent certaines actions :

- `UserActivityListener` : Met à jour les timestamps
- `TransactionListener` : Log les transactions

## Flux de Données

```
HTTP Request
    ↓
Controller (routing)
    ↓
Service (logique métier)
    ↓
Repository (requêtes DB)
    ↓
Entity (données)
```

## Services Principaux

### Services Métier

| Service | Responsabilité |
|---------|---------------|
| `ParcelleService` | Gestion des parcelles |
| `AnimalService` | Gestion des animaux |
| `MachineService` | Gestion des machines |
| `BatimentService` | Gestion des bâtiments |
| `TransactionService` | Gestion des transactions financières |
| `MarketService` | Prix et marché |
| `ChatService` | Messages en temps réel |
| `MeteoService` | Génération de météo |
| `GameStateService` | État global du jeu |
| `AuthenticationService` | Authentification |

### Services Utilitaires

| Service | Responsabilité |
|---------|---------------|
| `ServerTimeService` | Gestion du temps serveur |

## Gestion des Erreurs

### Exceptions Personnalisées

```php
GameException                    # Exception de base
├── InsufficientBalanceException # Solde insuffisant
└── ResourceNotFoundException    # Ressource introuvable
```

## Sécurité

- **Authentification** : Système Symfony Security
- **Hashage des mots de passe** : Argon2i
- **CSRF Protection** : Activé sur les formulaires
- **Validation** : Symfony Validator sur tous les DTOs

## Performance

### Optimisations Base de Données

- Relations lazy-loading par défaut
- Requêtes DQL optimisées avec jointures
- Index sur les colonnes fréquemment recherchées

### Mise en Cache

- Cache Doctrine en production
- OPcache PHP activé

## Tests

Structure des tests :

```
tests/
├── Service/           # Tests unitaires des services
├── Controller/        # Tests fonctionnels des controllers
└── Repository/        # Tests des repositories
```

## Documentation du Code

Tous les fichiers suivent ces conventions :

- **PHPDoc en français** pour faciliter la compréhension
- **Typage strict** : `declare(strict_types=1)`
- **Readonly properties** quand possible
- **Return types** explicites

Exemple :

```php
/**
 * Achète une nouvelle parcelle pour l'utilisateur
 * 
 * @param User $user Utilisateur acheteur
 * @param ParcelleDTO $dto Données de la parcelle
 * @return UserParcelle Parcelle créée
 * @throws InsufficientBalanceException Si solde insuffisant
 */
public function buyParcelle(User $user, ParcelleDTO $dto): UserParcelle
{
    // ...
}
```

## Évolutivité

L'architecture permet facilement :

- D'ajouter de nouveaux services
- D'ajouter de nouvelles entités
- D'étendre les fonctionnalités existantes
- De modifier la logique sans toucher aux controllers

## Points d'Extension

### Ajouter une nouvelle fonctionnalité

1. Créer l'entité si nécessaire
2. Créer le repository
3. Créer le service métier
4. Créer le DTO si nécessaire
5. Créer le controller léger
6. Créer les templates

### Exemple : Ajouter un système de quêtes

```php
// 1. Entity/Quest.php
// 2. Repository/QuestRepository.php
// 3. Service/Quest/QuestService.php
// 4. DTO/Quest/QuestDTO.php
// 5. Controller/Quest/QuestController.php
// 6. templates/quest/
```

---

Cette architecture garantit un code **maintenable**, **testable** et **évolutif**.
