<?php

namespace Helpdesk\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertUserData extends Seeder
{
    public function run()
    {
        // Vérifier si la colonne fk_role_id existe
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_user_data` LIKE 'fk_role_id'");
        $columnExists = $query->getNumRows() > 0;

        $data = 
        [
            [
                'id_user_data'          => 1,
                'fk_user_id'            => 1,
                'fk_role_id'            => 1,
                'last_name_user_data'   => 'Admin',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'AdUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 2,
                'fk_user_id'            => 2,
                'fk_role_id'            => 2,
                'last_name_user_data'   => 'First',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'FiUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 3,
                'fk_user_id'            => 3,
                'fk_role_id'            => 3,
                'last_name_user_data'   => 'Second',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'SeUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 4,
                'fk_user_id'            => 4,
                'fk_role_id'            => 4,
                'last_name_user_data'   => 'Third',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'ThUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 5,
                'fk_user_id'            => 5,
                'fk_role_id'            => 5,
                'last_name_user_data'   => 'Fourth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'FoUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 6,
                'fk_user_id'            => 6,
                'fk_role_id'            => 5,
                'last_name_user_data'   => 'Fifth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'FfUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 7,
                'fk_user_id'            => 7,
                'fk_role_id'            => 2,
                'last_name_user_data'   => 'Sixth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'SiUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 8,
                'fk_user_id'            => 8,
                'fk_role_id'            => 2,
                'last_name_user_data'   => 'Seventh',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'SvUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 9,
                'fk_user_id'            => 9,
                'fk_role_id'            => 6,
                'last_name_user_data'   => 'Eighth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'EiUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 10,
                'fk_user_id'            => 10,
                'fk_role_id'            => 6,
                'last_name_user_data'   => 'Ninth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'NiUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 11,
                'fk_user_id'            => 11,
                'fk_role_id'            => 6,
                'last_name_user_data'   => 'Tenth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'TeUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 12,
                'fk_user_id'            => 12,
                'fk_role_id'            => 1,
                'last_name_user_data'   => 'Eleventh',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'ElUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 13,
                'fk_user_id'            => 13,
                'fk_role_id'            => 2,
                'last_name_user_data'   => 'Twelfth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'TwUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 14,
                'fk_user_id'            => 14,
                'fk_role_id'            => 5,
                'last_name_user_data'   => 'Thirteenth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'ThUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 15,
                'fk_user_id'            => 15,
                'fk_role_id'            => 5,
                'last_name_user_data'   => 'Fourteenth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'FuUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 16,
                'fk_user_id'            => 16,
                'fk_role_id'            => 1,
                'last_name_user_data'   => 'Fifteenth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'FtUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 17,
                'fk_user_id'            => 17,
                'fk_role_id'            => 5,
                'last_name_user_data'   => 'Sixteenth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'SxUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 18,
                'fk_user_id'            => 18,
                'fk_role_id'            => 3,
                'last_name_user_data'   => 'Seventeenth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'SnUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 19,
                'fk_user_id'            => 19,
                'fk_role_id'            => 2,
                'last_name_user_data'   => 'Eighteenth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'EgUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 20,
                'fk_user_id'            => 20,
                'fk_role_id'            => 4,
                'last_name_user_data'   => 'Nineteenth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'NnUs',
                'photo_user_data'       => NULL,
            ],
            [
                'id_user_data'          => 21,
                'fk_user_id'            => 21,
                'fk_role_id'            => 6,
                'last_name_user_data'   => 'Twentieth',
                'first_name_user_data'  => 'User',
                'initials_user_data'    => 'TtUs',
                'photo_user_data'       => NULL,
            ]
        ];

        foreach($data as $row)
        {
            // Vérifier si l'enregistrement existe déjà (par id_user_data)
            $existing = $this->db->table('tbl_user_data')
                ->where('id_user_data', $row['id_user_data'])
                ->get()
                ->getRowArray();
            
            // Si l'enregistrement existe déjà, passer au suivant
            if ($existing) {
                continue;
            }
            
            // Ajouter fk_role_id seulement si la colonne existe
            if ($columnExists && !isset($row['fk_role_id'])) {
                $row['fk_role_id'] = 1; // Valeur par défaut
            } elseif (!$columnExists && isset($row['fk_role_id'])) {
                // Retirer fk_role_id si la colonne n'existe pas
                unset($row['fk_role_id']);
            }
            
            $this->db->table('tbl_user_data')->insert($row);
        }
    }
}