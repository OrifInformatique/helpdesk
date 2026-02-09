<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour la table tbl_statuses
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 */
class AddStatuses extends Migration
{
    public function up()
    {
        // Check if table already exists
        if (!$this->db->tableExists('tbl_statuses')) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            $this->forge->addField(
            [
                'id_status' =>
                [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],

                'title_status' =>
                [
                    'type'          => 'VARCHAR',
                    'constraint'    => 50,
                    'null'          => true,
                ],
            ]);

            $this->forge->addKey('id_status', true);

            $this->forge->createTable('tbl_statuses');

            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        }

        $seeder = \Config\Database::seeder();
        $seeder->call('\Tests\Support\Database\Seeds\TestInsertStatusesData');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_statuses');
    }
}