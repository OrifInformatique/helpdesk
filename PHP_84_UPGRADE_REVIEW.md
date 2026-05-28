# PHP 8.4 Upgrade Review

Date: 2026-05-28

## Executive summary

The PHP 8.4 upgrade has been implemented on this branch. The root Composer platform constraint now targets PHP 8.4, and the project has moved from CodeIgniter 4.3.7 to the maintained CodeIgniter 4.7.x line.

Current target: PHP `>=8.4 <9.0`, CodeIgniter `>=4.7 <5.0`, and PHPUnit `>=11.5 <12.0` for development/test tooling.

## Implementation status

- Composer dependencies were updated to `codeigniter4/framework` 4.7.3, `codeigniter4/translations` 4.7.2, `phpunit/phpunit` 11.5.55, `psr/log` 3.0.2, and `laminas/laminas-escaper` 2.18.0.
- CodeIgniter 4.7 starter config changes were merged into `spark`, `public/index.php`, routing, filters, app/session/cookie/security, feature, Kint, database, and production boot config.
- PHP 8.4 implicit-nullable model constructors were fixed.
- PHP 8.2+ dynamic controller properties were declared or removed where inherited.
- `phpunit.xml.dist` was upgraded to a PHPUnit 11-compatible schema.
- `composer audit` reports no security advisories, and `composer check-platform-reqs` passes under PHP 8.4.12.
- `php spark`, `php spark namespaces`, `php spark routes`, and PHP lint across `app`, `orif`, and `tests` pass locally.
- `php spark migrate:status` reaches the database layer but cannot complete in the current shell because the configured `mariadb` host is not resolvable outside the Docker network.

## Original blockers addressed

### Local PHP 8.4 check

The local CLI is now using PHP 8.4:

- `php -v`: PHP 8.4.12
- Composer uses `C:\laragon\bin\php\php-8.4.12-nts-Win32-vs17-x64\php.exe`
- `composer validate --no-check-publish`: valid, with a warning that the exact `codeigniter4/framework` pin should be avoided.

Runtime status after the upgrade:

- `php spark` boots and lists CodeIgniter 4.7.3 commands under PHP 8.4.
- `php spark namespaces` and `php spark routes` show no warning/error output under full error reporting.
- `composer check-platform-reqs` passes.
- PHP lint under `app`, `orif`, and `tests` found no syntax errors and no PHP 8.4 implicit-nullable deprecations.

### 1. Composer root platform blocked PHP 8.4

Previous `composer.json`:

- `php`: `^8.0 <8.2`
- `codeigniter4/framework`: `4.3.7`
- `codeigniter4/translations`: `^4.3`

Current `composer.json`:

- `php`: `>=8.4 <9.0`
- `codeigniter4/framework`: `>=4.7 <5.0`
- `codeigniter4/translations`: `>=4.7 <5.0`

Original command result:

- `composer why-not php 8.4.0` reports: root package requires `php (^8.0 <8.2)`.

### 2. CodeIgniter 4.3.7 is too old for PHP 8.4 support

Official CodeIgniter requirements state:

- PHP 8.4 requires CodeIgniter 4.6.0 or later.
- PHP 8.5 requires CodeIgniter 4.7.0 or later.
- Only the latest CodeIgniter version is maintained.

Current installed framework:

- `codeigniter4/framework`: 4.3.7, released in 2023.

Available Composer targets checked locally:

- `codeigniter4/framework` 4.6.5 requires PHP `^8.1`, `psr/log` `^3.0`, `laminas/laminas-escaper` `^2.17`.
- `codeigniter4/framework` 4.7.3 requires PHP `^8.2`, `psr/log` `^3.0`, `laminas/laminas-escaper` `^2.18`.

### 3. Previous package set had security advisories

Before the upgrade, `composer audit` reported five advisories affecting two packages:

- `codeigniter4/framework` 4.3.7 has four advisories, including one critical advisory fixed in `>=4.6.2`.
- `phpunit/phpunit` 9.6.29 has one high-severity advisory fixed in newer PHPUnit lines.

