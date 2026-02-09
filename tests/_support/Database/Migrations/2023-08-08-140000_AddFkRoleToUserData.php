<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour ajouter fk_role_id à tbl_user_data
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 */
class AddFkRoleToUserData extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Check if column already exists (for existing installations)
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_user_data` LIKE 'fk_role_id'");
        $column_exists = $query->getNumRows() > 0;

        // If column doesn't exist, add it (case of existing database)
        if (!$column_exists) {
            $fields = [
                'fk_role_id' =>
                [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'null'           => true,
                    'after'          => 'fk_user_id',
                ],
            ];

            $this->forge->addColumn('tbl_user_data', $fields);
            $this->forge->addForeignKey('fk_role_id', 'tbl_roles', 'id_role', 'CASCADE', 'CASCADE');
        }

        // Call test seeder TestInsertUserData after all tables are created
        // (this will do nothing if data already exists)
        $seeder = \Config\Database::seeder();
        $seeder->call('\Tests\Support\Database\Seeds\TestInsertUserData');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        
        // Check if column exists before deleting it
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_user_data` LIKE 'fk_role_id'");
        $column_exists = $query->getNumRows() > 0;

        if ($column_exists) {
            $this->forge->dropForeignKey('tbl_user_data', 'tbl_user_data_fk_role_id_foreign');
            $this->forge->dropColumn('tbl_user_data', 'fk_role_id');
        }
        
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}

