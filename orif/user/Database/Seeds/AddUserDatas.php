<?php


namespace User\Database\Seeds;


class AddUserDatas extends \CodeIgniter\Database\Seeder
{
    public function run()
    {
        $defaultPassword = '$2y$10$11wIuR3FnfWwTpfyJ9WCz.E3KErvb.i.Q2Wef6XMUZHTXUlW0FhJm';

        $data = [
            ['fk_user_type' => 1, 'username' => 'admin', 'password' =>  '$2y$10$84r63xo.M4LVcIi8IvT8cO0qYxyglPshY1jJmKLedRMcaTcxhcVYO'],
            ['fk_user_type' => 2, 'username' => 'first_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'second_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'third_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'fourth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'fifth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'sixth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'seventh_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'eighth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'ninth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'tenth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'eleventh_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'twelfth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'thirteenth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'fourteenth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'fifteenth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'sixteenth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'seventeenth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'eighteenth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'nineteenth_user', 'password' => $defaultPassword],
            ['fk_user_type' => 2, 'username' => 'twentieth_user', 'password' => $defaultPassword],
        ];

        foreach ($data as $row) {
            $this->db->table('user')->insert($row);
        }
    }
}