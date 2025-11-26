<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddRoles extends Migration
{
    public function up()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->addField(
        [
            'id_role' =>
            [
                'type'           => 'INT',
                'constraint'     =>11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            
            'name_role' =>
            [
                'type'           => 'VARCHAR',
                'constraint'     => 50,
                'null'           => true,
            ],

            'priority_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
            ],

            'min_assignation_first_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],

            'max_assignation_first_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],

            'min_assignation_second_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],

            'max_assignation_second_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],

            'min_assignation_third_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ],

            'max_assignation_third_technician_role' =>
            [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'null'           => true,
            ]
        ]);

        $this->forge->addKey('id_role', true);

        $this->forge->createTable('tbl_roles');

        $seeder=\Config\Database::seeder();

        $seeder->call('\Helpdesk\Database\Seeds\InsertRolesData');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }

    public function down()
    {
        $this->forge->dropTable('tbl_roles');
    }
}