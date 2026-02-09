<?php

namespace Helpdesk\Database\Seeds;

use CodeIgniter\Database\Seeder;

class InsertPresencesData extends Seeder
{
    public function run()
    {
        // Use existing users with IDs 2 to 21 (20 users)
        
        // 1. Create 5 users with the same presences but different roles
        // Presences: Realistic presence
        // Using IDs 2 to 6
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
        
        // 2. Create 3 users with the same presence for all periods, for each presence
        // ID 7 : user with "Present" everywhere
        $present_presences = [
            'presence_mon_m1' => 1, 'presence_mon_m2' => 1, 'presence_mon_a1' => 1, 'presence_mon_a2' => 1,
            'presence_tue_m1' => 1, 'presence_tue_m2' => 1, 'presence_tue_a1' => 1, 'presence_tue_a2' => 1,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 1, 'presence_wed_a1' => 1, 'presence_wed_a2' => 1,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 1, 'presence_thu_a1' => 1, 'presence_thu_a2' => 1,
            'presence_fri_m1' => 1, 'presence_fri_m2' => 1, 'presence_fri_a1' => 1, 'presence_fri_a2' => 1,
        ];

        $this->createPresences(7, $present_presences);

        // ID 8 : user with "Partially absent" everywhere
        $partly_absent_presences = [
            'presence_mon_m1' => 2, 'presence_mon_m2' => 2, 'presence_mon_a1' => 2, 'presence_mon_a2' => 2,
            'presence_tue_m1' => 2, 'presence_tue_m2' => 2, 'presence_tue_a1' => 2, 'presence_tue_a2' => 2,
            'presence_wed_m1' => 2, 'presence_wed_m2' => 2, 'presence_wed_a1' => 2, 'presence_wed_a2' => 2,
            'presence_thu_m1' => 2, 'presence_thu_m2' => 2, 'presence_thu_a1' => 2, 'presence_thu_a2' => 2,
            'presence_fri_m1' => 2, 'presence_fri_m2' => 2, 'presence_fri_a1' => 2, 'presence_fri_a2' => 2,
        ];

        $this->createPresences(8, $partly_absent_presences);

        // ID 9 : user with "Absent" everywhere
        $all_absent_presences = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 3, 'presence_mon_a1' => 3, 'presence_mon_a2' => 3,
            'presence_tue_m1' => 3, 'presence_tue_m2' => 3, 'presence_tue_a1' => 3, 'presence_tue_a2' => 3,
            'presence_wed_m1' => 3, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 3, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 3, 'presence_fri_m2' => 3, 'presence_fri_a1' => 3, 'presence_fri_a2' => 3,
        ];

        $this->createPresences(9, $all_absent_presences);
        
        // 3. Create 12 users with realistic presences (IDs 10 to 21)
        // Configuration to guarantee constraints:
        // - 1 period (mon_m1) with only 1 user available
        // - 1 period (tue_m1) with only 2 users available
        // - 1 period (wed_m1) with only 3 users available
        // - 1 period (thu_m1) with all users available
        
        $realistic_users = [];
        
        // User 10 (index 1) : Available at mon_m1, tue_m1, wed_m1, thu_m1 (for constraints)
        $realistic_users[10] = [
            'presence_mon_m1' => 1, 'presence_mon_m2' => 3, 'presence_mon_a1' => 3, 'presence_mon_a2' => 3,
            'presence_tue_m1' => 1, 'presence_tue_m2' => 3, 'presence_tue_a1' => 3, 'presence_tue_a2' => 3,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 3, 'presence_fri_m2' => 3, 'presence_fri_a1' => 3, 'presence_fri_a2' => 3,
        ];
        
        // User 11 (index 2) : Available at tue_m1, wed_m1, thu_m1 (but NOT mon_m1)
        $realistic_users[11] = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 3, 'presence_mon_a1' => 3, 'presence_mon_a2' => 3,
            'presence_tue_m1' => 1, 'presence_tue_m2' => 3, 'presence_tue_a1' => 3, 'presence_tue_a2' => 3,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 3, 'presence_fri_m2' => 3, 'presence_fri_a1' => 3, 'presence_fri_a2' => 3,
        ];
        
        // User 12 (index 3) : Available at wed_m1, thu_m1 (but NOT mon_m1, tue_m1, fri_m1)
        $realistic_users[12] = [
            'presence_mon_m1' => 3, 'presence_mon_m2' => 3, 'presence_mon_a1' => 3, 'presence_mon_a2' => 3,
            'presence_tue_m1' => 3, 'presence_tue_m2' => 3, 'presence_tue_a1' => 3, 'presence_tue_a2' => 3,
            'presence_wed_m1' => 1, 'presence_wed_m2' => 3, 'presence_wed_a1' => 3, 'presence_wed_a2' => 3,
            'presence_thu_m1' => 1, 'presence_thu_m2' => 3, 'presence_thu_a1' => 3, 'presence_thu_a2' => 3,
            'presence_fri_m1' => 3, 'presence_fri_m2' => 3, 'presence_fri_a1' => 3, 'presence_fri_a2' => 3,
        ];
        
        // Users 13-21 : realistic planning with randomized assignment
        for ($i = 13; $i <= 21; $i++) {
            // Generate a random value between 1, 2 or 3 for each period
            $realistic_users[$i] = [
                'presence_mon_m1' => 3, 'presence_mon_m2' => rand(1, 3), 'presence_mon_a1' => rand(1, 3), 'presence_mon_a2' => rand(1, 3),
                'presence_tue_m1' => 3, 'presence_tue_m2' => rand(1, 3), 'presence_tue_a1' => rand(1, 3), 'presence_tue_a2' => rand(1, 3),
                'presence_wed_m1' => 3, 'presence_wed_m2' => rand(1, 3), 'presence_wed_a1' => rand(1, 3), 'presence_wed_a2' => rand(1, 3),
                'presence_thu_m1' => 1, 'presence_thu_m2' => rand(1, 3), 'presence_thu_a1' => rand(1, 3), 'presence_thu_a2' => rand(1, 3),
                'presence_fri_m1' => rand(1, 3), 'presence_fri_m2' => rand(1, 3), 'presence_fri_a1' => rand(1, 3), 'presence_fri_a2' => rand(1, 3), 
            ];
        }
        
        // Create presences for the 12 realistic users (IDs 10 to 21)
        foreach ($realistic_users as $user_id => $presences) {
            $this->createPresences($user_id, $presences);
        }
    }
    
    /**
     * Creates presences for an existing user
     * Checks if presences already exist before inserting
     */
    private function createPresences($user_id, $presences)
    {
        // Check if presences already exist for this user
        $existing = $this->db->table('tbl_presences')
            ->where('fk_user_id', $user_id)
            ->get()
            ->getRowArray();
        
        // If presences already exist, skip to next
        if ($existing) {
            return;
        }
        
        $presence_data = array_merge(['fk_user_id' => $user_id], $presences);
        $this->db->table('tbl_presences')->insert($presence_data);
    }
}

