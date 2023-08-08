<?php

namespace User\Database\Seeds;

class InsertEtatsDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $data = 
        [
            [
                'id_etat' => 1,
                'intitule_etat' => 'Présent',
            ],
            [
                'id_etat' => 2,
                'intitule_etat' => 'Absent en partie',
            ],
            [
                'id_etat' => 3,
                'intitule_etat' => 'Absent',
            ],            
        ];

        foreach($data as $row)
        {
            $this->db->table('tbl_etats')->insert($row);
        }
    }
}