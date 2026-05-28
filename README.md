# Helpdesk site

A project by Pomy's IT department, aimed at automating the process of assigning roles and displaying on-call technicians on a terminal. All modifications can be made easily via a web interface.
Wtih this tool, plannings can be generated manually, and, if configured, automatically.

This app uses [CodeIgniter](https://codeigniter.com/) 4.7.x, along with HTML, CSS (with [Bootstrap](https://getbootstrap.com/)) and JavaScript.

## Table of Contents

- [Software Requirements](#software-requirements)
- [Cloning the Project](#cloning-the-project)
- [Docker Quick Start Guide](#docker-quick-start-guide)
  - [Docker Installation](#docker-installation)
  - [Enable Docker Daemon](#enable-docker-daemon)
  - [Container Installation and Deployment](#container-installation-and-deployment)
  - [Differences with Laragon](#differences-with-laragon)
  - [Application Access](#application-access)
  - [Useful Commands](#useful-commands)
  - [Integration Tests](#integration-tests)
  - [Quick Troubleshooting](#quick-troubleshooting)
- [You're all set!](#youre-all-set)

### Software requirements

To make this app work, you'll need to have both [PHP](https://www.php.net/) 8.4 and [Composer](https://getcomposer.org/) installed.

Required PHP extensions for normal operation are `intl`, `mbstring`, `mysqli`, `json`, `xml`, `libxml`, `ctype`, and `tokenizer`. Composer/test tooling also expects `dom`, `phar`, and `xmlwriter` in a development environment.

Install dependencies from the project root:

```bash
composer install
composer check-platform-reqs
```

When intentionally refreshing dependencies on the PHP 8.4 upgrade line, run:

```bash
composer update --with-all-dependencies
composer audit
composer check-platform-reqs
```

Installation processes are not detailled here.

## Cloning the project

1. Clone the repository into your server root.
2. Open the project in your text editor.
3. Run `composer install` from the project root.
4. Copy-paste the `env_dist` file.
5. Rename the new file `.env` and edit it :
    - Make sure that the `CI_ENVIRONMENT` variable is set to the correct value.
        - _`development` when working on the project, `production` when publishing the application._
    - `app.baseURL` must contain the URL to the root of your website.
        - Example : `app.baseURL = 'https://orif.ch/'`
    - Modify the `database.default.` fields with the informations matching your server.
    - Don't forget to uncomment the code you edited.
6. On your server, create manually a new database.
    - Its name has to be the same as defined in the `.env` file, in the `database.default.database` field.
    - Use utf8_general_ci or utf8mb4_general_ci collation.
7. On a new terminal, on project root, execute `php spark migrate --all`. This inserts all tables and default values in the database.

## Docker Quick Start Guide

Quick guide to install, configure and use Docker with the Helpdesk application.

**Note**: This section assumes you have already configured the project (PHP, CodeIgniter). For a complete installation from scratch, refer to the main project documentation.

---

### Docker Installation

#### Option 1: Docker Desktop (Recommended for Windows/Mac)

1. Download Docker Desktop from: https://www.docker.com/products/docker-desktop

2. Install and launch Docker Desktop
3. Verify the installation:

   ```bash
   docker --version
   docker compose version
   ```

#### Option 2: CLI Installation (Linux)

```bash
# Ubuntu/Debian
curl -fsSL https://get.docker.com -o get-docker.sh
sudo sh get-docker.sh

# Verify the installation
docker --version
```

---

### Enable Docker Daemon

#### Windows/Mac (Docker Desktop)

The daemon starts automatically with Docker Desktop. Verify that it is active:

```bash
docker ps
```

If the daemon is not active, launch Docker Desktop manually.

#### Linux

```bash
# Start the Docker service
sudo systemctl start docker

# Enable on startup
sudo systemctl enable docker

# Verify the status
sudo systemctl status docker
```

---

### Container Installation and Deployment

#### 1. Create the `.env` file

```bash
# Copy the example file
cp env_dist .env
```

#### 2. Configure environment variables

Edit the `.env` file and uncomment/adjust these variables:

```env
# Docker environment variables
DB_ROOT_PASSWORD = root_password
DB_NAME = ci4
DB_USER = ci4_user
DB_PASSWORD = ci4_password
```

**Important**: These values must match the configuration in `app/Config/Database.php`:

- `hostname`: `mariadb` (fixed, Docker service name)
- `username`: Must match `DB_USER`
- `password`: Must match `DB_PASSWORD`
- `database`: Must match `DB_NAME`

#### 3. Build and start containers

##### 3.1 In 2 commands

```bash
# Build the images
docker compose build

# Start the container in the background (-d)
docker compose up -d
```

##### 3.2 In 1 command

```bash
# Build the images and start the containers
docker compose up -d --build
```

This command will:

- Build the Apache and MariaDB images
- Create the containers
- Start the services in the background

#### 4. Verify that containers are active

```bash
# See running containers
docker compose ps

# See logs
docker compose logs
```

---

### Differences with Laragon

#### Environment variables

| Parameter | Laragon | Docker |
|-----------|---------|--------|
| **DB Hostname** | `localhost` | `mariadb` (service name) |
| **DB Port** | `3306` (local) | `3306` (exposed) |
| **Connection** | Direct | Via Docker internal network |
| **Variables** | In `.env` or PHP config | In `.env` + `docker-compose.yml` |

#### Database configuration

In `app/Config/Database.php`, the default values are:

```php
'hostname' => 'mariadb',  // Docker service name
'username' => 'ci4_user',
'password' => 'ci4_password',
'database' => 'ci4',
```

**Important**: These values must match the `DB_*` variables in your `.env` file.

---

### Application Access

#### Web application

- **Main URL**: `http://localhost/helpdesk/public/`
- **Example route**: `http://localhost/helpdesk/public/helpdesk/planning/nextWeekPlanning`

#### phpMyAdmin

1. **URL**: `http://localhost:8080/`

2. **Login credentials**:
   - **Server**: `mariadb` (or leave empty, phpMyAdmin detects it automatically)
   - **User**: Value of `DB_USER` in `.env` (e.g.: `ci4_user`)
   - **Password**: Value of `DB_PASSWORD` in `.env` (e.g.: `ci4_password`)

3. **Connection**:
   - Open `http://localhost:8080/` in your browser
   - Enter the credentials above
   - Click "Login"

---

### Useful Commands

#### Container Management

```bash
# Start containers
docker compose up -d

# Stop containers
docker compose stop

# Restart containers
docker compose restart

# Stop and remove containers
docker compose down

# View real-time logs
docker compose logs -f apache
docker compose logs -f mariadb
```

#### CodeIgniter Commands

```bash
# Run migrations
docker compose exec apache php spark migrate --all

# Other spark commands
docker compose exec apache php spark [command]
```

#### Shell Access

```bash
# Access Apache container shell
docker compose exec apache bash

# Access MariaDB container shell
docker compose exec mariadb bash
```

---

### Integration Tests

This section describes how to configure the databases and run integration tests for the planning system.

#### Database Configuration

##### Main Database Migration (ci4)

Before running migrations, make sure the `.env` file contains:

```env
CI_ENVIRONMENT=development
```

Then, run the migration:

```bash
docker exec -i helpdesk-apache-1 php spark migrate --all
```

##### Test Database Migration (ci4_test)

For the test database, configure the `.env` file with:

```env
CI_ENVIRONMENT=testing
```

Then run the test migration:

```bash
docker exec -i helpdesk-apache-1 php spark migrate:test
```

#### Adding Holidays

To add or manage holidays (for integration tests) in the database:

```bash
docker exec -i helpdesk-apache-1 php spark holidays:manage
```

#### Planning Tests

##### Full Generation (20 weeks + summaries)

To generate 20 weeks of planning with summaries in the `/writable` folder:

```bash
docker exec -i helpdesk-apache-1 php spark test:planning:integration
```

##### Single Week Generation

To generate a single week of planning:

```bash
docker exec -i helpdesk-apache-1 php spark test:planning
```

---

### Quick Troubleshooting

#### Issue: "Cannot connect to Docker daemon"

**Solution**: Verify that Docker Desktop is running (Windows/Mac) or that the Docker service is active (Linux).

#### Issue: "Port already in use"

**Solution**: Stop Laragon or change the ports in `docker-compose.yml`:

```yaml
ports:
  - "8081:80"  # Instead of 80:80
```

#### Issue: "Unable to connect to the database"

**Solution**:

1. Verify that MariaDB is started: `docker compose ps`
2. Check logs: `docker compose logs mariadb`
3. Verify that variables in `.env` match `app/Config/Database.php`

#### Issue: "404 Not Found"

**Solution**: Verify that:

- `app/Config/App.php`: `baseURL = 'http://localhost/helpdesk/public/'`
- `public/.htaccess`: `RewriteBase /helpdesk/public/`
- The Apache container is started: `docker compose ps`

### You're all set!

The helpdesk part of the project is under `orif\helpdesk`.
