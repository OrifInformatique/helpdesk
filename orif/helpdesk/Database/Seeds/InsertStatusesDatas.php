<?php

namespace User\Database\Seeds;

class InsertStatusesDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $data = 
        [
            [
                'id_status' => 1,
                'intitule_status' => 'Présent',
            ],
            [
                'id_status' => 2,
                'intitule_status' => 'Absent en partie',
            ],
            [
                'id_status' => 3,
                'intitule_status' => 'Absent',
            ],            
        ];

        foreach($data as $row)
        {
            $this->db->table('tbl_statuses')->insert($row);
        }
    }
}