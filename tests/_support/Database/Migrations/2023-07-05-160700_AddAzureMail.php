<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour ajouter la colonne azure_mail à la table user
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 */
class AddAzureMail extends Migration
{
    public function up()
    {
        // Check if column already exists
        if (!$this->db->fieldExists('azure_mail', 'user')) {
            $fields = [
                'azure_mail' => [
                    'type'              => 'VARCHAR',
                    'constraint'        => '100',
                    'null'              => true,
                    'default'           => null,
                    'after'             => 'email',
                ],
            ];
            
            $this->forge->addColumn('user', $fields);
        }
    }

    /**
     * @inheritDoc
     */
    public function down()
    {
        $this->forge->dropColumn('user', 'azure_mail');
    }
}
