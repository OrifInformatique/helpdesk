<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Test seeder for user data
 * 
 * TEST DATABASE : ci4_test
 * This seeder is used exclusively for unit and integration tests.
 */
class TestInsertUserData extends Seeder
{
    public function run()
    {
        // Check if fk_role_id column exists
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_user_data` LIKE 'fk_role_id'");
        $column_exists = $query->getNumRows() > 0;

        $data = 
        [
            [
                'id_user_data'          => 1,
                'fk_user_id'            => 1,
                'fk_role_id'            => 1,
                'last_name_user_data'   => 'Admin',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'AdUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 2,
                'fk_user_id'            => 2,
                'fk_role_id'            => 5,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'Alpha',
                'initials_user_data'    => 'ReAl',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 3,
                'fk_user_id'            => 3,
                'fk_role_id'            => 6,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'Bravo',
                'initials_user_data'    => 'ReBr',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 4,
                'fk_user_id'            => 4,
                'fk_role_id'            => 2,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'Charlie',
                'initials_user_data'    => 'ReCh',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 5,
                'fk_user_id'            => 5,
                'fk_role_id'            => 3,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'Delta',
                'initials_user_data'    => 'ReDe',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 6,
                'fk_user_id'            => 6,
                'fk_role_id'            => 4,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'Echo',
                'initials_user_data'    => 'ReEc',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 7,
                'fk_user_id'            => 7,
                'fk_role_id'            => 5,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'Foxtrot',
                'initials_user_data'    => 'ReFo',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 8,
                'fk_user_id'            => 8,
                'fk_role_id'            => 6,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'Golf',
                'initials_user_data'    => 'ReGo',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 9,
                'fk_user_id'            => 9,
                'fk_role_id'            => 6,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'Hotel',
                'initials_user_data'    => 'ReHo',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 10,
                'fk_user_id'            => 10,
                'fk_role_id'            => 5,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'India',
                'initials_user_data'    => 'ReIn',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 11,
                'fk_user_id'            => 11,
                'fk_role_id'            => 4,
                'last_name_user_data'   => 'Realistic',
                'first_name_user_data'  => 'Juliet',
                'initials_user_data'    => 'ReJu',
                'photo_user_data'       => NULL,
            ]
        ];

        foreach($data as $row)
        {
            // Check if record already exists (by id_user_data)
            $existing = $this->db->table('tbl_user_data')
                ->where('id_user_data', $row['id_user_data'])
                ->get()
                ->getRowArray();
            
            // If record already exists, skip to next
            if ($existing) {
                continue;
            }
            
            // Add fk_role_id only if column exists
            if ($column_exists && !isset($row['fk_role_id'])) {
                $row['fk_role_id'] = 1; // Default value
            } elseif (!$column_exists && isset($row['fk_role_id'])) {
                // Remove fk_role_id if column does not exist
                unset($row['fk_role_id']);
            }
            
            $this->db->table('tbl_user_data')->insert($row);
        }
    }
}
