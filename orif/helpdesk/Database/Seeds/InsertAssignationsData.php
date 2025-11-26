<?php

namespace Helpdesk\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertAssignationsData extends Seeder
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
            $this->db->table('tbl_assignation')->insert($row);
        }
    }
}