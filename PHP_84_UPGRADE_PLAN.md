# PHP 8.4 Upgrade Plan

Date: 2026-05-28

This plan upgrades the project to run locally and on Infomaniak with PHP 8.4, while moving CodeIgniter to a supported release line.

## 1. Create an upgrade branch

Start from the current `pre-production` branch and isolate the work:

```powershell
git checkout pre-production
git pull
git checkout -b upgrade/php-84-codeigniter-47
```

## 2. Update Composer constraints

Update `composer.json` first.

Recommended runtime constraints:

```json
"require": {
    "php": ">=8.4 <9.0",
    "codeigniter4/framework": ">=4.7 <5.0",
    "codeigniter4/translations": ">=4.7 <5.0"
}
```

Recommended development constraints:

```json
"require-dev": {
    "fakerphp/faker": "^1.24",
    "mikey179/vfsstream": "^1.6.12",
    "phpunit/phpunit": ">=11.5 <12.0"
}
```

Then run:

```powershell
composer update --with-all-dependencies
composer audit
composer check-platform-reqs
```

Expected runtime upgrades from the PHP 8.4 dry-run:

- `codeigniter4/framework`: `4.3.7` -> `4.7.3`
- `codeigniter4/translations`: `4.6.3` -> `4.7.2`
- `laminas/laminas-escaper`: `2.17.0` -> `2.18.0`
- `psr/log`: `1.1.4` -> `3.0.2`

Expected test tooling upgrade:

- `phpunit/phpunit`: `9.6.29` -> `11.5.x`

## 3. Merge CodeIgniter project files

After Composer updates, merge current CodeIgniter App Starter project files. Do not blindly overwrite local configuration; compare vendor defaults with project-specific settings.

Priority files:

- `spark`
- `public/index.php`
- `app/Config/Routes.php`
- `app/Config/Routing.php` (new file to add)
- `app/Config/Filters.php`
- `app/Config/App.php`
- `app/Config/Session.php`
- `app/Config/Cookie.php`
- `app/Config/Security.php`
- `app/Config/Feature.php`
- `app/Config/Kint.php`
- `app/Config/Database.php`
- `app/Config/Boot/production.php`

Main merge goals:

- Move framework-level routing settings out of `app/Config/Routes.php` into `app/Config/Routing.php`.
- Keep only route definitions in `app/Config/Routes.php`.
- Update filters for CodeIgniter 4.5+ required filters and changed filter order.
- Remove deprecated session, cookie, and CSRF settings from `app/Config/App.php`.
- Keep active session, cookie, and CSRF settings in their dedicated config files.
- Remove Kint v5-only constants from `app/Config/Kint.php`.
- Remove `E_STRICT` from `app/Config/Boot/production.php`.

## 4. Fix PHP 8.4 implicit nullable deprecations

PHP 8.4 deprecates typed parameters with `= null` when the type is not explicitly nullable.

Change this pattern:

```php
public function __construct(ConnectionInterface &$db = null, ValidationInterface $validation = null)
```

To:

```php
public function __construct(?ConnectionInterface &$db = null, ?ValidationInterface $validation = null)
```

Files to update:

- `orif/user/Models/User_model.php`
- `orif/helpdesk/Models/Holidays_model.php`
- `orif/helpdesk/Models/Lw_planning_model.php`
- `orif/helpdesk/Models/Nw_planning_model.php`
- `orif/helpdesk/Models/Planning_model.php`
- `orif/helpdesk/Models/Presences_model.php`
- `orif/helpdesk/Models/Roles_model.php`
- `orif/helpdesk/Models/Terminal_model.php`
- `orif/helpdesk/Models/User_data_model.php`

## 5. Declare dynamic properties

PHP 8.2+ deprecates creating object properties dynamically. Declare these properties explicitly.

Files and properties:

- `app/Controllers/BaseController.php`: `$session`
- `orif/user/Controllers/Auth.php`: `$validation`, `$user_model`, `$db`
- `orif/user/Controllers/Admin.php`: `$validation`, `$user_model`, `$user_type_model`, `$db`
- `orif/welcome/Controllers/Home.php`: `$session`, unless cleanly inherited from `BaseController`

`$access_level` is already declared on `BaseController`, so child assignments are acceptable.

## 6. Run framework smoke checks

After Composer and config merges, run:

```powershell
php -d error_reporting=E_ALL -d display_errors=1 spark
php spark namespaces
php spark routes
php spark migrate:status
```

Lint the project under PHP 8.4:

```powershell
Get-ChildItem -Path app,orif,tests -Recurse -Filter *.php |
  ForEach-Object { php -d error_reporting=E_ALL -d display_errors=1 -l $_.FullName }
```

The target result is no syntax errors, no deprecation warnings, and successful Spark bootstrap.

## 7. Upgrade PHPUnit config and tests

PHPUnit `9` to `11` may require updates in `phpunit.xml.dist` and test code.

Run:

```powershell
vendor\bin\phpunit
```

Fix test failures caused by the PHP/CodeIgniter upgrade only. Avoid unrelated test rewrites in this upgrade branch.

## 8. Functional regression test

Manually verify the high-risk workflows:

- Login and logout
- Admin user management
- Role management
- Helpdesk planning pages
- Presence updates
- Holiday management
- Terminal and technician views
- Admin versus regular-user permission checks
- Migrations and seeders on a clean database copy

## 9. Update documentation

Update PHP and deployment instructions in:

- `README.md`
- `DEPLOYMENT_GUIDE.md`
- `PHP_84_UPGRADE_REVIEW.md`

Documentation should mention:

- PHP `8.4`
- CodeIgniter `4.7.x`
- Required PHP extensions
- Infomaniak deployment command sequence
- Composer install/update expectations

## 10. Prepare Infomaniak deployment

Before deploying, verify the server PHP version and extensions:

```bash
php -v
php -m
```

Install production dependencies:

```bash
composer install --no-dev --optimize-autoloader
```

Check migrations:

```bash
php spark migrate:status
```

Run migrations only after a database backup:

```bash
php spark migrate --all
```

Recommended deployment precautions:

- Take a production database backup before migration.
- Deploy first to a staging path or temporary subdomain if available.
- Keep `CI_ENVIRONMENT = production`.
- Confirm `baseURL`, database, and session settings for Infomaniak.

## Recommended execution order

1. Composer constraints and lock file.
2. CodeIgniter project file merges.
3. PHP 8.4 deprecation fixes.
4. Dynamic property declarations.
5. Spark and lint smoke checks.
6. PHPUnit upgrade and test fixes.
7. Manual regression testing.
8. Documentation updates.
9. Infomaniak deployment.
