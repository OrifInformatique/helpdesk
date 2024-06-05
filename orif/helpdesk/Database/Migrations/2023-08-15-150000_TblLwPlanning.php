<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddLwPlanning extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $fields =
        [
            'lw_planning_mon_m1', 'lw_planning_mon_m2', 'lw_planning_mon_a1', 'lw_planning_mon_a2',
            'lw_planning_tue_m1', 'lw_planning_tue_m2', 'lw_planning_tue_a1', 'lw_planning_tue_a2',
            'lw_planning_wed_m1', 'lw_planning_wed_m2', 'lw_planning_wed_a1', 'lw_planning_wed_a2',
            'lw_planning_thu_m1', 'lw_planning_thu_m2', 'lw_planning_thu_a1', 'lw_planning_thu_a2',
            'lw_planning_fri_m1', 'lw_planning_fri_m2', 'lw_planning_fri_a1', 'lw_planning_fri_a2'
        ];

        $this->forge->addField(
        [
            'id_lw_planning' =>
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
            ],
        ]);

        $this->forge->addKey('id_lw_planning', true);

        $this->forge->addForeignKey('fk_user_id', 'user', 'id');

        foreach($fields as $field)
        {
            $this->forge->addField(
            [
                $field =>
                [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'unsigned'   => true,
                    'null'       => true,
                ]
            ]);

            $this->forge->addForeignKey($field, 'tbl_roles', 'id_role');
        }

        $this->forge->createTable('tbl_lw_planning');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_lw_planning');
    }
}