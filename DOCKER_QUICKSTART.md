# 🐳 Guide Rapide Docker - Helpdesk

Guide rapide pour installer, configurer et utiliser Docker avec l'application Helpdesk.

---

## 📦 Installation de Docker

### Option 1 : Docker Desktop (Recommandé pour Windows/Mac)

1. Télécharger Docker Desktop depuis : https://www.docker.com/products/docker-desktop
2. Installer et lancer Docker Desktop
3. Vérifier l'installation :
   ```bash
   docker --version
   docker compose version
   ```

### Option 2 : Installation en CLI (Linux)

```bash
# Ubuntu/Debian
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Vérifier l'installation
docker --version
```

---

## 🚀 Activer le daemon Docker

### Windows/Mac (Docker Desktop)
Le daemon démarre automatiquement avec Docker Desktop. Vérifier qu'il est actif :
```bash
docker ps
```

Si le daemon n'est pas actif, lancer Docker Desktop manuellement.

### Linux
```bash
# Démarrer le service Docker
sudo systemctl start docker

# Activer au démarrage
sudo systemctl enable docker

# Vérifier le statut
sudo systemctl status docker
```

---

## 📥 Installation et déploiement des conteneurs

### 1. Créer le fichier `.env`

```bash
# Copier le fichier d'exemple
cp env_dist .env
```

### 2. Configurer les variables d'environnement

Éditer le fichier `.env` et décommenter/ajuster ces variables :

```env
# Docker environment variables
DB_ROOT_PASSWORD = root_password
DB_NAME = ci4
DB_USER = ci4_user
DB_PASSWORD = ci4_password
```

**⚠️ Important** : Ces valeurs doivent correspondre à la configuration dans `app/Config/Database.php` :
- `hostname` : `mariadb` (fixe, nom du service Docker)
- `username` : Doit correspondre à `DB_USER`
- `password` : Doit correspondre à `DB_PASSWORD`
- `database` : Doit correspondre à `DB_NAME`

### 3. Construire et démarrer les conteneurs

#### 3.1 En 2 commandes

```bash
# Construire les images
docker compose build

# Démarrer le conteneur en arrière plan (-d)
docker compose up -d
```

#### 3.2 En 1 commande

```bash
# Construire les images et démarrer les conteneurs
docker compose up -d --build
```

Cette commande va :
- Construire les images Apache et MariaDB
- Créer les conteneurs
- Démarrer les services en arrière-plan

### 4. Vérifier que les conteneurs sont actifs

```bash
# Voir les conteneurs en cours d'exécution
docker compose ps

# Voir les logs
docker compose logs
```

---

## 🔧 Différences avec Laragon

### Variables d'environnement

| Paramètre | Laragon | Docker |
|-----------|---------|--------|
| **Hostname DB** | `localhost` | `mariadb` (nom du service) |
| **Port DB** | `3306` (local) | `3306` (exposé) |
| **Connexion** | Directe | Via réseau Docker interne |
| **Variables** | Dans `.env` ou config PHP | Dans `.env` + `docker-compose.yml` |

### Configuration de la base de données

Dans `app/Config/Database.php`, les valeurs par défaut sont :
```php
'hostname' => 'mariadb',  // Nom du service Docker
'username' => 'ci4_user',
'password' => 'ci4_password',
'database' => 'ci4',
```

**Important** : Ces valeurs doivent correspondre aux variables `DB_*` dans votre fichier `.env`.

---

## 🌐 Accès à l'application

### Application web
- **URL principale** : `http://localhost/helpdesk/public/`
- **Route exemple** : `http://localhost/helpdesk/public/helpdesk/planning/nw_planning`

### phpMyAdmin

1. **URL** : `http://localhost:8080/`

2. **Identifiants de connexion** :
   - **Serveur** : `mariadb` (ou laisser vide, phpMyAdmin le détecte automatiquement)
   - **Utilisateur** : Valeur de `DB_USER` dans `.env` (ex: `ci4_user`)
   - **Mot de passe** : Valeur de `DB_PASSWORD` dans `.env` (ex: `ci4_password`)

3. **Connexion** :
   - Ouvrir `http://localhost:8080/` dans votre navigateur
   - Entrer les identifiants ci-dessus
   - Cliquer sur "Connexion"

---

## 🛠️ Commandes utiles

### Gestion des conteneurs

```bash
# Démarrer les conteneurs
docker compose up -d

# Arrêter les conteneurs
docker compose stop

# Redémarrer les conteneurs
docker compose restart

# Arrêter et supprimer les conteneurs
docker compose down

# Voir les logs en temps réel
docker compose logs -f apache
docker compose logs -f mariadb
```

### Commandes CodeIgniter

```bash
# Exécuter les migrations
docker compose exec apache php spark migrate

# Autres commandes spark
docker compose exec apache php spark [commande]
```

### Accès au shell

```bash
# Accéder au shell du conteneur Apache
docker compose exec apache bash

# Accéder au shell du conteneur MariaDB
docker compose exec mariadb bash
```

---

## ⚠️ Dépannage rapide

### Problème : "Cannot connect to Docker daemon"

**Solution** : Vérifier que Docker Desktop est lancé (Windows/Mac) ou que le service Docker est actif (Linux).

### Problème : "Port already in use"

**Solution** : Arrêter Laragon ou changer les ports dans `docker-compose.yml` :
```yaml
ports:
  - "8081:80"  # Au lieu de 80:80
```

### Problème : "Unable to connect to the database"

**Solution** :
1. Vérifier que MariaDB est démarré : `docker compose ps`
2. Vérifier les logs : `docker compose logs mariadb`
3. Vérifier que les variables dans `.env` correspondent à `app/Config/Database.php`

### Problème : "404 Not Found"

**Solution** : Vérifier que :
- `app/Config/App.php` : `baseURL = 'http://localhost/helpdesk/public/'`
- `public/.htaccess` : `RewriteBase /helpdesk/public/`
- Le conteneur Apache est bien démarré : `docker compose ps`

---

## 📚 Documentation complète

Pour plus de détails, consultez le fichier `DOCKER_CHANGES.md` qui documente tous les changements effectués pour la conteneurisation.

---

**Note** : Ce guide suppose que vous avez déjà configuré le projet. Pour une installation complète depuis zéro, référez-vous à la documentation principale du projet.

