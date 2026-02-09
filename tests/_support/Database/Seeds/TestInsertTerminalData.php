<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Test seeder for terminals
 * 
 * TEST DATABASE : ci4_test
 * This seeder is used exclusively for unit and integration tests.
 */
class TestInsertTerminalData extends Seeder
{
    public function run()
    {
        $data = 
        [
            [
                'id_terminal' => 1,
                'fk_role_terminal' => 1,
                'tech_available_terminal' => true
            ],      
            [
                'id_terminal' => 2,
                'fk_role_terminal' => 2,
                'tech_available_terminal' => true
            ],      
            [
                'id_terminal' => 3,
                'fk_role_terminal' => 3,
                'tech_available_terminal' => true
            ],      
        ];

        foreach($data as $row)
        {
            // Check if record already exists before inserting (idempotent)
            $exists = $this->db->table('tbl_terminal')
                ->where('id_terminal', $row['id_terminal'])
                ->countAllResults();
            
            if ($exists === 0) {
                $this->db->table('tbl_terminal')->insert($row);
            }
        }
    }
}
