<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddTerminal extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->addField([
            'id_terminal' => 
            [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'null'          => false
            ],
            
            'fk_role_terminal' => 
            [
                'type'          => 'INT',
                'constraint'    => 11,
                'unsigned'      => true,
                'null'          => false,
            ],

            'tech_available_terminal' => 
            [
                'type'          => 'VARCHAR',
                'constraint'    => 16,
                'default'       => 'true',
            ],
        ]);

        $this->forge->addKey('id_terminal', true);

        $this->forge->addForeignKey('fk_role_terminal', 'tbl_roles', 'id_role');

        $this->forge->createTable('tbl_terminal');

        $seeder=\Config\Database::seeder();

        $seeder->call('\Helpdesk\Database\Seeds\InsertTerminalData');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_terminal');
    }
}