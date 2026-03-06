<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour la table ci_sessions
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 * 
 * Cette table est utilisée par CodeIgniter pour stocker les sessions.
 * Elle doit être créée en premier car elle n'a pas de dépendances.
 */
class AddCiSessions extends Migration
{
    /**
     * @inheritDoc
     */
    public function up()
    {
        $this->forge->addField([
            'id' => [
                'type'              => 'VARCHAR',
                'constraint'        => '128',
                'null'              => false
            ],
            'ip_address' => [
                'type'              => 'VARCHAR',
                'constraint'        => '45',
                'null'              => false
            ],
            'timestamp TIMESTAMP DEFAULT CURRENT_TIMESTAMP NOT NULL',
            'data' => [
                'type'              => 'blob',
                'null'              => false
            ]
        ]);

        $this->forge->addKey('id', TRUE);
        $this->forge->addKey('timestamp');
        $this->forge->createTable('ci_sessions', true);
    }

    /**
     * @inheritDoc
     */
    public function down()
    {
        $this->forge->dropTable('ci_sessions');
    }
}
