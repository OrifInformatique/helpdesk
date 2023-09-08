<?php

namespace User\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertRolesDatas extends Seeder
{
    public function run()
    {
        $data = 
        [
            [
                'id_role' => 1,
                'title_role' => 'Technicien d\'astreinte',
            ],
            [
                'id_role' => 2,
                'title_role' => 'Technicien de backup',
            ],
            [
                'id_role' => 3,
                'title_role' => 'Technicien de réserve',
            ],            
        ];

        foreach($data as $row)
        {
            $this->db->table('tbl_roles')->insert($row);
        }
    }
}