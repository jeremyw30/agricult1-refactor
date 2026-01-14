# 🤝 Guide de Contribution

Merci de votre intérêt pour contribuer à Agri-Cult !

## 📋 Avant de Commencer

1. Lire le [README.md](README.md) et [ARCHITECTURE.md](ARCHITECTURE.md)
2. Vérifier les [issues existantes](https://github.com/jeremyw30/agricult1-refactor/issues)
3. Créer une issue si nécessaire pour discuter des changements majeurs

## 🔧 Configuration de l'Environnement

```bash
# Fork et clone
git clone https://github.com/VOTRE-USERNAME/agricult1-refactor.git
cd agricult1-refactor

# Installation
composer install

# Configuration
cp .env .env.local
# Modifier .env.local avec vos paramètres

# Base de données
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

## 📝 Standards de Code

### PHP

- **PSR-12** : Standard de code PHP
- **PHPDoc en français** : Tous les commentaires doivent être en français
- **Typage strict** : `declare(strict_types=1)` en haut de chaque fichier
- **Readonly properties** : Utiliser quand possible

### Exemple de Code

```php
<?php

declare(strict_types=1);

namespace App\Service\Example;

/**
 * Service d'exemple
 * 
 * Description détaillée en français
 */
class ExampleService
{
    public function __construct(
        private readonly DependencyService $dependency
    ) {}

    /**
     * Méthode d'exemple
     * 
     * @param string $param Paramètre d'entrée
     * @return bool Résultat
     */
    public function exampleMethod(string $param): bool
    {
        // Code...
        return true;
    }
}
```

## 🏗️ Architecture

### Ajouter une Nouvelle Fonctionnalité

1. **Créer l'Entity** (si nécessaire)
   ```bash
   php bin/console make:entity
   ```

2. **Créer le Repository**
   - Ajouter les requêtes DQL optimisées

3. **Créer le Service**
   - Toute la logique métier dans le service
   - Injection de dépendances par constructeur

4. **Créer le DTO** (si nécessaire)
   - Pour la validation des données

5. **Créer le Controller**
   - Léger, uniquement routing
   - Déléguer au service

6. **Créer les Templates**

7. **Écrire les Tests**

### Exemple : Ajouter un Système de Réalisations

```bash
# 1. Entity
src/Entity/Achievement.php

# 2. Repository
src/Repository/AchievementRepository.php

# 3. Service
src/Service/Achievement/AchievementService.php

# 4. DTO
src/DTO/Achievement/AchievementDTO.php

# 5. Controller
src/Controller/Achievement/AchievementController.php

# 6. Templates
templates/achievement/

# 7. Tests
tests/Service/Achievement/AchievementServiceTest.php
```

## 🧪 Tests

### Tests Unitaires

```php
<?php

namespace App\Tests\Service\Example;

use App\Service\Example\ExampleService;
use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;

class ExampleServiceTest extends KernelTestCase
{
    private ExampleService $service;

    protected function setUp(): void
    {
        self::bootKernel();
        $this->service = self::getContainer()->get(ExampleService::class);
    }

    public function testExampleMethod(): void
    {
        $result = $this->service->exampleMethod('test');
        $this->assertTrue($result);
    }
}
```

### Lancer les Tests

```bash
# Tous les tests
php bin/phpunit

# Tests spécifiques
php bin/phpunit tests/Service/Example

# Avec couverture
php bin/phpunit --coverage-html coverage
```

## 📦 Commits

### Convention de Commit

Format : `type(scope): message`

**Types** :
- `feat`: Nouvelle fonctionnalité
- `fix`: Correction de bug
- `docs`: Documentation
- `style`: Formatage, indentation
- `refactor`: Refactoring
- `test`: Ajout de tests
- `chore`: Maintenance

**Exemples** :
```
feat(parcelle): ajout du système de rotation des cultures
fix(animal): correction du calcul de production
docs(readme): mise à jour de la documentation d'installation
refactor(service): simplification du ParcelleService
test(animal): ajout des tests pour AnimalService
```

## 🔀 Workflow Git

1. **Créer une branche**
   ```bash
   git checkout -b feature/ma-nouvelle-fonctionnalite
   ```

2. **Développer et commiter**
   ```bash
   git add .
   git commit -m "feat(scope): description"
   ```

3. **Pousser la branche**
   ```bash
   git push origin feature/ma-nouvelle-fonctionnalite
   ```

4. **Créer une Pull Request**
   - Titre clair et descriptif
   - Description détaillée des changements
   - Référencer les issues liées

## ✅ Checklist avant Pull Request

- [ ] Le code suit les standards PSR-12
- [ ] Tous les commentaires sont en français
- [ ] Les tests passent (`php bin/phpunit`)
- [ ] PHPStan passe sans erreur (`vendor/bin/phpstan analyse src`)
- [ ] La documentation est à jour
- [ ] Les migrations sont créées si nécessaire
- [ ] Pas de code commenté ou de `var_dump`
- [ ] Les variables et méthodes ont des noms explicites

## 🐛 Signaler un Bug

Créer une issue avec :

- **Titre** : Description courte du bug
- **Description** : Détails du problème
- **Étapes pour reproduire** : Comment reproduire le bug
- **Comportement attendu** : Ce qui devrait se passer
- **Comportement actuel** : Ce qui se passe réellement
- **Environnement** : OS, version PHP, etc.
- **Screenshots** : Si applicable

## 💡 Proposer une Fonctionnalité

Créer une issue avec :

- **Titre** : Nom de la fonctionnalité
- **Description** : Explication détaillée
- **Cas d'usage** : Pourquoi cette fonctionnalité est utile
- **Proposition de solution** : Comment implémenter (optionnel)

## 📞 Questions

Pour toute question :

- Ouvrir une issue avec le tag `question`
- Consulter la [documentation](docs/)

## 📄 Licence

En contribuant, vous acceptez que vos contributions soient sous licence MIT.

---

**Merci pour votre contribution ! 🙏**
