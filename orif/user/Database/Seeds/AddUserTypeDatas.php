<?php


namespace User\Database\Seeds;


class AddUserTypeDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $data=[
            ['name'=>'Administrateur','access_level'=>4],
            ['name'=>'Enregistré','access_level'=>2],
            ['name'=>'Invité','access_level'=>1],
            ['name'=>'Technicien parrain','access_level'=>3]
        ];
        foreach($data as $row)
        {
            // Check if record already exists before inserting (idempotent)
            $exists = $this->db->table('user_type')
                ->where('name', $row['name'])
                ->countAllResults();
            
            if ($exists === 0) {
                $this->db->table('user_type')->insert($row);
            }
        }

    }
}