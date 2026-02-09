<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Test seeder for system roles
 * 
 * TEST DATABASE : ci4_test
 * This seeder is used exclusively for unit and integration tests.
 * 
 * Napw = Number of assignations per week
 * 
 * Default roles :
 * - No Role: Napw Tech 1=0, Tech 2=0, Tech 3=0, Priority=0
 * - Operator: Napw Tech 1=20, Tech 2=20, Tech 3=20, Priority=1
 * - Infrastructure: Napw Tech 1=2, Tech 2=2, Tech 3=2, Priority=2
 * - Developer: Napw Tech 1=2, Tech 2=2, Tech 3=2, Priority=2
 * 
 * Custom roles :
 * - Observation: Napw Tech 1=0, Tech 2=2, Tech 3=2, Priority=2
 * - Pré-apprentissage: Napw Tech 1=2, Tech 2=4, Tech 3=4, Priority=1
 */
class TestInsertRolesData extends Seeder
{
    public function run()
    {
        $data = 
        [
            // Default roles
            [
                'id_role' => 1,
                'name_role' => 'No Role',
                'priority_role' => 0,
                'min_assignation_first_technician_role' => 0,
                'max_assignation_first_technician_role' => 0,
                'min_assignation_second_technician_role' => 0,
                'max_assignation_second_technician_role' => 0,
                'min_assignation_third_technician_role' => 0,
                'max_assignation_third_technician_role' => 0,
            ],
            [
                'id_role' => 2,
                'name_role' => 'Operator',
                'priority_role' => 1,
                'min_assignation_first_technician_role' => 0,
                'max_assignation_first_technician_role' => 20,
                'min_assignation_second_technician_role' => 0,
                'max_assignation_second_technician_role' => 20,
                'min_assignation_third_technician_role' => 0,
                'max_assignation_third_technician_role' => 20,
            ],
            [
                'id_role' => 3,
                'name_role' => 'Infrastructure',
                'priority_role' => 2,
                'min_assignation_first_technician_role' => 0,
                'max_assignation_first_technician_role' => 2,
                'min_assignation_second_technician_role' => 0,
                'max_assignation_second_technician_role' => 2,
                'min_assignation_third_technician_role' => 0,
                'max_assignation_third_technician_role' => 2,
            ],
            [
                'id_role' => 4,
                'name_role' => 'Developer',
                'priority_role' => 2,
                'min_assignation_first_technician_role' => 0,
                'max_assignation_first_technician_role' => 2,
                'min_assignation_second_technician_role' => 0,
                'max_assignation_second_technician_role' => 2,
                'min_assignation_third_technician_role' => 0,
                'max_assignation_third_technician_role' => 2,
            ],
            // Custom roles
            [
                'id_role' => 5,
                'name_role' => 'Observation',
                'priority_role' => 2,
                'min_assignation_first_technician_role' => 0,
                'max_assignation_first_technician_role' => 0,
                'min_assignation_second_technician_role' => 0,
                'max_assignation_second_technician_role' => 2,
                'min_assignation_third_technician_role' => 0,
                'max_assignation_third_technician_role' => 2,
            ],
            [
                'id_role' => 6,
                'name_role' => 'Pré-apprentissage',
                'priority_role' => 1,
                'min_assignation_first_technician_role' => 0,
                'max_assignation_first_technician_role' => 2,
                'min_assignation_second_technician_role' => 0,
                'max_assignation_second_technician_role' => 4,
                'min_assignation_third_technician_role' => 0,
                'max_assignation_third_technician_role' => 4,
            ],
        ];

        foreach($data as $row)
        {
            // Check if record already exists before inserting (idempotent)
            $exists = $this->db->table('tbl_roles')
                ->where('id_role', $row['id_role'])
                ->countAllResults();
            
            if ($exists === 0) {
                $this->db->table('tbl_roles')->insert($row);
            }
        }
    }
}
