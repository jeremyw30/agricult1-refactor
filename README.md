# 🌾 Agri-Cult - Version Refactorée

> Simulateur de ferme agricole en ligne avec architecture professionnelle

[![PHP](https://img.shields.io/badge/PHP-8.2+-blue.svg)](https://www.php.net/)
[![Symfony](https://img.shields.io/badge/Symfony-7.2-brightgreen.svg)](https://symfony.com/)
[![PostgreSQL](https://img.shields.io/badge/PostgreSQL-15-blue.svg)](https://www.postgresql.org/)

## 📋 Description

Agri-Cult est un jeu de simulation de ferme agricole développé avec Symfony 7.2 et suivant les meilleures pratiques de développement PHP.

**Version originale :** https://github.com/jeremyw30/agricult1

## ✨ Fonctionnalités

- 🚜 **Gestion de ferme** : Achetez et cultivez des parcelles
- 🐄 **Élevage d'animaux** : Prenez soin de vos animaux et collectez leurs productions
- 🏗️ **Bâtiments** : Construisez et améliorez vos infrastructures
- 🔧 **Machines agricoles** : Achetez et entretenez votre équipement
- 💰 **Marché** : Achetez et vendez vos ressources
- 💬 **Chat en temps réel** : Communiquez avec les autres joueurs via Mercure
- ☀️ **Système météo** : La météo influence vos cultures
- 👤 **Système d'authentification** : Inscription et connexion sécurisées

## 🏗️ Architecture

Ce projet utilise une **architecture clean** avec :

- **Controllers légers** : Routing uniquement
- **Services métier** : Toute la logique dans des services dédiés
- **DTOs** : Data Transfer Objects pour la validation
- **Repositories optimisés** : Requêtes DQL performantes
- **Event-Driven** : Listeners Doctrine pour les actions automatiques
- **Principes SOLID** : Code maintenable et évolutif

Voir [ARCHITECTURE.md](ARCHITECTURE.md) pour plus de détails.

## 🚀 Installation

### Prérequis

- Docker & Docker Compose
- Git

### Installation avec Docker

```bash
# Cloner le repository
git clone https://github.com/jeremyw30/agricult1-refactor.git
cd agricult1-refactor

# Lancer les conteneurs Docker
docker-compose up -d

# Installer les dépendances
docker-compose exec php composer install

# Créer la base de données
docker-compose exec php bin/console doctrine:database:create

# Exécuter les migrations
docker-compose exec php bin/console doctrine:migrations:migrate

# L'application est accessible sur http://localhost:8080
```

### Installation locale (sans Docker)

```bash
# Cloner le repository
git clone https://github.com/jeremyw30/agricult1-refactor.git
cd agricult1-refactor

# Installer les dépendances
composer install

# Configurer la base de données dans .env.local
# DATABASE_URL="postgresql://user:password@127.0.0.1:5432/agricult_db"

# Créer la base de données
php bin/console doctrine:database:create

# Exécuter les migrations
php bin/console doctrine:migrations:migrate

# Lancer le serveur Symfony
symfony server:start
```

## 🛠️ Stack Technique

- **Backend** : PHP 8.2+, Symfony 7.2
- **Base de données** : PostgreSQL 15
- **ORM** : Doctrine
- **Admin** : EasyAdmin 4
- **Temps réel** : Mercure
- **Frontend** : Bootstrap 5, Stimulus, Turbo
- **Assets** : Webpack Encore
- **Conteneurisation** : Docker

## 📚 Documentation

- [Architecture](ARCHITECTURE.md) - Détails de l'architecture du projet
- [Contribution](CONTRIBUTING.md) - Guide pour contribuer
- [Services](docs/SERVICES.md) - Documentation des services métier

## 🧪 Tests

```bash
# Lancer les tests
php bin/phpunit

# Tests avec couverture
php bin/phpunit --coverage-html coverage
```

## 📝 Standards de code

Le projet suit les standards PSR-12 et les conventions Symfony.

```bash
# Vérifier le code
vendor/bin/phpstan analyse src

# Formater le code
vendor/bin/php-cs-fixer fix
```

## 🤝 Contribution

Les contributions sont les bienvenues ! Voir [CONTRIBUTING.md](CONTRIBUTING.md).

## 📄 Licence

MIT

## 👨‍💻 Auteur

**Jeremy W.**
- Repository original : https://github.com/jeremyw30/agricult1
- Repository refactoré : https://github.com/jeremyw30/agricult1-refactor

---

**Bon jeu ! 🎮🌾**