After the upgrade, `composer audit` reports no security vulnerability advisories.

### 4. Composer dry-run results on PHP 8.4

With the PHP root constraint changed to PHP 8.4, Composer can resolve the preferred runtime upgrade:

- `codeigniter4/framework`: 4.3.7 -> 4.7.3
- `codeigniter4/translations`: 4.6.3 -> 4.7.2
- `laminas/laminas-escaper`: 2.17.0 -> 2.18.0
- `psr/log`: 1.1.4 -> 3.0.2

With the PHP root constraint changed to PHP 8.4, Composer can also resolve a dev tooling upgrade:

- `phpunit/phpunit`: 9.6.29 -> 11.5.55
- PHPUnit dependency tree updates to current `sebastian/*`, `phpunit/*`, `nikic/php-parser`, and `theseer/tokenizer` versions.
- `staabm/side-effects-detector` is added.
- `doctrine/instantiator` and `sebastian/resource-operations` are removed.

## Recommended Composer changes

### Preferred path: latest maintained CodeIgniter

Use this if local development can move to PHP 8.2+:

- `php`: `^8.2` or `^8.4`
- `codeigniter4/framework`: `^4.7`
- `codeigniter4/translations`: `^4.7`
- `phpunit/phpunit`: `^11.5` for development/tests

Why: aligns with the maintained CodeIgniter release line and is compatible with PHP 8.4.

Equivalent non-caret constraints, useful if the Windows shell strips `^`, are:

- `php`: `>=8.4 <9.0`
- `codeigniter4/framework`: `>=4.7 <5.0`
- `codeigniter4/translations`: `>=4.7 <5.0`
- `phpunit/phpunit`: `>=11.5 <12.0`

### Minimum-change path: PHP 8.4 support with less local disruption

Use this if the local development CLI must stay on PHP 8.1 temporarily:

- `php`: `^8.1`
- `codeigniter4/framework`: `^4.6.5`
- `codeigniter4/translations`: `^4.6`
- `phpunit/phpunit`: `^10.5 || ^11.2`

Why: CodeIgniter 4.6 is the minimum line documented as PHP 8.4-compatible, but it is not the latest maintained release.

## CodeIgniter project files that need review/merge

The current project still contains several older App Starter config patterns. When upgrading CodeIgniter, merge the current framework App Starter versions rather than only changing Composer.

High-priority files:

- `public/index.php` and `spark`: mandatory updates in CodeIgniter 4.4 and 4.5 upgrade guides.
- `app/Config/Routing.php`: missing in this project; routing settings are still in `app/Config/Routes.php`.
- `app/Config/Routes.php`: move framework-level routing settings to `Routing.php`; keep only route definitions.
- `app/Config/Filters.php`: CodeIgniter 4.5+ changes the base class and introduces required filters; also review filter execution order.
- `app/Config/App.php`: still contains deprecated session, cookie, and CSRF settings that should be moved/removed in favor of `Session.php`, `Cookie.php`, and `Security.php`.
- `app/Config/Feature.php`: old flags differ from 4.5/4.6 defaults and new flags.
- `app/Config/Kint.php`: current file uses `Kint\Renderer\AbstractRenderer::SORT_FULL`; CodeIgniter 4.6 upgrade notes say Kint v6 removed this constant, so leaving this can cause runtime errors.
- `app/Config/Database.php`: merge newer defaults, especially `utf8mb4` charset/collation recommendations.
- `app/Config/Security.php`: remove deprecated `$samesite`; use `Config\Cookie::$samesite`.
- `app/Config/Boot/production.php`: remove `E_STRICT`; CodeIgniter 4.5 default is `E_ALL & ~E_DEPRECATED`.

## Application code issues to update for PHP 8.4

### 1. Implicit nullable parameter declarations

PHP 8.4 deprecates typed parameters with `= null` unless the type is explicitly nullable. The scan found these model constructors:

