<?php

/**
 * Commande Spark pour tester la configuration de la base de données
 * 
 * Utilisation: php spark db:test [default|tests]
 * 
 * Cette commande teste la connexion aux bases de données et affiche
 * les valeurs chargées depuis le fichier .env
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Config\Database;

class TestDatabaseConfig extends BaseCommand
{
    protected $group       = 'Database';
    protected $name        = 'db:test';
    protected $description = 'Teste la connexion aux bases de données et affiche la configuration depuis .env';
    protected $usage       = 'db:test [default|tests]';
    protected $arguments   = [
        'group' => 'Groupe de connexion à tester (default ou tests). Par défaut: les deux'
    ];

    public function run(array $params)
    {
        CLI::write('========================================', 'white');
        CLI::write('TEST DE CONFIGURATION BASE DE DONNÉES', 'white');
        CLI::write('========================================', 'white');
        CLI::newLine();

        $group = $params[0] ?? null;
        $groupsToTest = $group ? [$group] : ['default', 'tests'];

        foreach ($groupsToTest as $groupName) {
            $this->testConnection($groupName);
            CLI::newLine();
        }
    }

    private function testConnection(string $groupName)
    {
        CLI::write("--- Test du groupe: {$groupName} ---", 'cyan');
        CLI::newLine();

        // Get configuration
        $dbConfig = config('Database');
        
        if ($groupName === 'default') {
            $config = $dbConfig->default;
        } elseif ($groupName === 'tests') {
            $config = $dbConfig->tests;
        } else {
            CLI::error("Groupe invalide: {$groupName}. Utilisez 'default' ou 'tests'");
            return;
        }

        // Display configuration
        CLI::write("Configuration chargée:", 'yellow');
        CLI::write("  Hostname: {$config['hostname']}", 'white');
        CLI::write("  Database: {$config['database']}", 'white');
        CLI::write("  Username: {$config['username']}", 'white');
        CLI::write("  Password: " . str_repeat('*', strlen($config['password'])), 'white');
        CLI::write("  Port: {$config['port']}", 'white');
        CLI::write("  DBDriver: {$config['DBDriver']}", 'white');
        CLI::newLine();

        // Test connection
        try {
            CLI::write("Tentative de connexion...", 'yellow');
            $db = Database::connect($groupName);
            
            // Simple query test
            $query = $db->query("SELECT DATABASE() as db_name, USER() as db_user, VERSION() as db_version");
            $result = $query->getRow();
            
            CLI::write("✓ Connexion réussie !", 'green');
            CLI::newLine();
            CLI::write("Informations de connexion:", 'yellow');
            CLI::write("  Base de données connectée: {$result->db_name}", 'white');
            CLI::write("  Utilisateur: {$result->db_user}", 'white');
            CLI::write("  Version MySQL/MariaDB: {$result->db_version}", 'white');
            
            // List tables
            $tables = $db->listTables();
            CLI::newLine();
            CLI::write("Tables dans la base de données (" . count($tables) . "):", 'yellow');
            if (count($tables) > 0) {
                foreach ($tables as $table) {
                    CLI::write("  - {$table}", 'white');
                }
            } else {
                CLI::write("  (Aucune table trouvée)", 'white');
            }
            
        } catch (\Exception $e) {
            CLI::error("✗ Erreur de connexion: " . $e->getMessage());
            CLI::newLine();
            CLI::write("Vérifiez que:", 'yellow');
            CLI::write("  1. Le conteneur Docker MariaDB est démarré", 'white');
            CLI::write("  2. La base de données '{$config['database']}' existe", 'white');
            CLI::write("  3. L'utilisateur '{$config['username']}' existe et a les droits", 'white');
            CLI::write("  4. Les variables dans .env sont correctes", 'white');
            CLI::write("  5. Le hostname '{$config['hostname']}' est accessible depuis le conteneur", 'white');
        }
    }
}
