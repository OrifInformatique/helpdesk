<?php


namespace Migration\Config;


class MigrationConfig extends \CodeIgniter\Config\BaseConfig
{
    public $migrationpass='$2y$10$iKUKZXR.9wIMF.4Kc3hpgeknFJTNUZJr/5rk6ZcCVz2YiVdkz4Tsq';
    public $writablePath=ROOTPATH.'orif/migration/Writable';
    public $migrate_status_not_migrated=0;
    public $migrate_status_migrated=1;
    public $migrate_status_removed=2;
    //public $sessionDriver=
}