<?php

/**
 * Spark command to run test migrations
 * 
 * Usage: php spark migrate:test
 * 
 * This command runs migrations from tests/_support/Database/Migrations/
 * using the "tests" connection group (ci4_test database)
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use CodeIgniter\Database\MigrationRunner;
use Config\Database;
use Config\Migrations;

class MigrateTest extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'migrate:test';
    protected $description = 'Runs test migrations from tests/_support/Database/Migrations/ to ci4_test database';
    protected $usage       = 'migrate:test [options]';
    protected $arguments   = [];
    protected $options    = [
        '--all' => 'Execute all migrations (default)',
        '--version' => 'Specific version to migrate',
        '--force' => 'Force re-execution even if tables exist (not recommended)',
    ];

    public function run(array $params)
    {
        CLI::write('========================================', 'white');
        CLI::write('MIGRATIONS DE TEST - Base ci4_test', 'white');
        CLI::write('========================================', 'white');
        CLI::newLine();

        // Check that test migrations path exists
        $testMigrationsPath = ROOTPATH . 'tests' . DIRECTORY_SEPARATOR . '_support' . DIRECTORY_SEPARATOR . 'Database' . DIRECTORY_SEPARATOR . 'Migrations';
        $testDatabasePath = ROOTPATH . 'tests' . DIRECTORY_SEPARATOR . '_support' . DIRECTORY_SEPARATOR . 'Database';
        
        if (!is_dir($testMigrationsPath)) {
            CLI::error("Le chemin des migrations de test n'existe pas: {$testMigrationsPath}");
            return EXIT_ERROR;
        }

        CLI::write("Chemin des migrations: {$testMigrationsPath}", 'cyan');
        CLI::newLine();

        // Get test database configuration
        $dbConfig = config('Database');
        $testConfig = $dbConfig->tests;

        if (empty($testConfig)) {
            CLI::error("La configuration de la base de données de test n'est pas définie dans app/Config/Database.php");
            return EXIT_ERROR;
        }

        CLI::write("Base de données: {$testConfig['database']}", 'cyan');
        CLI::write("Hostname: {$testConfig['hostname']}", 'cyan');
        CLI::write("Utilisateur: {$testConfig['username']}", 'cyan');
        CLI::newLine();

        // Connect to test database
        try {
            $db = Database::connect('tests');
            CLI::write("✓ Connexion à la base de données de test réussie", 'green');
        } catch (\Exception $e) {
            CLI::error("Erreur de connexion à la base de données de test: " . $e->getMessage());
            CLI::write("Vérifiez que:");
            CLI::write("  1. La base de données ci4_test existe", 'yellow');
            CLI::write("  2. L'utilisateur ci4_test_user existe et a les droits", 'yellow');
            CLI::write("  3. Le conteneur Docker MariaDB est démarré", 'yellow');
            return EXIT_ERROR;
        }

        // Get migrations configuration
        $migrationConfig = config('Migrations');
        
        // Check that migration files exist
        $migrationFiles = glob($testMigrationsPath . DIRECTORY_SEPARATOR . '*_*.php');
        
        if (empty($migrationFiles)) {
            CLI::write("Aucun fichier de migration trouvé dans: {$testMigrationsPath}", 'yellow');
            CLI::write("Vérifiez que les fichiers suivent le format: YYYY-MM-DD-HHMMSS_NomDeLaMigration.php", 'yellow');
            return EXIT_SUCCESS;
        }
        
        CLI::write("Fichiers de migration trouvés: " . count($migrationFiles), 'cyan');
        
        // Get force option BEFORE any check
        $force = CLI::getOption('force') !== null;
        $alreadyCleaned = false; // Flag to avoid double cleaning
        
        // If --force is used, ALWAYS clean database BEFORE continuing
        if ($force) {
            CLI::newLine();
            CLI::write("Option --force détectée: nettoyage automatique de la base de données...", 'cyan');
            try {
                $db->query('SET FOREIGN_KEY_CHECKS=0');
                $allTables = $db->listTables();
                if (!empty($allTables)) {
                    foreach ($allTables as $table) {
                        try {
                            $db->query("DROP TABLE IF EXISTS `{$table}`");
                            CLI::write("  ✓ Table {$table} supprimée", 'green');
                        } catch (\Exception $e) {
                            CLI::write("  ✗ Erreur lors de la suppression de {$table}: " . $e->getMessage(), 'red');
                        }
                    }
                    CLI::write("✓ Base de données nettoyée", 'green');
                    CLI::newLine();
                    $alreadyCleaned = true; // Mark that we've already cleaned
                } else {
                    CLI::write("Aucune table à supprimer", 'cyan');
                    CLI::newLine();
                }
                $db->query('SET FOREIGN_KEY_CHECKS=1');
            } catch (\Exception $e) {
                CLI::error("Erreur lors du nettoyage: " . $e->getMessage());
                return EXIT_ERROR;
            }
        }
        
        // Temporarily save the original path
        $originalPath = $dbConfig->filesPath;
        
        // Temporarily modify path to point to test Database directory
        // filesPath must point to parent directory that contains Migrations AND Seeds
        // MigrationRunner will look in filesPath/Migrations and Seeder will look in filesPath/Seeds
        $dbConfig->filesPath = $testDatabasePath . DIRECTORY_SEPARATOR;
        
        CLI::write("Chemin configuré dans Database: {$dbConfig->filesPath}", 'cyan');
        CLI::newLine();
        
        // Ensure migrations table exists BEFORE creating MigrationRunner
        // It may have been deleted during cleanup with --force
        // Use cached=false to force a fresh check after cleanup
        if (!$db->tableExists($migrationConfig->table, false)) {
            CLI::write("Creating migrations table...", 'cyan');
            // Create migrations table manually if it doesn't exist
            // Structure based on create_migrations_table.sql
            $createMigrationsTable = "
                CREATE TABLE IF NOT EXISTS `{$migrationConfig->table}` (
                    `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                    `version` VARCHAR(255) NOT NULL,
                    `class` VARCHAR(255) NOT NULL,
                    `group` VARCHAR(255) NOT NULL DEFAULT 'default',
                    `namespace` VARCHAR(255) NOT NULL DEFAULT 'App',
                    `time` INT(11) NOT NULL,
                    `batch` INT(11) UNSIGNED NOT NULL
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
            ";
            $db->query($createMigrationsTable);
            
            // Force refresh connection to ensure table is visible
            // This is important after --force cleanup
            $db->reconnect();
            
            // Verify that the table was created successfully (without cache)
            if (!$db->tableExists($migrationConfig->table, false)) {
                CLI::error("Failed to create migrations table!");
                CLI::write("Trying to verify table existence with direct query...", 'yellow');
                // Try direct query
                $checkTable = $db->query("SHOW TABLES LIKE '{$migrationConfig->table}'");
                if ($checkTable->getNumRows() === 0) {
                    CLI::error("Table migrations does not exist after creation!");
                    return EXIT_ERROR;
                } else {
                    CLI::write("Table exists according to direct query, but tableExists() returns false", 'yellow');
                    CLI::write("This may be a cache issue. Continuing anyway...", 'yellow');
                }
            }
            
            CLI::write("✓ Table migrations créée et vérifiée", 'green');
            CLI::newLine();
        } else {
            CLI::write("✓ Table migrations existe déjà", 'green');
            CLI::newLine();
        }
        
        try {
            // Create MigrationRunner instance with migrations configuration
            // MigrationRunner expects Config\Migrations as first argument, then DB connection
            $runner = new MigrationRunner($migrationConfig, $db);
            
            // Use reflection to directly modify path in MigrationRunner
            // because it reads Database::$filesPath at instantiation time
            $reflection = new \ReflectionClass($runner);
            
            // MigrationRunner stores path in a private property
            // In PHP 8.1+, setAccessible() is deprecated but still necessary for private properties
            // We use an approach that avoids the warning
            $setPathProperty = function($propertyName) use ($reflection, $runner, $testDatabasePath) {
                if ($reflection->hasProperty($propertyName)) {
                    $property = $reflection->getProperty($propertyName);
                    // For PHP 8.1+, setAccessible() generates a warning but is still necessary
                    // We use error_reporting to temporarily suppress the warning
                    $oldErrorReporting = error_reporting(E_ALL & ~E_DEPRECATED);
                    try {
                        $property->setAccessible(true);
                        // MigrationRunner looks in path/Migrations, so we must give it the parent path
                        // that contains both Migrations and Seeds
                        $property->setValue($runner, $testDatabasePath . DIRECTORY_SEPARATOR);
                        return true;
                    } finally {
                        error_reporting($oldErrorReporting);
                    }
                }
                return false;
            };
            
            if ($setPathProperty('path')) {
                CLI::write("Path modified in MigrationRunner via reflection", 'cyan');
            } elseif ($setPathProperty('filesPath')) {
                CLI::write("Path modified in MigrationRunner via reflection (filesPath)", 'cyan');
            }
            
            // Set 'tests' group for MigrationRunner using setGroup() method
            // MigrationRunner must use 'tests' group to register migrations correctly
            $runner->setGroup('tests');
            CLI::write("Groupe 'tests' configuré dans MigrationRunner", 'cyan');
            
            $runner->setNamespace('App\\Database\\Migrations'); // Test migrations namespace
            
            // Check that there are migrations
            $migrations = $runner->findMigrations();
            
            if (empty($migrations)) {
                CLI::write("No migrations found by MigrationRunner", 'yellow');
                CLI::write("Expected path: {$testMigrationsPath}", 'yellow');
                
                // Display found files for debug
                CLI::write("Fichiers trouvés dans le répertoire:", 'yellow');
                foreach (array_slice($migrationFiles, 0, 5) as $file) {
                    CLI::write("  - " . basename($file), 'white');
                }
                if (count($migrationFiles) > 5) {
                    CLI::write("  ... et " . (count($migrationFiles) - 5) . " autres", 'white');
                }
                
                CLI::write("Vérifiez le format des timestamps dans les noms de fichiers", 'yellow');
                CLI::write("Format attendu: {$migrationConfig->timestampFormat}", 'yellow');
                return EXIT_SUCCESS;
            }

            CLI::write("Migrations trouvées: " . count($migrations), 'cyan');
            
            // Check current database state
            $migrationsTable = $migrationConfig->table;
            $existingTables = [];
            $existingMigrationsCount = 0;
            
            // If --force was used, we already cleaned everything, so skip orphan detection
            if (!$force) {
                // Check which tables already exist
                $allTables = $db->listTables();
                
                // Filter to exclude migrations table itself
                // Any other table is considered "orphan" if no migration is registered
                $existingTables = array_filter($allTables, function($table) use ($migrationsTable) {
                    return $table !== $migrationsTable;
                });
                
                // Convert to indexed array for easier display
                $existingTables = array_values($existingTables);
                
                // Check registered migrations
                $hasMigrationsTable = $db->tableExists($migrationsTable);
                if ($hasMigrationsTable) {
                    try {
                        $existingMigrations = $db->table($migrationsTable)
                            ->where('group', 'tests')
                            ->get()
                            ->getResultArray();
                        $existingMigrationsCount = count($existingMigrations);
                        CLI::write("Migrations déjà exécutées: {$existingMigrationsCount}", 'cyan');
                    } catch (\Exception $e) {
                        CLI::write("Erreur lors de la lecture de la table migrations: " . $e->getMessage(), 'yellow');
                        $existingMigrationsCount = 0;
                    }
                } else {
                    CLI::write("Aucune migration précédente enregistrée (table migrations n'existe pas)", 'cyan');
                }
                
                // Display detected tables for debug
                if (!empty($existingTables)) {
                    CLI::write("Existing tables detected: " . implode(', ', $existingTables), 'yellow');
                }
            } else {
                // With --force, we cleaned the database, so no registered migrations
                CLI::write("Database cleaned with --force: no previous migrations", 'cyan');
                $existingMigrationsCount = 0;
                $existingTables = []; // No orphan tables after --force cleanup
            }
            
            // If tables exist but no migration is registered, it's a problem
            // But if we already cleaned with --force, don't clean again
            if ((!empty($existingTables) && $existingMigrationsCount === 0) && !$alreadyCleaned) {
                CLI::newLine();
                CLI::write("ATTENTION: Tables orphelines détectées!", 'red');
                CLI::write("Les tables suivantes existent mais aucune migration n'est enregistrée:", 'yellow');
                CLI::write("  " . implode(', ', $existingTables), 'yellow');
                CLI::newLine();
                
                if ($force) {
                    CLI::write("Option --force détectée: nettoyage automatique de la base de données...", 'cyan');
                } elseif (!$alreadyCleaned) {
                    CLI::write("Ces tables seront supprimées pour permettre l'exécution propre des migrations.", 'cyan');
                    CLI::newLine();
                    // Ask for confirmation before deleting
                    $response = CLI::prompt('Voulez-vous supprimer les tables orphelines et continuer?', ['oui', 'non'], 'oui');
                    if ($response !== 'oui') {
                        CLI::write("Opération annulée.", 'yellow');
                        CLI::write("Utilisez .\\clean-test-db.ps1 pour nettoyer complètement la base.", 'yellow');
                        CLI::write("Ou utilisez --force pour nettoyer automatiquement: php spark migrate:test --force", 'yellow');
                        return EXIT_SUCCESS;
                    }
                }
                
                // Supprimer les tables orphelines
                CLI::write("Suppression des tables orphelines...", 'cyan');
                try {
                    $db->query('SET FOREIGN_KEY_CHECKS=0');
                    
                    // Delete all tables in database (including migrations if it exists)
                    // to have a completely clean database
                    $allTables = $db->listTables();
                    foreach ($allTables as $table) {
                        try {
                            $db->query("DROP TABLE IF EXISTS `{$table}`");
                            CLI::write("  ✓ Table {$table} supprimée", 'green');
                        } catch (\Exception $e) {
                            CLI::write("  ✗ Erreur lors de la suppression de {$table}: " . $e->getMessage(), 'red');
                        }
                    }
                    
                    $db->query('SET FOREIGN_KEY_CHECKS=1');
                    CLI::write("✓ Tables orphelines supprimées", 'green');
                    CLI::newLine();
                    
                    // Reset variables after cleanup
                    $existingTables = [];
                    $existingMigrationsCount = 0;
                    $alreadyCleaned = true; // Mark that we cleaned
                } catch (\Exception $e) {
                    CLI::error("Erreur lors de la suppression des tables: " . $e->getMessage());
                    return EXIT_ERROR;
                }
            } elseif (!empty($existingTables) && $existingMigrationsCount > 0) {
                CLI::write("Tables existantes détectées: " . implode(', ', $existingTables), 'yellow');
                CLI::write("Ces tables existent déjà avec des migrations enregistrées.", 'yellow');
                CLI::newLine();
            }
            
            CLI::newLine();

            // Execute migrations
            $version = CLI::getOption('version');
            
            if ($version !== null) {
                CLI::write("Migrating to version: {$version}", 'cyan');
                try {
                    $runner->version($version, 'tests');
                } catch (\mysqli_sql_exception $e) {
                    // Handle existing table errors
                    if (strpos($e->getMessage(), 'already exists') !== false) {
                        CLI::write("ATTENTION: Certaines tables existent déjà", 'yellow');
                        CLI::write("Utilisez --force pour forcer la réexécution ou videz la base de données", 'yellow');
                        throw $e;
                    }
                    throw $e;
                }
            } else {
                CLI::write("Executing all migrations...", 'cyan');
                
                // Double-check that migrations table exists before calling latest()
                // Use cached=false to force a fresh check
                if (!$db->tableExists($migrationConfig->table, false)) {
                    CLI::write("Migrations table not found, attempting to create it...", 'yellow');
                    // Try to create it again
                    $createMigrationsTable = "
                        CREATE TABLE IF NOT EXISTS `{$migrationConfig->table}` (
                            `id` INT(11) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
                            `version` VARCHAR(255) NOT NULL,
                            `class` VARCHAR(255) NOT NULL,
                            `group` VARCHAR(255) NOT NULL DEFAULT 'default',
                            `namespace` VARCHAR(255) NOT NULL DEFAULT 'App',
                            `time` INT(11) NOT NULL,
                            `batch` INT(11) UNSIGNED NOT NULL
                        ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
                    ";
                    $db->query($createMigrationsTable);
                    $db->reconnect();
                    
                    // Check again without cache
                    if (!$db->tableExists($migrationConfig->table, false)) {
                        CLI::error("Migrations table does not exist! Cannot proceed.");
                        CLI::write("This should not happen. Please report this issue.", 'yellow');
                        return EXIT_ERROR;
                    }
                    CLI::write("✓ Table migrations créée avec succès", 'green');
                }
                
                try {
                    // Execute migrations with 'tests' group
                    $result = $runner->latest('tests');
                    
                    // Fix: Update migrations that were registered with 'default' group to 'tests'
                    // This is necessary because MigrationRunner may use the default connection group
                    if ($db->tableExists($migrationConfig->table)) {
                        // Get the latest batch number to update only recently executed migrations
                        $latestBatch = $db->table($migrationConfig->table)
                            ->selectMax('batch')
                            ->get()
                            ->getRow();
                        $maxBatch = $latestBatch ? $latestBatch->batch : 0;
                        
                        // Update only migrations from the latest batch that have 'default' group
                        // This ensures we only update migrations that were just executed
                        if ($maxBatch > 0) {
                            $db->table($migrationConfig->table)
                                ->where('group', 'default')
                                ->where('batch', $maxBatch)
                                ->update(['group' => 'tests']);
                        }
                        
                        // Check that migrations have been registered
                        $registeredMigrations = $db->table($migrationConfig->table)
                            ->where('group', 'tests')
                            ->get()
                            ->getResultArray();
                        CLI::write("Migrations enregistrées après exécution: " . count($registeredMigrations), 'cyan');
                        
                        if (count($registeredMigrations) > 0) {
                            CLI::write("Dernières migrations enregistrées:", 'cyan');
                            foreach (array_slice($registeredMigrations, -5) as $migration) {
                                CLI::write("  - {$migration['version']}: {$migration['class']}", 'white');
                            }
                        }
                    }
                } catch (\mysqli_sql_exception $e) {
                    // Handle existing table errors
                    if (strpos($e->getMessage(), 'already exists') !== false) {
                        CLI::newLine();
                        CLI::write("ERROR: Table already exists: " . $this->extractTableName($e->getMessage()), 'red');
                        CLI::write("This indicates the database contains orphan tables.", 'yellow');
                        CLI::newLine();
                        
                        // Check migrations table state
                        $migrationsTable = $migrationConfig->table;
                        $migrationsCount = 0;
                        try {
                            if ($db->tableExists($migrationsTable)) {
                                $migrationsCount = $db->table($migrationsTable)->countAllResults();
                                CLI::write("Migrations enregistrées dans la table '{$migrationsTable}': {$migrationsCount}", 'cyan');
                            } else {
                                CLI::write("La table '{$migrationsTable}' n'existe pas encore", 'yellow');
                            }
                        } catch (\Exception $checkError) {
                            CLI::write("Impossible de vérifier la table migrations: " . $checkError->getMessage(), 'yellow');
                        }
                        
                        CLI::newLine();
                        CLI::write("Solution: Nettoyer la base de données et réessayer", 'cyan');
                        CLI::newLine();
                        
                        if (!$force) {
                            $response = CLI::prompt('Voulez-vous nettoyer automatiquement la base de données maintenant?', ['oui', 'non'], 'oui');
                            if ($response === 'oui') {
                                CLI::write("Nettoyage de la base de données...", 'cyan');
                                try {
                                    $db->query('SET FOREIGN_KEY_CHECKS=0');
                                    $allTables = $db->listTables();
                                    foreach ($allTables as $table) {
                                        try {
                                            $db->query("DROP TABLE IF EXISTS `{$table}`");
                                            CLI::write("  ✓ Table {$table} supprimée", 'green');
                                        } catch (\Exception $dropError) {
                                            CLI::write("  ✗ Erreur lors de la suppression de {$table}: " . $dropError->getMessage(), 'red');
                                        }
                                    }
                                    $db->query('SET FOREIGN_KEY_CHECKS=1');
                                    CLI::write("✓ Base de données nettoyée", 'green');
                                    CLI::newLine();
                                    CLI::write("Réexécution des migrations...", 'cyan');
                                    // Retry after cleanup
                                    $runner->latest('tests');
                                    CLI::write("✓ Migrations de test exécutées avec succès", 'green');
                                    $dbConfig->filesPath = $originalPath;
                                    return EXIT_SUCCESS;
                                } catch (\Exception $cleanError) {
                                    CLI::error("Erreur lors du nettoyage: " . $cleanError->getMessage());
                                    throw $e;
                                }
                            }
                        }
                        
                        CLI::write("Solutions alternatives:", 'cyan');
                        CLI::write("1. Nettoyer manuellement la base de données:", 'yellow');
                        CLI::write("   .\\clean-test-db.ps1", 'white');
                        CLI::write("   Puis réexécutez: php spark migrate:test", 'white');
                        CLI::newLine();
                        CLI::write("2. Utiliser l'option --force pour nettoyer automatiquement:", 'yellow');
                        CLI::write("   php spark migrate:test --force", 'white');
                        CLI::newLine();
                        throw $e;
                    }
                    throw $e;
                }
            }
            
            CLI::newLine();
            CLI::write("✓ Migrations de test exécutées avec succès", 'green');
            
            // Final check: display all registered migrations
            if ($db->tableExists($migrationConfig->table)) {
                $allMigrations = $db->table($migrationConfig->table)
                    ->where('group', 'tests')
                    ->orderBy('id', 'ASC')
                    ->get()
                    ->getResultArray();
                
                if (count($allMigrations) > 0) {
                    CLI::write("`nMigrations enregistrées dans la base de données:", 'cyan');
                    foreach ($allMigrations as $migration) {
                        CLI::write("  ✓ {$migration['version']}: {$migration['class']}", 'green');
                    }
                } else {
                    CLI::write("`nATTENTION: Aucune migration enregistrée avec le groupe 'tests'", 'yellow');
                    CLI::write("Vérifiez que MigrationRunner utilise bien le groupe 'tests'", 'yellow');
                    
                    // Display all migrations for debug
                    $allMigrationsAnyGroup = $db->table($migrationConfig->table)
                        ->orderBy('id', 'ASC')
                        ->get()
                        ->getResultArray();
                    if (count($allMigrationsAnyGroup) > 0) {
                        CLI::write("Migrations trouvées (tous groupes):", 'yellow');
                        foreach ($allMigrationsAnyGroup as $migration) {
                            CLI::write("  - [{$migration['group']}] {$migration['version']}: {$migration['class']}", 'white');
                        }
                    }
                }
            }
            
            // Restore the original path
            $dbConfig->filesPath = $originalPath;
            
            return EXIT_SUCCESS;
            
        } catch (\Exception $e) {
            // Restore the original path in case of error
            $dbConfig->filesPath = $originalPath;
            
            CLI::error("Erreur lors de l'exécution des migrations: " . $e->getMessage());
            CLI::write($e->getTraceAsString(), 'red');
            return EXIT_ERROR;
        }
    }


}
