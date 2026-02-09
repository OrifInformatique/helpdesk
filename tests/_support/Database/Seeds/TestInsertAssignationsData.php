<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Test seeder for assignations
 * 
 * TEST DATABASE : ci4_test
 * This seeder is used exclusively for unit and integration tests.
 */
class TestInsertAssignationsData extends Seeder
{
    public function run()
    {
        $data = 
        [
            [
                'id_assignation' => 1,
                'title_assignation' => 'Technicien d\'astreinte',
            ],
            [
                'id_assignation' => 2,
                'title_assignation' => 'Technicien de backup',
            ],
            [
                'id_assignation' => 3,
                'title_assignation' => 'Technicien de réserve',
            ],            
        ];

        foreach($data as $row)
        {
            // Check if record already exists before inserting (idempotent)
            $exists = $this->db->table('tbl_assignation')
                ->where('id_assignation', $row['id_assignation'])
                ->countAllResults();
            
            if ($exists === 0) {
                $this->db->table('tbl_assignation')->insert($row);
            }
        }
    }
}
