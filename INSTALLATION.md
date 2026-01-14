# 🚀 Guide d'Installation - Agri-Cult

Ce guide vous accompagne étape par étape pour installer et lancer l'application Agri-Cult.

## 📋 Prérequis

Avant de commencer, assurez-vous d'avoir :

- **Docker** et **Docker Compose** installés ([Guide d'installation](https://docs.docker.com/get-docker/))
- **Git** installé
- **Minimum 4 Go de RAM** disponible pour Docker

**OU (pour installation locale sans Docker) :**

- **PHP 8.2+** avec extensions : `pdo_pgsql`, `intl`, `zip`, `opcache`
- **PostgreSQL 15+**
- **Composer**
- **Symfony CLI** (optionnel mais recommandé)

---

## 🐳 Installation avec Docker (Recommandé)

### Étape 1 : Cloner le Repository

```bash
git clone https://github.com/jeremyw30/agricult1-refactor.git
cd agricult1-refactor
```

### Étape 2 : Configurer l'Environnement

Le fichier `.env` est déjà configuré pour Docker. Vous pouvez le personnaliser si nécessaire :

```bash
# Optionnel : Créer un fichier .env.local pour vos paramètres
cp .env .env.local
# Modifier .env.local selon vos besoins
```

### Étape 3 : Démarrer les Conteneurs

```bash
docker-compose up -d
```

Cette commande va :
- Créer et démarrer PostgreSQL
- Créer et démarrer PHP-FPM
- Créer et démarrer Nginx
- Créer et démarrer Mercure

### Étape 4 : Installer les Dépendances

```bash
docker-compose exec php composer install
```

### Étape 5 : Créer la Base de Données

```bash
# Créer la base de données
docker-compose exec php bin/console doctrine:database:create

# Générer et exécuter les migrations
docker-compose exec php bin/console make:migration
docker-compose exec php bin/console doctrine:migrations:migrate
```

### Étape 6 : (Optionnel) Charger des Données de Test

```bash
# Installer le bundle de fixtures
docker-compose exec php composer require --dev orm-fixtures

# Créer des fixtures
docker-compose exec php bin/console make:fixtures

# Charger les fixtures
docker-compose exec php bin/console doctrine:fixtures:load
```

### Étape 7 : Accéder à l'Application

Ouvrez votre navigateur et accédez à :

🌐 **http://localhost:8080**

**Ports utilisés :**
- **8080** : Application web (Nginx)
- **5432** : PostgreSQL
- **3000** : Mercure

### Étape 8 : Créer un Compte Utilisateur

1. Cliquez sur "Inscription"
2. Remplissez le formulaire
3. Connectez-vous avec vos identifiants
4. Commencez à jouer ! 🎮

---

## 💻 Installation Locale (Sans Docker)

### Étape 1 : Cloner le Repository

```bash
git clone https://github.com/jeremyw30/agricult1-refactor.git
cd agricult1-refactor
```

### Étape 2 : Installer les Dépendances

```bash
composer install
```

### Étape 3 : Configurer la Base de Données

Créez un fichier `.env.local` :

```bash
cp .env .env.local
```

Modifiez la ligne `DATABASE_URL` dans `.env.local` :

```
DATABASE_URL="postgresql://VOTRE_USER:VOTRE_PASSWORD@127.0.0.1:5432/agricult_db"
```

### Étape 4 : Créer la Base de Données

```bash
# Créer la base de données
php bin/console doctrine:database:create

# Générer et exécuter les migrations
php bin/console make:migration
php bin/console doctrine:migrations:migrate
```

### Étape 5 : Lancer le Serveur

```bash
# Avec Symfony CLI (recommandé)
symfony server:start

# OU avec le serveur PHP intégré
php -S localhost:8000 -t public/
```

### Étape 6 : Accéder à l'Application

🌐 **http://localhost:8000** (ou le port indiqué par Symfony CLI)

---

## 🧪 Lancer les Tests

### Avec Docker

```bash
docker-compose exec php bin/phpunit
```

### Sans Docker

```bash
php bin/phpunit
```

---

## 🛠️ Commandes Utiles

### Docker

```bash
# Voir les logs
docker-compose logs -f

# Voir les logs d'un service spécifique
docker-compose logs -f php
docker-compose logs -f nginx

# Accéder au conteneur PHP
docker-compose exec php sh

# Arrêter les conteneurs
docker-compose stop

# Redémarrer les conteneurs
docker-compose restart

# Supprimer tout (conteneurs, volumes, réseaux)
docker-compose down -v
```

### Symfony

```bash
# Vider le cache
php bin/console cache:clear

# Lister toutes les routes
php bin/console debug:router

# Lister tous les services
php bin/console debug:container

# Créer une nouvelle entité
php bin/console make:entity

# Créer un nouveau controller
php bin/console make:controller

# Créer une migration
php bin/console make:migration

# Exécuter les migrations
php bin/console doctrine:migrations:migrate
```

### Base de Données

```bash
# Accéder à PostgreSQL (Docker)
docker-compose exec db psql -U agricult -d agricult_db

# Supprimer et recréer la base
php bin/console doctrine:database:drop --force
php bin/console doctrine:database:create
php bin/console doctrine:migrations:migrate
```

---

## 🔧 Dépannage

### Problème : "Port 8080 already in use"

**Solution :** Modifiez le port dans `docker-compose.yml` :

```yaml
nginx:
  ports:
    - "8081:80"  # Changez 8080 en 8081
```

### Problème : "Database connection failed"

**Solution :** Vérifiez que PostgreSQL est bien démarré :

```bash
docker-compose ps
```

Si le service `db` n'est pas `Up`, redémarrez-le :

```bash
docker-compose restart db
```

### Problème : "Permission denied" lors de l'installation

**Solution :** Donnez les permissions nécessaires :

```bash
sudo chmod -R 777 var/
sudo chown -R $USER:$USER .
```

### Problème : Erreur Composer "out of memory"

**Solution :** Augmentez la limite de mémoire :

```bash
php -d memory_limit=-1 /usr/local/bin/composer install
```

---

## 📊 État de l'Application

Après l'installation, vous devriez avoir :

✅ Page d'accueil fonctionnelle  
✅ Système d'inscription/connexion  
✅ Dashboard du joueur  
✅ Gestion des parcelles  
✅ Gestion des animaux  
✅ Gestion des machines  
✅ Gestion des bâtiments  
✅ Marché fonctionnel  
✅ Chat (Mercure à configurer pour le temps réel)  
✅ Système de météo  

---

## 🎮 Premiers Pas dans le Jeu

1. **Créez un compte** via la page d'inscription
2. **Connectez-vous** avec vos identifiants
3. **Consultez votre dashboard** pour voir votre solde initial (1000€)
4. **Achetez votre première parcelle** (500€/hectare)
5. **Cultivez** votre parcelle
6. **Récoltez** après la période de culture
7. **Gagnez de l'argent** et développez votre ferme !

---

## 📝 Notes Importantes

- **Solde initial** : Chaque joueur commence avec 1000€
- **Prix parcelle** : 500€ par hectare
- **Durée de culture** : 7 jours (peut être ajusté dans `ParcelleService`)
- **Production animale** : Une fois par 24h
- **Réparation machine** : 50€
- **La météo influence** : Les rendements des cultures

---

## 🆘 Besoin d'Aide ?

- Consultez la [documentation complète](README.md)
- Lisez l'[architecture](ARCHITECTURE.md)
- Consultez le [guide de contribution](CONTRIBUTING.md)
- Ouvrez une [issue sur GitHub](https://github.com/jeremyw30/agricult1-refactor/issues)

---

**Bon jeu ! 🎮🌾**
