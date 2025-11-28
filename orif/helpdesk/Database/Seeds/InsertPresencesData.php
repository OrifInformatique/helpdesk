<?php

namespace Helpdesk\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertPresencesData extends Seeder
{
    public function run()
    {
        // Utiliser les utilisateurs existants avec les IDs 2 à 21 (20 utilisateurs)
        
        // 1. Créer 5 utilisateurs avec les mêmes presences mais différents rôles
        // Presences: Présent toute la semaine (tous les champs = 1)
        // Utilisation des IDs 2 à 6
        $samePresences = [
            'presence_mon_m1' => 1, 'presence_mon_m2' => 1, 'presence_mon_a1' => 1, 'presence_mon_a2' => 1,
            'presence_tue_m1' => 1, 'presence_tue_m2' => 1, 'presence_tue_a1' => 1, 'presence_tue_a2' => 1,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 1, 'presence_wed_a1' => 1, 'presence_wed_a2' => 1,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 1, 'presence_thu_a1' => 1, 'presence_thu_a2' => 1,
            'presence_fri_m1' => 1, 'presence_fri_m2' => 1, 'presence_fri_a1' => 1, 'presence_fri_a2' => 1,
        ];
        
        for ($i = 2; $i <= 6; $i++) {
            $this->createPresences($i, $samePresences);
        }
        
        // 2. Créer 3 utilisateurs avec la même présence pour toutes les périodes, pour chaque présence
        // ID 7 : utilisateur avec "Présent" partout
        $this->createPresences(7, $samePresences);
        
        // ID 8 : utilisateur avec "Absent en partie" partout
        $partlyAbsentPresences = [
            'presence_mon_m1' => 2, 'presence_mon_m2' => 2, 'presence_mon_a1' => 2, 'presence_mon_a2' => 2,
            'presence_tue_m1' => 2, 'presence_tue_m2' => 2, 'presence_tue_a1' => 2, 'presence_tue_a2' => 2,
            'presence_wed_m1' => 2, 'presence_wed_m2' => 2, 'presence_wed_a1' => 2, 'presence_wed_a2' => 2,
            'presence_thu_m1' => 2, 'presence_thu_m2' => 2, 'presence_thu_a1' => 2, 'presence_thu_a2' => 2,
            'presence_fri_m1' => 2, 'presence_fri_m2' => 2, 'presence_fri_a1' => 2, 'presence_fri_a2' => 2,
        ];
        $this->createPresences(8, $partlyAbsentPresences);
        
        // ID 9 : utilisateur avec "Absent" partout
        $absentPresences = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 3, 'presence_mon_a1' => 3, 'presence_mon_a2' => 3,
            'presence_tue_m1' => 3, 'presence_tue_m2' => 3, 'presence_tue_a1' => 3, 'presence_tue_a2' => 3,
            'presence_wed_m1' => 3, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 3, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 3, 'presence_fri_m2' => 3, 'presence_fri_a1' => 3, 'presence_fri_a2' => 3,
        ];
        $this->createPresences(9, $absentPresences);
        
        // 3. Créer 12 utilisateurs avec des presences réalistes (IDs 10 à 21)
        // Configuration pour garantir les contraintes :
        // - 1 période (mon_m1) avec seulement 1 utilisateur disponible
        // - 1 période (tue_m1) avec seulement 2 utilisateurs disponibles
        // - 1 période (wed_m1) avec seulement 3 utilisateurs disponibles
        // - 1 période (thu_m1) avec tous les utilisateurs disponibles
        
        $realisticUsers = [];
        
        // User 10 (index 1) : Disponible à mon_m1, tue_m1, wed_m1, thu_m1 (pour les contraintes)
        $realisticUsers[10] = [
            'presence_mon_m1' => 1, 'presence_mon_m2' => 1, 'presence_mon_a1' => 2, 'presence_mon_a2' => 1,
            'presence_tue_m1' => 1, 'presence_tue_m2' => 1, 'presence_tue_a1' => 1, 'presence_tue_a2' => 1,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 2, 'presence_wed_a1' => 1, 'presence_wed_a2' => 1,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 1, 'presence_thu_a1' => 1, 'presence_thu_a2' => 1,
            'presence_fri_m1' => 1, 'presence_fri_m2' => 1, 'presence_fri_a1' => 1, 'presence_fri_a2' => 1,
        ];
        
        // User 11 (index 2) : Disponible à tue_m1, wed_m1, thu_m1 (mais PAS mon_m1)
        $realisticUsers[11] = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 1, 'presence_mon_a1' => 1, 'presence_mon_a2' => 1,
            'presence_tue_m1' => 1, 'presence_tue_m2' => 1, 'presence_tue_a1' => 1, 'presence_tue_a2' => 1,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 1, 'presence_wed_a1' => 2, 'presence_wed_a2' => 1,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 1, 'presence_thu_a1' => 1, 'presence_thu_a2' => 1,
            'presence_fri_m1' => 1, 'presence_fri_m2' => 1, 'presence_fri_a1' => 1, 'presence_fri_a2' => 1,
        ];
        
        // User 12 (index 3) : Disponible à wed_m1, thu_m1 (mais PAS mon_m1, tue_m1)
        $realisticUsers[12] = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 1, 'presence_mon_a1' => 1, 'presence_mon_a2' => 1,
            'presence_tue_m1' => 3, 'presence_tue_m2' => 1, 'presence_tue_a1' => 1, 'presence_tue_a2' => 1,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 1, 'presence_wed_a1' => 1, 'presence_wed_a2' => 1,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 1, 'presence_thu_a1' => 1, 'presence_thu_a2' => 1,
            'presence_fri_m1' => 1, 'presence_fri_m2' => 1, 'presence_fri_a1' => 1, 'presence_fri_a2' => 1,
        ];
        
        // Users 13-21 : Disponibles à thu_m1 (mais PAS mon_m1, tue_m1, wed_m1)
        for ($i = 13; $i <= 21; $i++) {
            $realisticUsers[$i] = [
                'presence_mon_m1' => 3, 'presence_mon_m2' => 1, 'presence_mon_a1' => 1, 'presence_mon_a2' => 1,
                'presence_tue_m1' => 3, 'presence_tue_m2' => 1, 'presence_tue_a1' => 1, 'presence_tue_a2' => 1,
                'presence_wed_m1' => 3, 'presence_wed_m2' => 1, 'presence_wed_a1' => 1, 'presence_wed_a2' => 1,
                'presence_thu_m1' => 1, 'presence_thu_m2' => 1, 'presence_thu_a1' => 1, 'presence_thu_a2' => 1,
                'presence_fri_m1' => 1, 'presence_fri_m2' => 1, 'presence_fri_a1' => 1, 'presence_fri_a2' => 1,
            ];
        }
        
        // Créer les présences pour les 12 utilisateurs réalistes (IDs 10 à 21)
        foreach ($realisticUsers as $userId => $presences) {
            $this->createPresences($userId, $presences);
        }
    }
    
    /**
     * Crée les presences pour un utilisateur existant
     * Vérifie si les présences existent déjà avant d'insérer
     */
    private function createPresences($userId, $presences)
    {
        // Vérifier si les présences existent déjà pour cet utilisateur
        $existing = $this->db->table('tbl_presences')
            ->where('fk_user_id', $userId)
            ->get()
            ->getRowArray();
        
        // Si les présences existent déjà, passer au suivant
        if ($existing) {
            return;
        }
        
        $presenceData = array_merge(['fk_user_id' => $userId], $presences);
        $this->db->table('tbl_presences')->insert($presenceData);
    }
}

