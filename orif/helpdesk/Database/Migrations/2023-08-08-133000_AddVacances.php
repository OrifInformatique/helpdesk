<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddHolidays extends Migration
{
    public function up()
    {
        $this->forge->addField([
            'id_holiday' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],

            'name_holiday' => [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true,
            ],

            'start_date_holiday' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],

            'end_date_holiday' => [
                'type'           => 'DATETIME',
                'null'           => true,
            ],
        ]);

        $this->forge->addKey('id_holiday', true);

        $this->forge->createTable('tbl_holidays');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_holidays');
    }
}