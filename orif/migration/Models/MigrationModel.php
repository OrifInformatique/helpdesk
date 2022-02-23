<?php
/**
 * Model MigrationModel this represents the migration table
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace Migration\Models;
use CodeIgniter\Database\ConnectionInterface;
use CodeIgniter\Validation\ValidationInterface;

class MigrationModel extends \CodeIgniter\Model{
    protected $table='migrations';
    protected $primaryKey='id';
    protected $allowedFields=[];

}