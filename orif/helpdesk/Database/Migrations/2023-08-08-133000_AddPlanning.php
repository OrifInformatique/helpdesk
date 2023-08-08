<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddPlanning extends Migration
{
    public function up()
    {
        $fields = [
            'planning_lundi_m1', 'planning_lundi_m2', 'planning_lundi_a1', 'planning_lundi_a2',
            'planning_mardi_m1', 'planning_mardi_m2', 'planning_mardi_a1', 'planning_mardi_a2',
            'planning_mercredi_m1', 'planning_mercredi_m2', 'planning_mercredi_a1', 'planning_mercredi_a2',
            'planning_jeudi_m1', 'planning_jeudi_m2', 'planning_jeudi_a1', 'planning_jeudi_a2',
            'planning_vendredi_m1', 'planning_vendredi_m2', 'planning_vendredi_a1', 'planning_vendredi_a2'
        ];

        $this->forge->addField([
            'id_planning' => [
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

        $this->forge->addKey('id_planning', true);

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

            $this->forge->addForeignKey($field, 'tbl_roles', 'id_role');
        }

        $this->forge->createTable('tbl_planning');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_planning');
    }
}