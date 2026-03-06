<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour la table tbl_roles
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 */
class AddRoles extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->addField(
        [
            'id_role' =>
            [
                'type'           => 'INT',
                'constraint'     =>11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            'name_role' =>
            [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => false,
            ],

            'priority_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => false,
            ],

            'min_assignation_first_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 3,
                'unsigned'       => true,
                'null'           => false,
            ],

            'max_assignation_first_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 3,
                'unsigned'       => true,
                'null'           => false,
            ],

            'min_assignation_second_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 3,
                'unsigned'       => true,
                'null'           => false,
            ],

            'max_assignation_second_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 3,
                'unsigned'       => true,
                'null'           => false,
            ],

            'min_assignation_third_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 3,
                'unsigned'       => true,
                'null'           => false,
            ],

            'max_assignation_third_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 3,
                'unsigned'       => true,
                'null'           => false,
            ],

        ]);

        $this->forge->addKey('id_role', true);

        // Check if table already exists
        if (!$this->db->tableExists('tbl_roles')) {
            $this->forge->createTable('tbl_roles');
        }

        $seeder = \Config\Database::seeder();
        $seeder->call('\Tests\Support\Database\Seeds\TestInsertRolesData');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_roles');
    }
}