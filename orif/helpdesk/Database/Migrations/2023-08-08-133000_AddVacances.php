<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddVacances extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_vacances' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'titre_vacances' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true,
            ],

            'date_debut_vacances' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],

            'date_fin_vacances' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addKey('id_vacances', true);

        $this->forge->createTable('tbl_vacances');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_vacances');
    }
}