<?php


namespace User\Database\Seeds;


class AddUserDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        // password
        $defaultPassword = '$2y$10$6udFYIjZkuCZshN.h2RlKuF.UeELiKFm7SmFM9Omgoh0HPdmsS2Xy';

        $data = [
            ['fk_user_type' => 1, 'username' => 'admin', 'password' =>  '$2y$10$84r63xo.M4LVcIi8IvT8cO0qYxyglPshY1jJmKLedRMcaTcxhcVYO', 'email' => 'admin@test.ch']
            // ['fk_user_type' => 2, 'username' => 'A_Operator_User', 'password' => $defaultPassword, 'email' => 'Operator@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'A_Infrastructure_User', 'password' => $defaultPassword, 'email' => 'Infrastructure@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'A_Developer_User', 'password' => $defaultPassword, 'email' => 'Developer@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'A_Observation_User', 'password' => $defaultPassword, 'email' => 'Observation@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'A_Pre-apprentissage_User', 'password' => $defaultPassword, 'email' => 'PreApprentissage@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'B_Present_User', 'password' => $defaultPassword, 'email' => 'Present@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'B_Partially_Absent_User', 'password' => $defaultPassword, 'email' => 'PartiallyAbsent@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'B_Absent_User', 'password' => $defaultPassword, 'email' => 'Absent@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Alpha', 'password' => $defaultPassword, 'email' => 'Alpha@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Bravo', 'password' => $defaultPassword, 'email' => 'Bravo@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Charlie', 'password' => $defaultPassword, 'email' => 'Charlie@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Delta', 'password' => $defaultPassword, 'email' => 'Delta@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Echo', 'password' => $defaultPassword, 'email' => 'Echo@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Foxtrot', 'password' => $defaultPassword, 'email' => 'Foxtrot@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Golf', 'password' => $defaultPassword, 'email' => 'Golf@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Hotel', 'password' => $defaultPassword, 'email' => 'Hotel@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_India', 'password' => $defaultPassword, 'email' => 'India@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Juliet', 'password' => $defaultPassword, 'email' => 'Juliet@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Kilo', 'password' => $defaultPassword, 'email' => 'Kilo@test.ch'],
            // ['fk_user_type' => 2, 'username' => 'Realistic_Lima', 'password' => $defaultPassword, 'email' => 'Lima@test.ch'],
        ];

        foreach ($data as $row) {
            // Check if user already exists before inserting
            $exists = $this->db->table('user')
                ->where('username', $row['username'])
                ->countAllResults();
            
            if ($exists == 0) {
                $this->db->table('user')->insert($row);
            }
        }
    }
}