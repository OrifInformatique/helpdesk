<?php

namespace Helpdesk\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertUserData extends Seeder
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
            ]
            // [
            //     'id_user_data'          => 2,
            //     'fk_user_id'            => 2,
            //     'fk_role_id'            => 2,
            //     'last_name_user_data'   => 'A_Operator',
            //     'first_name_user_data'  => 'User',
            //     'initials_user_data'    => 'OpUs',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 3,
            //     'fk_user_id'            => 3,
            //     'fk_role_id'            => 3,
            //     'last_name_user_data'   => 'A_Infrastructure',
            //     'first_name_user_data'  => 'User',
            //     'initials_user_data'    => 'InUs',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 4,
            //     'fk_user_id'            => 4,
            //     'fk_role_id'            => 4,
            //     'last_name_user_data'   => 'A_Developer',
            //     'first_name_user_data'  => 'User',
            //     'initials_user_data'    => 'DeUs',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 5,
            //     'fk_user_id'            => 5,
            //     'fk_role_id'            => 5,
            //     'last_name_user_data'   => 'A_Observation',
            //     'first_name_user_data'  => 'User',
            //     'initials_user_data'    => 'ObUs',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 6,
            //     'fk_user_id'            => 6,
            //     'fk_role_id'            => 6,
            //     'last_name_user_data'   => 'A_Pre-apprentissage',
            //     'first_name_user_data'  => 'User',
            //     'initials_user_data'    => 'PaUs',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 7,
            //     'fk_user_id'            => 7,
            //     'fk_role_id'            => 2,
            //     'last_name_user_data'   => 'B_Present',
            //     'first_name_user_data'  => 'User',
            //     'initials_user_data'    => 'PrUs',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 8,
            //     'fk_user_id'            => 8,
            //     'fk_role_id'            => 3,
            //     'last_name_user_data'   => 'B_Partially_Absent',
            //     'first_name_user_data'  => 'User',
            //     'initials_user_data'    => 'PtUs',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 9,
            //     'fk_user_id'            => 9,
            //     'fk_role_id'            => 4,
            //     'last_name_user_data'   => 'B_Absent',
            //     'first_name_user_data'  => 'User',
            //     'initials_user_data'    => 'AbUs',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 10,
            //     'fk_user_id'            => 10,
            //     'fk_role_id'            => 5,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Alpha',
            //     'initials_user_data'    => 'ReAl',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 11,
            //     'fk_user_id'            => 11,
            //     'fk_role_id'            => 6,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Bravo',
            //     'initials_user_data'    => 'ReBr',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 12,
            //     'fk_user_id'            => 12,
            //     'fk_role_id'            => 2,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Charlie',
            //     'initials_user_data'    => 'ReCh',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 13,
            //     'fk_user_id'            => 13,
            //     'fk_role_id'            => 3,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Delta',
            //     'initials_user_data'    => 'ReDe',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 14,
            //     'fk_user_id'            => 14,
            //     'fk_role_id'            => 4,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Echo',
            //     'initials_user_data'    => 'ReEc',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 15,
            //     'fk_user_id'            => 15,
            //     'fk_role_id'            => 5,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Foxtrot',
            //     'initials_user_data'    => 'ReFo',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 16,
            //     'fk_user_id'            => 16,
            //     'fk_role_id'            => 6,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Golf',
            //     'initials_user_data'    => 'ReGo',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 17,
            //     'fk_user_id'            => 17,
            //     'fk_role_id'            => 6,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Hotel',
            //     'initials_user_data'    => 'ReHo',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 18,
            //     'fk_user_id'            => 18,
            //     'fk_role_id'            => 5,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'India',
            //     'initials_user_data'    => 'ReIn',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 19,
            //     'fk_user_id'            => 19,
            //     'fk_role_id'            => 4,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Juliet',
            //     'initials_user_data'    => 'ReJu',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 20,
            //     'fk_user_id'            => 20,
            //     'fk_role_id'            => 3,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Kilo',
            //     'initials_user_data'    => 'ReKi',
            //     'photo_user_data'       => NULL,
            // ],
            // [
            //     'id_user_data'          => 21,
            //     'fk_user_id'            => 21,
            //     'fk_role_id'            => 2,
            //     'last_name_user_data'   => 'Realistic',
            //     'first_name_user_data'  => 'Lima',
            //     'initials_user_data'    => 'ReLi',
            //     'photo_user_data'       => NULL,
            // ]
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
                // Remove fk_role_id if column doesn't exist
                unset($row['fk_role_id']);
            }
            
            $this->db->table('tbl_user_data')->insert($row);
        }
    }
}