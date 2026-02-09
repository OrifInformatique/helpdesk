<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class AddUpdatedAtToPresences extends Migration
{
    public function up()
    {
        // Check if the column already exists with a direct SQL query
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_presences` LIKE 'updated_at'");
        $columnExists = $query->getNumRows() > 0;

        if (!$columnExists) {
            $this->db->query('SET FOREIGN_KEY_CHECKS=0');

            $fields = [
                'updated_at' => [
                    'type'       => 'DATETIME',
                    'null'       => true,
                    'default'    => null,
                ],
            ];

            $this->forge->addColumn('tbl_presences', $fields);

            // Update existing records with current date
            $this->db->query("UPDATE tbl_presences SET updated_at = NOW() WHERE updated_at IS NULL");

            $this->db->query('SET FOREIGN_KEY_CHECKS=1');
        }
    }

    public function down()
    {
        $this->db->query('SET FOREIGN_KEY_CHECKS=0');

        $this->forge->dropColumn('tbl_presences', 'updated_at');

        $this->db->query('SET FOREIGN_KEY_CHECKS=1');
    }
}
