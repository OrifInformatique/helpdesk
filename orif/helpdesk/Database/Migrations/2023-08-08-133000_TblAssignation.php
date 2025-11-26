<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddAssignation extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->addField(
        [
            'id_assignation' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            'title_assignation' =>
            [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true,
            ],
        ]);

        $this->forge->addKey('id_assignation', true);

        $this->forge->createTable('tbl_assignation');

        $seeder=\Config\Database::seeder();

        $seeder->call('\Helpdesk\Database\Seeds\InsertAssignationsData');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_assignation');
    }
}