- `orif/user/Models/User_model.php`
- `orif/helpdesk/Models/Holidays_model.php`
- `orif/helpdesk/Models/Lw_planning_model.php`
- `orif/helpdesk/Models/Nw_planning_model.php`
- `orif/helpdesk/Models/Planning_model.php`
- `orif/helpdesk/Models/Presences_model.php`
- `orif/helpdesk/Models/Roles_model.php`
- `orif/helpdesk/Models/Terminal_model.php`
- `orif/helpdesk/Models/User_data_model.php`

Fixed and confirmed by PHP lint under PHP 8.4.12.

Current pattern:

- `ConnectionInterface &$db = null`
- `ValidationInterface $validation = null`

Recommended pattern:

- `?ConnectionInterface &$db = null`
- `?ValidationInterface $validation = null`

### 2. Dynamic properties

PHP 8.2+ deprecates creating dynamic properties. The project already has a comment about this in `BaseController`, but `$session` is commented out and then assigned dynamically.

Declare properties explicitly in these areas:

- `app/Controllers/BaseController.php`: declare `$session`.
- `orif/welcome/Controllers/Home.php`: declare `$session`, or rely on a declared parent property.
- `orif/user/Controllers/Auth.php`: declare `$validation`, `$user_model`, `$db`.
- `orif/user/Controllers/Admin.php`: declare `$validation`, `$user_model`, `$user_type_model`, `$db`.

The `$access_level` property is already declared in `BaseController`, so child assignments are not a problem.

### 3. Deprecated constants/functions scan

The scan found no application use of common removed/deprecated functions such as `utf8_encode()`, `utf8_decode()`, `strftime()`, `create_function()`, `each()`, or `FILTER_SANITIZE_STRING`.

One deprecated constant usage was found:

- `app/Config/Boot/production.php` uses `E_STRICT`; remove it during the config merge.

## CodeIgniter behavior changes to test after upgrade

Focus regression testing on these areas:

- Routing and modules: `app/Config/Routes.php`, module routes under `orif/*/Config/Routes.php`, and auto-routing behavior.
- Filters and permissions: `BaseController::check_permission()` and admin/user route access, because CodeIgniter 4.5 changed filter execution order.
- Sessions: CodeIgniter 4.6 forces PHP default 32-character session IDs.
- Validation: CodeIgniter 4.4 introduced security-related guidance around retrieving validated data; review controllers that use validation services.
- Time/date behavior: CodeIgniter 4.6 changed some `Time` behavior; this project mostly uses PHP `strtotime()`/`date()`, but planning and holidays should be tested.
- Response headers: CodeIgniter 4.6 changes how Response headers replace headers set by PHP.

## Infomaniak deployment checklist

Before deployment on the server, verify PHP 8.4 extensions:

- Required by CodeIgniter: `intl`, `mbstring`.
- Required for MySQL/MariaDB use: `mysqli` and ideally `mysqlnd`.
- Commonly needed for Composer/app operation: `json`, `zip`, `fileinfo`, `curl`.
- Needed for PHPUnit/TestResponse only if tests are run on server: `dom`, `libxml`.

Deployment flow after the upgrade:

1. Update Composer constraints and lock file in a PHP-compatible environment.
2. Merge CodeIgniter App Starter project-file changes.
3. Fix PHP 8.4 deprecations listed above.
4. Run tests and at least `php spark` locally with the target PHP version.
5. On Infomaniak, install production dependencies without dev packages and with optimized autoloading.
6. Run migrations/status checks only after dependencies load successfully.
7. Keep `CI_ENVIRONMENT = production` and set the production `baseURL`/database/session configuration.

## Priority order

1. Update Composer constraints and framework version.
2. Merge CodeIgniter project files (`spark`, `public/index.php`, configs).
3. Fix PHP 8.4 deprecations in model constructors and dynamic properties.
4. Upgrade test tooling.
5. Run regression tests for login, permissions, routes, planning, migrations, and sessions.
