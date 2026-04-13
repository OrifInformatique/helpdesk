# Helpdesk — Production Deployment Guide

> **Branch:** `pre-production`  
> **Date:** 2026-04-13  
> **Target:** Production server (no Docker)

---

## Table of Contents

- [Helpdesk — Production Deployment Guide](#helpdesk--production-deployment-guide)
  - [Table of Contents](#table-of-contents)
  - [1. Prerequisites](#1-prerequisites)
  - [2. Files to Deploy](#2-files-to-deploy)
  - [3. Environment Configuration (.env)](#3-environment-configuration-env)
    - [Important notes on `.env`:](#important-notes-on-env)
  - [4. Apache / Web Server Configuration](#4-apache--web-server-configuration)
    - [4.1 Document Root](#41-document-root)
    - [4.2 `.htaccess`](#42-htaccess)
    - [4.3 Required Apache modules](#43-required-apache-modules)
  - [5. Database Setup](#5-database-setup)
    - [5.1 Create the database](#51-create-the-database)
    - [5.2 Run migrations](#52-run-migrations)
    - [5.3 Verify migration](#53-verify-migration)
  - [6. Writable Permissions](#6-writable-permissions)
  - [7. Post-Deployment Verification](#7-post-deployment-verification)
    - [7.1 Checklist](#71-checklist)
    - [7.2 Change admin password](#72-change-admin-password)
    - [7.3 Optional: Update admin email](#73-optional-update-admin-email)
  - [8. Rollback Plan](#8-rollback-plan)
  - [Summary of Seed Files and Their Status](#summary-of-seed-files-and-their-status)

---

## 1. Prerequisites

| Requirement         | Details                                      |
|---------------------|----------------------------------------------|
| **PHP**             | >= 8.0 and < 8.2 (see `composer.json`)       |
| **PHP Extensions**  | `intl`, `mysqli`, `mbstring`, `json`, `zip`   |
| **Web Server**      | Apache with `mod_rewrite` enabled             |
| **Database**        | MariaDB or MySQL                              |
| **Composer**        | Installed on the server or dependencies pre-installed via `vendor/` |

---

## 2. Files to Deploy

Copy the entire project to the production server. **Exclude** the following:

| Exclude                        | Reason                             |
|--------------------------------|------------------------------------|
| `docker/`                      | Docker-only (not used in prod)     |
| `docker-compose.yml`           | Docker-only                        |
| `tests/`                       | Test suite, not needed in prod     |
| `phpunit.xml.dist`             | PHPUnit config                     |
| `writable/debugbar/`           | Debug toolbar data                 |
| `writable/logs/*` (contents)   | Old logs, keep the directory       |
| `writable/session/*` (contents)| Old sessions, keep the directory   |
| `.git/`                        | Git history                        |
| `.env` from dev                | Must create a new one for prod     |

**Keep** the `vendor/` directory (or run `composer install --no-dev` on the server).

---

## 3. Environment Configuration (.env)

Copy `env_dist` to `.env` at the project root, then configure the following values:

```ini
#--------------------------------------------------------------------
# ENVIRONMENT — MANDATORY
#--------------------------------------------------------------------
CI_ENVIRONMENT = production

#--------------------------------------------------------------------
# APP
#--------------------------------------------------------------------
app.baseURL = 'https://your-production-domain.ch/helpdesk/public/'
app.forceGlobalSecureRequests = true

#--------------------------------------------------------------------
# DATABASE
#--------------------------------------------------------------------
database.default.hostname = localhost
database.default.database = your_prod_db_name
database.default.username = your_prod_db_user
database.default.password = your_prod_db_password
database.default.DBDriver = MySQLi
database.default.port = 3306
```

### Important notes on `.env`:

- **`CI_ENVIRONMENT = production`**: This disables the debug toolbar and detailed error messages. The hardcoded default in `Database.php` has `DBDebug = true` — in production mode, CodeIgniter will catch DB errors gracefully instead of showing stack traces.
- **`app.baseURL`**: Must match the actual production URL. This affects link generation, redirects, and CSRF.
- **Do NOT configure `database.tests.*`** values — they are not needed in production.
- **Do NOT set Docker variables** (`DB_ROOT_PASSWORD`, `DB_NAME`, etc.) — they are unused without Docker.

---

## 4. Apache / Web Server Configuration

### 4.1 Document Root

The web server document root must point to the `public/` directory:

```
/path/to/helpdesk/public/
```

### 4.2 `.htaccess`

The file `public/.htaccess` contains a `RewriteBase` directive:

```apache
RewriteBase /helpdesk/public/
```

**Adjust this** if the production URL path differs. For example:
- If the app is at `https://example.ch/helpdesk/public/` → keep as-is
- If the app is at the domain root `https://helpdesk.example.ch/` → change to `RewriteBase /`

### 4.3 Required Apache modules

- `mod_rewrite` (mandatory — URL rewriting)

---

## 5. Database Setup

Since the current production database will be **overridden**, the approach is to run a fresh migration.

### 5.1 Create the database

Connect to MySQL/MariaDB and create an empty database:

```sql
CREATE DATABASE your_prod_db_name CHARACTER SET utf8 COLLATE utf8_general_ci;
CREATE USER 'your_prod_db_user'@'localhost' IDENTIFIED BY 'your_prod_db_password';
GRANT ALL PRIVILEGES ON your_prod_db_name.* TO 'your_prod_db_user'@'localhost';
FLUSH PRIVILEGES;
```

### 5.2 Run migrations

From the project root directory:

```bash
php spark migrate --all
```

The `--all` flag ensures migrations from **all registered namespaces** (App, User, Helpdesk) are executed.

This will:
1. Create `ci_sessions` table
2. Create `user_type` table + seed 4 user types (Administrateur, Enregistré, Invité, Technicien parrain)
3. Create `user` table + seed admin user
4. Add `azure_mail` column to `user`
5. Create all helpdesk tables (`tbl_assignation`, `tbl_holidays`, `tbl_roles`, `tbl_statuses`, `tbl_terminal`, `tbl_user_data`, `tbl_presences`, `tbl_lw_planning`, `tbl_nw_planning`)
6. Seed reference data (assignations, roles, statuses, terminals)
7. Seed admin user data in `tbl_user_data`
8. Add `updated_at` column to `tbl_presences`

### 5.3 Verify migration

```bash
php spark migrate:status
```

Confirm all 16 migrations show as "migrated" (4 User + 12 Helpdesk).

---

## 6. Writable Permissions

The `writable/` directory must be writable by the web server process:

```bash
chmod -R 775 writable/
chown -R www-data:www-data writable/
```

Ensure these subdirectories exist and are writable:
- `writable/cache/`
- `writable/logs/`
- `writable/session/`
- `writable/uploads/`

---

## 7. Post-Deployment Verification

### 7.1 Checklist

| Check                                           | Command / Action                                       |
|-------------------------------------------------|--------------------------------------------------------|
| App loads without error                         | Open the base URL in a browser                         |
| Login works                                     | Login with `admin` / default password                  |
| Environment is production                       | No debug toolbar visible at bottom of page             |
| Database connection works                       | Navigate to any page that queries data                 |
| Migrations are complete                         | `php spark migrate:status`                             |
| No test users in database                       | Check `user` table — should only have 1 admin user     |
| Sessions work                                   | Login, navigate, confirm you stay logged in            |
| URL rewriting works                             | Clean URLs work (no `index.php` in URL)                |

### 7.2 Change admin password

After first login, **immediately change the admin password**. The seed uses a hardcoded bcrypt hash.

### 7.3 Optional: Update admin email

The seeded admin email is `admin@test.ch`. Update it to a real email address via the admin panel or directly in the database.

---

## 8. Rollback Plan

If the deployment fails:

1. Restore the previous application files from backup.
2. Restore the previous database from backup (if it was backed up before override).
3. Revert the `.env` file to the previous configuration.

**Before deploying, always back up the current production database**, even if it will be overridden.

---

## Summary of Seed Files and Their Status

| Seed File                              | Called By Migration                          | Status              | Contains Test Data?        |
|----------------------------------------|----------------------------------------------|---------------------|----------------------------|
| `AddUserTypeDatas.php` (User)          | `add_user_type` migration                    | ✅ OK               | No — reference data only   |
| `AddUserDatas.php` (User)              | `add_user` migration                         | ✅ OK (commented)   | No — only admin kept       |
| `InsertAssignationsData.php`           | `TblAssignation` migration                   | ✅ OK               | No — reference data only   |
| `InsertRolesData.php`                  | `TblRoles` migration                         | ✅ OK               | No — reference data only   |
| `InsertStatusesData.php`               | `TblStatuses` migration                      | ✅ OK               | No — reference data only   |
| `InsertTerminalData.php`               | `TblTerminal` migration                      | ✅ OK               | No — reference data only   |
| `InsertUserData.php` (Helpdesk)        | `AddFkRoleToUserData` migration              | ✅ OK (commented)   | No — only admin kept       |
| `InsertPresencesData.php` (Helpdesk)   | `TblPresences` migration                     | ✅ OK (empty shell)  | No — empty run() method    |
