<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Test seeder for statuses
 * 
 * TEST DATABASE : ci4_test
 * This seeder is used exclusively for unit and integration tests.
 */
class TestInsertStatusesData extends Seeder
{
    public function run()
    {
        $data = 
        [
            [
                'id_status' => 1,
                'title_status' => 'Présent',
            ],
            [
                'id_status' => 2,
                'title_status' => 'Absent en partie',
            ],
            [
                'id_status' => 3,
                'title_status' => 'Absent',
            ],            
        ];

        foreach($data as $row)
        {
            // Check if record already exists before inserting (idempotent)
            $exists = $this->db->table('tbl_statuses')
                ->where('id_status', $row['id_status'])
                ->countAllResults();
            
            if ($exists === 0) {
                $this->db->table('tbl_statuses')->insert($row);
            }
        }
    }
}
