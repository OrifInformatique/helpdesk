<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour la table user_type
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 * 
 * Cette table doit être créée AVANT la table user car user a une clé étrangère vers user_type.
 */
class AddUserType extends Migration
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
            'name' => [
                'type'              => 'VARCHAR',
                'constraint'        => '45',
                'null'              => false,
            ],
            'access_level' => [
                'type'              => 'INT',
                'null'              => false,
            ]
        ]);
        
        $this->forge->addKey('id', true);
        $this->forge->createTable('user_type', true);
        
        // Insert base data for tests
        $data = [
            ['name' => 'Administrateur', 'access_level' => 4],
            ['name' => 'Enregistré', 'access_level' => 2],
            ['name' => 'Invité', 'access_level' => 1],
            ['name' => 'Technicien parrain', 'access_level' => 3]
        ];
        
        foreach ($data as $row) {
            $this->db->table('user_type')->insert($row);
        }
    }

    /**
     * @inheritDoc
     */
    public function down()
    {
        $this->forge->dropTable('user_type');
    }
}
