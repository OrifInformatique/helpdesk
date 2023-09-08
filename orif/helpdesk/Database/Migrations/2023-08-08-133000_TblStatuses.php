<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddStatuses extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->addField([
            'id_status' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'intitule_status' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id_status', true);

        $this->forge->createTable('tbl_statuses');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_statuses');
    }
}