<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour la table tbl_presences
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 */
class AddPresences extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $fields = 
        [
            'presence_mon_m1', 'presence_mon_m2', 'presence_mon_a1', 'presence_mon_a2',
            'presence_tue_m1', 'presence_tue_m2', 'presence_tue_a1', 'presence_tue_a2',
            'presence_wed_m1', 'presence_wed_m2', 'presence_wed_a1', 'presence_wed_a2',
            'presence_thu_m1', 'presence_thu_m2', 'presence_thu_a1', 'presence_thu_a2',
            'presence_fri_m1', 'presence_fri_m2', 'presence_fri_a1', 'presence_fri_a2'
        ];

        $this->forge->addField(
        [
            'id_presence' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            'fk_user_id' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
        ]);

        $this->forge->addKey('id_presence', true);

        $this->forge->addForeignKey('fk_user_id', 'user', 'id');

        foreach($fields as $field)
        {
            $this->forge->addField(
            [
                $field =>
                [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ]
            ]);

            $this->forge->addForeignKey($field, 'tbl_statuses', 'id_status');
        }

        // Check if table already exists
        if (!$this->db->tableExists('tbl_presences')) {
            $this->forge->createTable('tbl_presences');
        }

        // Use test seeder for ci4_test database
        $seeder = \Config\Database::seeder();
        $seeder->call('\Tests\Support\Database\Seeds\TestInsertPresencesData');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_presences');
    }
}