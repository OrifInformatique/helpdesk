<?php


namespace User\Database\Seeds;


class AddUserDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        // password
        $defaultPassword = '$2y$10$6udFYIjZkuCZshN.h2RlKuF.UeELiKFm7SmFM9Omgoh0HPdmsS2Xy';

        $data = [
            ['fk_user_type' => 1, 'username' => 'admin', 'password' =>  '$2y$10$84r63xo.M4LVcIi8IvT8cO0qYxyglPshY1jJmKLedRMcaTcxhcVYO'],
            ['fk_user_type' => 2, 'username' => 'A_Operator_User', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'A_Infrastructure_User', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'A_Developer_User', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'A_Observation_User', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'A_Pre-apprentissage_User', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'B_Present_User', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'B_Partially_Absent_User', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'B_Absent_User', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Alpha', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Bravo', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Charlie', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Delta', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Echo', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Foxtrot', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Golf', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Hotel', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_India', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Juliet', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Kilo', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'Realistic_Lima', 'password' => $defaultPassword],
        ];

        foreach ($data as $row) {
            $this->db->table('user')->insert($row);
        }
    }
}