<?php

namespace Helpdesk\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertUserData extends Seeder
{
    public function run()
    {
        $data = 
        [
            [
                'id_user_data'          => 1,
                'fk_user_id'            => 1,
                'last_name_user_data'   => 'Admin',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'AdUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 2,
                'fk_user_id'            => 2,
                'last_name_user_data'   => 'Regular',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'ReUs',
                'photo_user_data'       => NULL,
            ]
        ];

        foreach($data as $row)
        {
            $this->db->table('tbl_user_data')->insert($row);
        }
    }
}