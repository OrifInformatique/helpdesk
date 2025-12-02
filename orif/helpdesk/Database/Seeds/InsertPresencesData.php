<?php

namespace Helpdesk\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertPresencesData extends Seeder
{
    public function run()
    {
        // Utiliser les utilisateurs existants avec les IDs 2 à 21 (20 utilisateurs)
        
        // 1. Créer 5 utilisateurs avec les mêmes presences mais différents rôles
        // Presences: Présence réaliste
        // Utilisation des IDs 2 à 6
        $same_presences = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 2, 'presence_mon_a1' => 1, 'presence_mon_a2' => 1,
            'presence_tue_m1' => 3, 'presence_tue_m2' => 1, 'presence_tue_a1' => 1, 'presence_tue_a2' => 1,
            'presence_wed_m1' => 3, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 3, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 1, 'presence_fri_m2' => 1, 'presence_fri_a1' => 1, 'presence_fri_a2' => 1,
        ];
        
        for ($i = 2; $i <= 6; $i++) {
            $this->createPresences($i, $same_presences);
        }
        
        // 2. Créer 3 utilisateurs avec la même présence pour toutes les périodes, pour chaque présence
        // ID 7 : utilisateur avec "Présent" partout
        $present_presences = [
            'presence_mon_m1' => 1, 'presence_mon_m2' => 1, 'presence_mon_a1' => 1, 'presence_mon_a2' => 1,
            'presence_tue_m1' => 1, 'presence_tue_m2' => 1, 'presence_tue_a1' => 1, 'presence_tue_a2' => 1,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 1, 'presence_wed_a1' => 1, 'presence_wed_a2' => 1,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 1, 'presence_thu_a1' => 1, 'presence_thu_a2' => 1,
            'presence_fri_m1' => 1, 'presence_fri_m2' => 1, 'presence_fri_a1' => 1, 'presence_fri_a2' => 1,
        ];

        $this->createPresences(7, $present_presences);

        // ID 8 : utilisateur avec "Absent en partie" partout
        $partly_absent_presences = [
            'presence_mon_m1' => 2, 'presence_mon_m2' => 2, 'presence_mon_a1' => 2, 'presence_mon_a2' => 2,
            'presence_tue_m1' => 2, 'presence_tue_m2' => 2, 'presence_tue_a1' => 2, 'presence_tue_a2' => 2,
            'presence_wed_m1' => 2, 'presence_wed_m2' => 2, 'presence_wed_a1' => 2, 'presence_wed_a2' => 2,
            'presence_thu_m1' => 2, 'presence_thu_m2' => 2, 'presence_thu_a1' => 2, 'presence_thu_a2' => 2,
            'presence_fri_m1' => 2, 'presence_fri_m2' => 2, 'presence_fri_a1' => 2, 'presence_fri_a2' => 2,
        ];

        $this->createPresences(8, $partly_absent_presences);

        // ID 9 : utilisateur avec "Absent" partout
        $all_absent_presences = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 3, 'presence_mon_a1' => 3, 'presence_mon_a2' => 3,
            'presence_tue_m1' => 3, 'presence_tue_m2' => 3, 'presence_tue_a1' => 3, 'presence_tue_a2' => 3,
            'presence_wed_m1' => 3, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 3, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 3, 'presence_fri_m2' => 3, 'presence_fri_a1' => 3, 'presence_fri_a2' => 3,
        ];

        $this->createPresences(9, $all_absent_presences);
        
        // 3. Créer 12 utilisateurs avec des presences réalistes (IDs 10 à 21)
        // Configuration pour garantir les contraintes :
        // - 1 période (mon_m1) avec seulement 1 utilisateur disponible
        // - 1 période (tue_m1) avec seulement 2 utilisateurs disponibles
        // - 1 période (wed_m1) avec seulement 3 utilisateurs disponibles
        // - 1 période (thu_m1) avec tous les utilisateurs disponibles
        
        $realistic_users = [];
        
        // User 10 (index 1) : Disponible à mon_m1, tue_m1, wed_m1, thu_m1 (pour les contraintes)
        $realistic_users[10] = [
            'presence_mon_m1' => 1, 'presence_mon_m2' => 3, 'presence_mon_a1' => 3, 'presence_mon_a2' => 3,
            'presence_tue_m1' => 1, 'presence_tue_m2' => 3, 'presence_tue_a1' => 3, 'presence_tue_a2' => 3,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 3, 'presence_fri_m2' => 3, 'presence_fri_a1' => 3, 'presence_fri_a2' => 3,
        ];
        
        // User 11 (index 2) : Disponible à tue_m1, wed_m1, thu_m1 (mais PAS mon_m1)
        $realistic_users[11] = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 3, 'presence_mon_a1' => 3, 'presence_mon_a2' => 3,
            'presence_tue_m1' => 1, 'presence_tue_m2' => 3, 'presence_tue_a1' => 3, 'presence_tue_a2' => 3,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 3, 'presence_fri_m2' => 3, 'presence_fri_a1' => 3, 'presence_fri_a2' => 3,
        ];
        
        // User 12 (index 3) : Disponible à wed_m1, thu_m1 (mais PAS mon_m1, tue_m1)
        $realistic_users[12] = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 3, 'presence_mon_a1' => 3, 'presence_mon_a2' => 3,
            'presence_tue_m1' => 3, 'presence_tue_m2' => 3, 'presence_tue_a1' => 3, 'presence_tue_a2' => 3,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 3, 'presence_fri_m2' => 3, 'presence_fri_a1' => 3, 'presence_fri_a2' => 3,
        ];
        
        // Users 13-21 : planning réaliste avec attribution randomisée
        for ($i = 13; $i <= 21; $i++) {
            // Générer une valeur aléatoire entre 1, 2 ou 3 pour chaque période
            $realistic_users[$i] = [
                'presence_mon_m1' => 3, 'presence_mon_m2' => rand(1, 3), 'presence_mon_a1' => rand(1, 3), 'presence_mon_a2' => rand(1, 3),
                'presence_tue_m1' => 3, 'presence_tue_m2' => rand(1, 3), 'presence_tue_a1' => rand(1, 3), 'presence_tue_a2' => rand(1, 3),
                'presence_wed_m1' => 3, 'presence_wed_m2' => rand(1, 3), 'presence_wed_a1' => rand(1, 3), 'presence_wed_a2' => rand(1, 3),
                'presence_thu_m1' => 1, 'presence_thu_m2' => rand(1, 3), 'presence_thu_a1' => rand(1, 3), 'presence_thu_a2' => rand(1, 3),
                'presence_fri_m1' => rand(1, 3), 'presence_fri_m2' => rand(1, 3), 'presence_fri_a1' => rand(1, 3), 'presence_fri_a2' => rand(1, 3), 
            ];
        }
        
        // Créer les présences pour les 12 utilisateurs réalistes (IDs 10 à 21)
        foreach ($realistic_users as $user_id => $presences) {
            $this->createPresences($user_id, $presences);
        }
    }
    
    /**
     * Crée les presences pour un utilisateur existant
     * Vérifie si les présences existent déjà avant d'insérer
     */
    private function createPresences($user_id, $presences)
    {
        // Vérifier si les présences existent déjà pour cet utilisateur
        $existing = $this->db->table('tbl_presences')
            ->where('fk_user_id', $user_id)
            ->get()
            ->getRowArray();
        
        // Si les présences existent déjà, passer au suivant
        if ($existing) {
            return;
        }
        
        $presence_data = array_merge(['fk_user_id' => $user_id], $presences);
        $this->db->table('tbl_presences')->insert($presence_data);
    }
}

