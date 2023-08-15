<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoles extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_role' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            'title_role' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id_role', true);

        $this->forge->createTable('tbl_roles');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_roles');
    }
}