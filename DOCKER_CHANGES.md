# Documentation des changements pour la conteneurisation Docker

Ce document liste tous les changements effectués pour permettre l'utilisation de Docker avec l'application Helpdesk.

## 📋 Table des matières

1. [Fichiers créés](#fichiers-créés)
2. [Fichiers modifiés](#fichiers-modifiés)
3. [Configuration Docker](#configuration-docker)
4. [Variables d'environnement](#variables-denvironnement)
5. [Instructions d'utilisation](#instructions-dutilisation)
6. [Différences avec l'environnement Laragon](#différences-avec-lenvironnement-laragon)

---

## 🆕 Fichiers créés

### 1. `docker/apache/dockerfile`
**Chemin** : `docker/apache/dockerfile`

**Description** : Dockerfile pour créer l'image Apache avec PHP 8.1 et les extensions nécessaires.

**Contenu** :
```dockerfile
FROM php:8.1-apache

RUN apt-get update && apt-get install -y \
    libzip-dev \
    zip \
    libicu-dev \
    && docker-php-ext-install zip pdo pdo_mysql intl mysqli

RUN a2enmod rewrite

# Copier la configuration Apache
COPY 000-default.conf /etc/apache2/sites-available/000-default.conf

WORKDIR /var/www/html
```

**Extensions PHP installées** :
- `zip` : Support des archives ZIP
- `pdo` : PDO (PHP Data Objects)
- `pdo_mysql` : Driver PDO pour MySQL/MariaDB
- `intl` : Support de l'internationalisation
- `mysqli` : Extension MySQLi pour CodeIgniter

### 2. `docker/apache/000-default.conf`
**Chemin** : `docker/apache/000-default.conf`

**Description** : Configuration Apache pour servir l'application depuis la racine du projet, permettant l'accès via le chemin `/helpdesk/public/`.

**Contenu** :
```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/public

    # Alias pour permettre l'accès via /helpdesk/public/
    Alias /helpdesk/public /var/www/html/public

    <Directory /var/www/html/public>
        Options Indexes FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

**Points importants** :
- `DocumentRoot` pointe vers `/var/www/html/public` (répertoire public de CodeIgniter)
- `Alias /helpdesk/public /var/www/html/public` permet d'accéder à l'application via l'URL `/helpdesk/public/`
- `AllowOverride All` permet l'utilisation du `.htaccess`
- `Require all granted` autorise l'accès au répertoire

### 3. `docker/mariadb/dockerfile`
**Chemin** : `docker/mariadb/dockerfile` (déjà existant)

**Description** : Dockerfile pour créer l'image MariaDB avec la configuration personnalisée.

### 4. `docker/mariadb/init.sql`
**Chemin** : `docker/mariadb/init.sql` (déjà existant)

**Description** : Script SQL d'initialisation de la base de données.

---

## ✏️ Fichiers modifiés

### 1. `docker-compose.yml`

#### Avant
```yaml
volumes:
  - ./ci-application:/var/www/html
```

#### Après
```yaml
volumes:
  - .:/var/www/html
```

**Changement** : Le volume monte maintenant le répertoire racine du projet (`./`) au lieu d'un répertoire `ci-application` inexistant.

**Impact** : L'application est maintenant correctement montée dans le conteneur.

---

### 2. `app/Config/App.php`

#### Avant
```php
public string $baseURL = 'http://localhost/ci_packbase_v4/public/';
```

#### Après
```php
public string $baseURL = 'http://localhost/helpdesk/public/';
```

**Changement** : Le `baseURL` a été modifié pour correspondre à l'environnement Docker avec le chemin `/helpdesk/public/`.

**Impact** :
- **Laragon** : L'URL était `http://localhost/helpdesk/public/`
- **Docker** : L'URL est maintenant `http://localhost/helpdesk/public/` (même format que Laragon)

**Note** : Les URLs sont maintenant identiques entre Laragon et Docker pour faciliter la transition.

---

### 3. `app/Config/Database.php`

#### Avant
```php
public array $default = [
    'hostname' => 'localhost',
    'username' => '',
    'password' => '',
    'database' => '',
    // ...
];
```

#### Après
```php
public array $default = [
    'hostname' => 'mariadb',
    'username' => 'ci4_user',
    'password' => 'ci4_password',
    'database' => 'ci4',
    // ...
];
```

**Changements** :
1. **Hostname** : `localhost` → `mariadb` (nom du service Docker)
2. **Identifiants** : Valeurs par défaut configurées pour Docker
3. **Base de données** : Nom de la base par défaut défini

**Impact** :
- **Laragon** : Connexion à `localhost` avec les identifiants locaux
- **Docker** : Connexion au service `mariadb` via le réseau Docker interne

**Important** : Les identifiants doivent correspondre aux variables d'environnement définies dans `.env` et `docker-compose.yml`.

---

### 4. `public/.htaccess`

#### Avant
```apache
# RewriteBase /
```

#### Après
```apache
RewriteBase /helpdesk/public/
```

**Changement** : Le `RewriteBase` a été décommenté et configuré pour `/helpdesk/public/` (chemin de base de l'application).

**Impact** :
- **Laragon** : Utilise `RewriteBase /helpdesk/public/`
- **Docker** : Utilise également `RewriteBase /helpdesk/public/` (même configuration que Laragon)

---

## 🐳 Configuration Docker

### Services Docker Compose

#### 1. Service `apache`
- **Image** : Construite depuis `docker/apache/dockerfile`
- **Port** : `80:80` (port 80 de l'hôte vers port 80 du conteneur)
- **Volume** : `.:/var/www/html` (montage du projet)
- **Dépendances** : `mariadb`

#### 2. Service `mariadb`
- **Image** : Construite depuis `docker/mariadb/dockerfile`
- **Port** : `3306:3306` (port 3306 de l'hôte vers port 3306 du conteneur)
- **Volume** : `db_data:/var/lib/mysql` (persistance des données)
- **Variables d'environnement** : Utilise les variables du fichier `.env`

#### 3. Service `phpmyadmin`
- **Image** : `phpmyadmin/phpmyadmin:latest`
- **Port** : `8080:80` (port 8080 de l'hôte vers port 80 du conteneur)
- **Accès** : `http://localhost:8080`

### Réseau Docker

- **Nom** : `app-network`
- **Type** : `bridge`
- **Usage** : Permet la communication entre les conteneurs (apache ↔ mariadb)

### Volumes Docker

- **`db_data`** : Volume nommé pour la persistance des données MariaDB

---

## 🔐 Variables d'environnement

### Fichier `.env` requis

Créez un fichier `.env` à partir de `env_dist` :

```bash
cp env_dist .env
```

### Variables nécessaires

Décommentez et configurez ces variables dans `.env` :

```env
# Docker environment variables
DB_ROOT_PASSWORD = root_password
DB_NAME = ci4
DB_USER = ci4_user
DB_PASSWORD = ci4_password
```

**Correspondance avec `app/Config/Database.php`** :
- `DB_NAME` → `database` dans la config
- `DB_USER` → `username` dans la config
- `DB_PASSWORD` → `password` dans la config
- `hostname` est fixé à `mariadb` (nom du service Docker)

---

## 📝 Instructions d'utilisation

### 1. Préparation

```bash
# Créer le fichier .env
cp env_dist .env

# Éditer .env et configurer les variables DB_*
```

### 2. Démarrer les conteneurs

```bash
# Construire et démarrer les conteneurs
docker compose up -d --build
```

### 3. Vérifier les conteneurs

```bash
# Voir les conteneurs en cours d'exécution
docker compose ps

# Voir les logs
docker compose logs apache
docker compose logs mariadb
```

### 4. Exécuter les migrations

```bash
# Exécuter les migrations dans le conteneur Apache
docker compose exec apache php spark migrate
```

### 5. Accéder à l'application

- **Application** : `http://localhost/helpdesk/public/`
- **Routes helpdesk** : `http://localhost/helpdesk/public/helpdesk/planning/nw_planning`
- **Page d'accueil** : `http://localhost/helpdesk/public/`
- **phpMyAdmin** : `http://localhost:8080/`

### 6. Commandes utiles

```bash
# Arrêter les conteneurs
docker compose stop

# Redémarrer les conteneurs
docker compose restart

# Arrêter et supprimer les conteneurs
docker compose down

# Voir les logs en temps réel
docker compose logs -f apache

# Exécuter une commande dans le conteneur Apache
docker compose exec apache php spark [commande]

# Accéder au shell du conteneur Apache
docker compose exec apache bash
```

---

## 🔄 Différences avec l'environnement Laragon

### URLs

| Environnement | URL de base | Exemple de route |
|--------------|-------------|------------------|
| **Laragon** | `http://localhost/helpdesk/public/` | `http://localhost/helpdesk/public/helpdesk/planning/nw_planning` |
| **Docker** | `http://localhost/helpdesk/public/` | `http://localhost/helpdesk/public/helpdesk/planning/nw_planning` |

### Base de données

| Paramètre | Laragon | Docker |
|-----------|---------|-------|
| **Hostname** | `localhost` | `mariadb` |
| **Port** | `3306` (local) | `3306` (exposé) |
| **Connexion** | Directe | Via réseau Docker |

### Configuration Apache

| Aspect | Laragon | Docker |
|--------|---------|--------|
| **DocumentRoot** | Configuré par Laragon | `/var/www/html/public` avec alias `/helpdesk/public` |
| **RewriteBase** | `/helpdesk/public/` | `/helpdesk/public/` |
| **Modules** | Gérés par Laragon | Configurés dans Dockerfile |

### Commandes

| Action | Laragon | Docker |
|--------|---------|--------|
| **Migrations** | `php spark migrate` | `docker compose exec apache php spark migrate` |
| **Logs** | Fichiers locaux | `docker compose logs` |
| **PHP** | Version système | PHP 8.1 (conteneurisé) |

---

## ⚠️ Notes importantes

### 1. Conflit de ports

Si vous utilisez Laragon et Docker en même temps :
- **Laragon** utilise le port 80
- **Docker** utilise aussi le port 80

**Solution** : Arrêtez Laragon ou changez le port dans `docker-compose.yml` :
```yaml
ports:
  - "8081:80"  # Utiliser le port 8081 au lieu de 80
```

### 2. Fichier `.env`

Le fichier `.env` doit être créé et configuré avant de démarrer Docker. Les variables `DB_*` sont utilisées par `docker-compose.yml` pour configurer MariaDB.

### 3. Persistance des données

Les données de la base de données sont stockées dans le volume Docker `db_data`. Pour supprimer complètement les données :
```bash
docker compose down -v  # Supprime aussi les volumes
```

### 4. Synchronisation des fichiers

Les fichiers du projet sont montés en volume, donc les modifications sont immédiatement visibles dans le conteneur (pas besoin de rebuild).

### 5. Configuration de base de données

Les identifiants dans `app/Config/Database.php` doivent correspondre aux variables d'environnement `.env` :
- `hostname` : `mariadb` (fixe, nom du service)
- `username` : Doit correspondre à `DB_USER`
- `password` : Doit correspondre à `DB_PASSWORD`
- `database` : Doit correspondre à `DB_NAME`

---

## 🔧 Dépannage

### Problème : "Unable to connect to the database"

**Cause** : Le conteneur MariaDB n'est pas démarré ou les identifiants sont incorrects.

**Solution** :
```bash
# Vérifier que MariaDB est démarré
docker compose ps

# Vérifier les logs
docker compose logs mariadb

# Vérifier les variables d'environnement dans .env
```

### Problème : "404 Not Found"

**Cause** : Le `baseURL` ou le `RewriteBase` est incorrect.

**Solution** : Vérifier que :
- `app/Config/App.php` : `baseURL = 'http://localhost/helpdesk/public/'`
- `public/.htaccess` : `RewriteBase /helpdesk/public/`
- `docker/apache/000-default.conf` : 
  - `DocumentRoot /var/www/html/public`
  - `Alias /helpdesk/public /var/www/html/public` présent

### Problème : "Port already in use"

**Cause** : Un autre service utilise le port 80 ou 3306.

**Solution** : Arrêter Laragon ou changer les ports dans `docker-compose.yml`.

---

## 📚 Références

- [Docker Compose Documentation](https://docs.docker.com/compose/)
- [CodeIgniter 4 Documentation](https://codeigniter.com/user_guide/)
- [Apache VirtualHost Documentation](https://httpd.apache.org/docs/2.4/vhosts/)

---

**Date de création** : 2025-11-19  
**Version** : 1.0

