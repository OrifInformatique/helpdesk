<?php

namespace Helpdesk\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertTerminalData extends Seeder
{
    public function run()
    {
        $data = 
        [
            [
                'id_terminal' => 1,
                'fk_role_terminal' => 1,
                'tech_available_terminal' => true
            ],      
            [
                'id_terminal' => 2,
                'fk_role_terminal' => 2,
                'tech_available_terminal' => true
            ],      
            [
                'id_terminal' => 3,
                'fk_role_terminal' => 3,
                'tech_available_terminal' => true
            ],      
        ];

        foreach($data as $row)
        {
            $this->db->table('tbl_terminal')->insert($row);
        }
    }
}