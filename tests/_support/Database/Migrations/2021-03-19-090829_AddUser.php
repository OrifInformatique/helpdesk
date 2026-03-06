<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour la table user
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 * 
 * Cette table doit être créée APRÈS user_type car elle a une clé étrangère vers user_type.
 * Elle doit être créée AVANT toutes les tables qui référencent user (tbl_user_data, tbl_presences, etc.).
 */
class AddUser extends Migration
{
    /**
     * @inheritDoc
     */
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'INT',
                'unsigned'          => true,
                'auto_increment'    => true,
            ],
            'fk_user_type' => [
                'type'              => 'INT',
                'unsigned'          => true,
            ],
            'username' => [
                'type'              => 'VARCHAR',
                'constraint'        => '45',
                'unique'            => true,
            ],
            'password' => [
                'type'              => 'VARCHAR',
                'constraint'        => '255',
            ],
            'email' => [
                'type'              => 'VARCHAR',
                'constraint'        => '100',
                'null'              => true,
                'default'           => null,
            ],
            'archive TIMESTAMP NULL',
            'date_creation TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->addForeignKey('fk_user_type', 'user_type', 'id');
        $this->forge->createTable('user', true);
        
        // Insert base data for tests
        // default password (bcrypt hash for 'password')
        $defaultPassword = '$2y$10$6udFYIjZkuCZshN.h2RlKuF.UeELiKFm7SmFM9Omgoh0HPdmsS2Xy';

        $data = [
            ['fk_user_type' => 1, 'username' => 'admin', 'password' => '$2y$10$84r63xo.M4LVcIi8IvT8cO0qYxyglPshY1jJmKLedRMcaTcxhcVYO', 'email' => 'admin@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Alpha', 'password' => $defaultPassword, 'email' => 'Alpha@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Bravo', 'password' => $defaultPassword, 'email' => 'Bravo@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Charlie', 'password' => $defaultPassword, 'email' => 'Charlie@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Delta', 'password' => $defaultPassword, 'email' => 'Delta@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Echo', 'password' => $defaultPassword, 'email' => 'Echo@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Foxtrot', 'password' => $defaultPassword, 'email' => 'Foxtrot@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Golf', 'password' => $defaultPassword, 'email' => 'Golf@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Hotel', 'password' => $defaultPassword, 'email' => 'Hotel@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_India', 'password' => $defaultPassword, 'email' => 'India@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Juliet', 'password' => $defaultPassword, 'email' => 'Juliet@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Kilo', 'password' => $defaultPassword, 'email' => 'Kilo@test.ch'],
            ['fk_user_type' => 2, 'username' => 'Realistic_Lima', 'password' => $defaultPassword, 'email' => 'Lima@test.ch'],
        ];

        foreach ($data as $row) {
            // Check if user already exists before inserting
            $exists = $this->db->table('user')
                ->where('username', $row['username'])
                ->countAllResults();
            
            if ($exists == 0) {
                $this->db->table('user')->insert($row);
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function down()
    {
        $this->forge->dropTable('user');
    }
}
