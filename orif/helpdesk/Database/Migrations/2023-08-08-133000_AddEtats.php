<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddEtats extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_etat' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'intitule_etat' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'       => true,
            ],
        ]);

        $this->forge->addKey('id_etat', true);

        $this->forge->createTable('tbl_etats');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_etats');
    }
}