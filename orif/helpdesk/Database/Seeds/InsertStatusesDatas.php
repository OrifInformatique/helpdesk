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
            $this->db->table('tbl_statuses')->insert($row);
        }
    }
}