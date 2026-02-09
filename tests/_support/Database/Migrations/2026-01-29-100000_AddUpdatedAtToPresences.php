<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

/**
 * Migration de test pour ajouter updated_at à tbl_presences
 * 
 * BASE DE DONNÉES DE TEST : ci4_test
 * Cette migration est utilisée exclusivement pour les tests unitaires et d'intégration.
 * 
 * NOTE : Pour les tests, on ne met PAS à jour les enregistrements existants avec NOW()
 * afin de conserver updated_at = NULL pour simuler des données anciennes.
 */
class AddUpdatedAtToPresences extends Migration
{
    public function up()
    {
        // Check if the column already exists
        if (!$this->db->fieldExists('updated_at', 'tbl_presences')) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            $fields = [
                'updated_at' => [
                    'type'       => 'DATETIME',
                    'null'       => true,
                    'default'    => null,
                ],
            ];

            $this->forge->addColumn('tbl_presences', $fields);

            // For tests, we do NOT update existing records
            // to keep updated_at = NULL to simulate old data
            // (unlike the production migration which updates with NOW())

            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->dropColumn('tbl_presences', 'updated_at');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
