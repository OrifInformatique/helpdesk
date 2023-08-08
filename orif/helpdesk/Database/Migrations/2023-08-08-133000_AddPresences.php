<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPresences extends Migration
{
    public function up()
    {
        $fields = [
            'presences_lundi_m1', 'presences_lundi_m2', 'presences_lundi_a1', 'presences_lundi_a2',
            'presences_mardi_m1', 'presences_mardi_m2', 'presences_mardi_a1', 'presences_mardi_a2',
            'presences_mercredi_m1', 'presences_mercredi_m2', 'presences_mercredi_a1', 'presences_mercredi_a2',
            'presences_jeudi_m1', 'presences_jeudi_m2', 'presences_jeudi_a1', 'presences_jeudi_a2',
            'presences_vendredi_m1', 'presences_vendredi_m2', 'presences_vendredi_a1', 'presences_vendredi_a2'
        ];

        $this->forge->addField([
            'id_presence' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            'fk_user_id' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],
        ]);

        $this->forge->addKey('id_presence', true);

        $this->forge->addForeignKey('fk_user_id', 'user', 'id');

        foreach($fields as $field)
        {
            $this->forge->addField([
                $field => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
            ]]);

            $this->forge->addForeignKey($field, 'tbl_etats', 'id_etat');
        }

        $this->forge->createTable('tbl_presences');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_presences');
    }
}