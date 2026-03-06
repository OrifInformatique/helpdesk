<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour la table tbl_assignation
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 */
class AddAssignation extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Check if table already exists before creating it
        if (!$this->db->tableExists('tbl_assignation')) {
            $this->forge->addField(
            [
                'id_assignation' =>
                [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],
                
                'title_assignation' =>
                [
                    'type'           => 'VARCHAR',
                    'constraint'     => 50,
                    'null'           => true,
                ],
            ]);

            $this->forge->addKey('id_assignation', true);

            $this->forge->createTable('tbl_assignation');
        }

        // Execute test seeder only if table is empty
        $seeder = \Config\Database::seeder();
        $seeder->call('\Tests\Support\Database\Seeds\TestInsertAssignationsData');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_assignation');
    }
}