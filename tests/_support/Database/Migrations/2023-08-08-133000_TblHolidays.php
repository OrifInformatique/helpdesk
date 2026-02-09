<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour la table tbl_holidays
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 */
class AddHolidays extends Migration
{
    public function up()
    {
        // Check if table already exists
        if (!$this->db->tableExists('tbl_holidays')) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            $this->forge->addField(
            [
                'id_holiday' =>
                [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'auto_increment' => true,
                ],

                'name_holiday' =>
                [
                    'type'           => 'VARCHAR',
                    'constraint'     => 50,
                    'null'           => true,
                ],

                'start_date_holiday' =>
                [
                    'type'           => 'DATETIME',
                    'null'           => true,
                ],

                'end_date_holiday' =>
                [
                    'type'           => 'DATETIME',
                    'null'           => true,
                ],
            ]);

            $this->forge->addKey('id_holiday', true);

            $this->forge->createTable('tbl_holidays');

            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down()
    {
        $this->forge->dropTable('tbl_holidays');
    }
}