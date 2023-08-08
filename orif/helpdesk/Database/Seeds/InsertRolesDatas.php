<?php

namespace User\Database\Seeds;

class InsertRolesDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $data = 
        [
            [
                'id_role' => 1,
                'intitule_role' => 'Technicien d\'astreinte',
            ],
            [
                'id_role' => 2,
                'intitule_role' => 'Technicien de backup',
            ],
            [
                'id_role' => 3,
                'intitule_role' => 'Technicien de réserve',
            ],            
        ];

        foreach($data as $row)
        {
            $this->db->table('tbl_roles')->insert($row);
        }
    }
}