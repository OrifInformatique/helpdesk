<?php

namespace Config;

use CodeIgniter\Database\Config;

/**
 * Database Configuration
 */
class Database extends Config
{
    /**
     * The directory that holds the Migrations
     * and Seeds directories.
     */
    public string $filesPath = APPPATH . 'Database' . DIRECTORY_SEPARATOR;

    /**
     * Lets you choose which connection group to
     * use if no other is specified.
     */
    public string $defaultGroup = 'default';

    /**
     * The default database connection.
     * Les valeurs sont lues depuis le fichier .env dans le constructeur
     */
    public array $default = [
        'DSN'      => '',
        'hostname' => 'mariadb',
        'username' => 'ci4_user',
        'password' => 'ci4_password',
        'database' => 'ci4',
        'DBDriver' => 'MySQLi',
        'DBPrefix' => '',
        'pConnect' => false,
        'DBDebug'  => true,
        'charset'  => 'utf8',
        'DBCollat' => 'utf8_general_ci',
        'swapPre'  => '',
        'encrypt'  => false,
        'compress' => false,
        'strictOn' => false,
        'failover' => [],
        'port'     => 3306,
    ];

    /**
     * This database connection is used when
     * running PHPUnit database tests.
     * Les valeurs sont lues depuis le fichier .env dans le constructeur
     */
    public array $tests = [
        'DSN'         => '',
        'hostname'    => 'mariadb',  // Docker service name. For local tests, use '127.0.0.1'
        'username'    => 'ci4_test_user',
        'password'    => 'ci4_test_password',
        'database'    => 'ci4_test',
        'DBDriver'    => 'MySQLi',  // Changed from SQLite3 to MySQLi for Docker
        'DBPrefix'    => '',  // No prefix for tests
        'pConnect'    => false,
        'DBDebug'     => true,
        'charset'     => 'utf8',
        'DBCollat'    => 'utf8_general_ci',
        'swapPre'     => '',
        'encrypt'     => false,
        'compress'    => false,
        'strictOn'    => false,
        'failover'    => [],
        'port'        => 3306,
    ];

    public function __construct()
    {
        parent::__construct();

        // Load values from .env file for default connection
        $this->default['hostname'] = env('database.default.hostname', $this->default['hostname']);
        $this->default['username'] = env('database.default.username', $this->default['username']);
        $this->default['password'] = env('database.default.password', $this->default['password']);
        $this->default['database'] = env('database.default.database', $this->default['database']);
        $this->default['DBDriver'] = env('database.default.DBDriver', $this->default['DBDriver']);
        $this->default['DBPrefix'] = env('database.default.DBPrefix', $this->default['DBPrefix']);
        $this->default['port'] = (int) env('database.default.port', $this->default['port']);

        // Load values from .env file for test connection
        $this->tests['hostname'] = env('database.tests.hostname', $this->tests['hostname']);
        $this->tests['username'] = env('database.tests.username', $this->tests['username']);
        $this->tests['password'] = env('database.tests.password', $this->tests['password']);
        $this->tests['database'] = env('database.tests.database', $this->tests['database']);
        $this->tests['DBDriver'] = env('database.tests.DBDriver', $this->tests['DBDriver']);
        $this->tests['DBPrefix'] = env('database.tests.DBPrefix', $this->tests['DBPrefix']);
        $this->tests['port'] = (int) env('database.tests.port', $this->tests['port']);

        // Ensure that we always set the database group to 'tests' if
        // we are currently running an automated test suite, so that
        // we don't overwrite live data on accident.
        if (ENVIRONMENT === 'testing') {
            $this->defaultGroup = 'tests';
        }
    }
}
