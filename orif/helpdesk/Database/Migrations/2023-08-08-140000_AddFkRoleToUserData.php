<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddFkRoleToUserData extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        // Vérifier si la colonne existe déjà (pour les installations existantes)
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_user_data` LIKE 'fk_role_id'");
        $columnExists = $query->getNumRows() > 0;

        // Si la colonne n'existe pas, l'ajouter (cas d'une base existante)
        if (!$columnExists) {
            $fields = [
                'fk_role_id' =>
                [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'unsigned'       => true,
                    'null'           => true,
                    'after'          => 'fk_user_id',
                ],
            ];

            $this->forge->addColumn('tbl_user_data', $fields);
            $this->forge->addForeignKey('fk_role_id', 'tbl_roles', 'id_role', 'CASCADE', 'CASCADE');
        }

        // Appeler le seeder InsertUserData après que toutes les tables soient créées
        // (cela ne fera rien si les données existent déjà)
        $seeder = \Config\Database::seeder();
        $seeder->call('\Helpdesk\Database\Seeds\InsertUserData');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');
        
        // Vérifier si la colonne existe avant de la supprimer
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_user_data` LIKE 'fk_role_id'");
        $columnExists = $query->getNumRows() > 0;

        if ($columnExists) {
            $this->forge->dropForeignKey('tbl_user_data', 'tbl_user_data_fk_role_id_foreign');
            $this->forge->dropColumn('tbl_user_data', 'fk_role_id');
        }
        
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}

