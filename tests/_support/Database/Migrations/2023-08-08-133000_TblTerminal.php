<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour la table tbl_terminal
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 */
class AddTerminal extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->addField(
        [
            'id_terminal' => 
            [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'null'          => false
            ],
            
            'fk_role_terminal' => 
            [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'null'          => false,
            ],

            'tech_available_terminal' => 
            [
                'type'          => 'BOOLEAN',
                'default'       => 1,
            ],
        ]);

        $this->forge->addKey('id_terminal', true);

        $this->forge->addForeignKey('fk_role_terminal', 'tbl_roles', 'id_role');

        // Check if table already exists
        if (!$this->db->tableExists('tbl_terminal')) {
            $this->forge->createTable('tbl_terminal');
        }

        $seeder = \Config\Database::seeder();
        $seeder->call('\Tests\Support\Database\Seeds\TestInsertTerminalData');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_terminal');
    }
}