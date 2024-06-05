<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUserData extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->addField(
        [
            'id_user_data' =>
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
                'null'           => true,
            ],

            'last_name_user_data' =>
            [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true,
            ],

            'first_name_user_data' =>
            [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true,
            ],

            'initials_user_data' =>
            [
                'type'           => 'VARCHAR',
                'constraint'     => 16,
                'null'           => true,
            ],
            
            'photo_user_data' =>
            [
                'type'           => 'VARCHAR',
                'constraint'     => 1000,
                'null'           => true,
            ],
        ]);

        $this->forge->addKey('id_user_data', true);

        $this->forge->addForeignKey('fk_user_id', 'user', 'id');

        $this->forge->createTable('tbl_user_data');

        $seeder=\Config\Database::seeder();

        $seeder->call('\Helpdesk\Database\Seeds\InsertUserData');
        
        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_user_data');
    }
}