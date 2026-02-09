<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Test seeder for presence data
 * 
 * TEST DATABASE : ci4_test
 * 
 * USAGE :
 * - Test migrations : Automatically called when creating the tbl_presences table
 *   (see tests/_support/Database/Migrations/2023-08-08-135000_TblPresences.php)
 * - Unit tests : Used by tests using DatabaseTestTrait
 * - Integration tests : Specifically used by TestPlanningIntegrationCommand
 *   (app/Commands/TestPlanningIntegrationCommand.php) to simulate 20 weeks
 *   of planning generation with deterministic data
 * 
 * SPECIFIC CONTEXT :
 * - Deterministic data (non-random) to ensure test reproducibility
 * - updated_at : Dates between 05.08.2025 and 08.08.2025 (DATETIME format)
 */
class TestInsertPresencesData extends Seeder
{
    public function run()
    {
        // Presence matrix for 10 users
        // Each user has 20 periods (5 days × 4 periods per day)
        // Values : 1 = Present, 2 = Partially absent, 3 = Absent
        // 
        // TEMPORAL CONTEXT :
        // - Test start date : Monday August 18, 2025
        // - updated_at : Dates between 05.08.2025 and 08.08.2025 (DATETIME format)
        // - TEST DATABASE : ci4_test (test data only)
        // 
        // Constraints respected :
        // - Period fri_a2 (index 19) : no user with value 1 (only 2 or 3)
        // - User 2 : completely absent (all 3)
        // - Users 3, 4 : absent on Wednesdays (indices 8-11 = 3)
        // - Users 5, 6 : absent on Wednesdays and Thursdays (indices 8-15 = 3)
        // - Users 7, 8, 9 : absent on Mondays (indices 0-3 = 3) AND 80% present (16 periods with 1 or 2)
        // - User 10 : absent on Mondays and Tuesdays (indices 0-7 = 3)
        // - User 11 : 70% present (6 consecutive periods with 3, 14 with 1 or 2)
        
        $presences = [
            // User 2 : Completely absent (all 3)
            2 => [3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3, 3],
            
            // Users 3, 4 : Absent on Wednesdays (indices 8-11 = 3)
            3 => [1, 1, 1, 2, 1, 1, 2, 1, 3, 3, 3, 3, 1, 1, 1, 2, 1, 1, 2, 2],
            4 => [1, 2, 1, 1, 2, 1, 1, 2, 3, 3, 3, 3, 1, 2, 1, 1, 2, 1, 1, 2],
            
            // Users 5, 6 : Absent on Wednesdays AND Thursdays (indices 8-15 = 3)
            5 => [1, 1, 2, 1, 1, 1, 1, 2, 3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 2, 2],
            6 => [2, 1, 1, 1, 1, 2, 1, 1, 3, 3, 3, 3, 3, 3, 3, 3, 1, 2, 1, 2],
            
            // Users 7, 8, 9 : Absent on Mondays (indices 0-3 = 3) AND 80% present (16 periods with 1 or 2)
            // These 3 users satisfy both "3 people absent on Mondays" and "3 people at 80%"
            7 => [3, 3, 3, 3, 1, 1, 1, 2, 1, 1, 2, 1, 1, 1, 1, 2, 1, 1, 2, 2],
            8 => [3, 3, 3, 3, 1, 2, 1, 1, 1, 2, 1, 1, 1, 1, 2, 1, 1, 2, 1, 2],
            9 => [3, 3, 3, 3, 2, 1, 1, 1, 1, 1, 1, 2, 1, 2, 1, 1, 2, 1, 1, 2],
            
            // User 10 : Absent on Mondays AND Tuesdays (indices 0-7 = 3)
            10 => [3, 3, 3, 3, 3, 3, 3, 3, 1, 1, 1, 2, 1, 1, 2, 1, 1, 1, 2, 2],
            
            // User 11 : 70% present (6 consecutive periods with 3, 14 with 1 or 2)
            11 => [1, 1, 1, 2, 1, 1, 2, 1, 1, 2, 1, 1, 3, 3, 3, 3, 3, 3, 1, 2],
        ];
        
        // Period order corresponding to table columns
        $periods = [
            'presence_mon_m1', 'presence_mon_m2', 'presence_mon_a1', 'presence_mon_a2',
            'presence_tue_m1', 'presence_tue_m2', 'presence_tue_a1', 'presence_tue_a2',
            'presence_wed_m1', 'presence_wed_m2', 'presence_wed_a1', 'presence_wed_a2',
            'presence_thu_m1', 'presence_thu_m2', 'presence_thu_a1', 'presence_thu_a2',
            'presence_fri_m1', 'presence_fri_m2', 'presence_fri_a1', 'presence_fri_a2',
        ];
        
        // Create presences for each user
        foreach ($presences as $user_id => $presence_values) {
            // Build presence array with column names
            $presence_data = [];
            foreach ($periods as $index => $period_name) {
                $presence_data[$period_name] = $presence_values[$index];
            }
            
            // Create presences for this user
            $this->createPresences($user_id, $presence_data);
        }
    }
    
    /**
     * Generates a deterministic updated_at date for a user
     * Dates are distributed between 05.08.2025 and 08.08.2025
     * Format : MySQL DATETIME (Y-m-d H:i:s)
     * 
     * Deterministic distribution :
     * - User 2 : 2025-08-05 08:00:00
     * - User 3 : 2025-08-05 14:30:00
     * - User 4 : 2025-08-06 10:15:00
     * - User 5 : 2025-08-06 16:45:00
     * - User 6 : 2025-08-07 09:20:00
     * - User 7 : 2025-08-07 15:10:00
     * - User 8 : 2025-08-08 11:30:00
     * - User 9 : 2025-08-08 17:00:00
     * - User 10 : 2025-08-05 12:00:00
     * - User 11 : 2025-08-06 13:45:00
     * 
     * @param int $user_id User ID (must be between 2 and 11)
     * @return string Date in 'Y-m-d H:i:s' format
     */
    private function generateUpdatedAt($user_id)
    {
        // Deterministic date mapping for each user
        // Ensures test reproducibility
        $dates_map = [
            2 => '2025-08-05 08:00:00',
            3 => '2025-08-05 14:30:00',
            4 => '2025-08-06 10:15:00',
            5 => '2025-08-06 16:45:00',
            6 => '2025-08-07 09:20:00',
            7 => '2025-08-07 15:10:00',
            8 => '2025-08-08 11:30:00',
            9 => '2025-08-08 17:00:00',
            10 => '2025-08-05 12:00:00',
            11 => '2025-08-06 13:45:00',
        ];
        
        // Return mapped date or default date if ID is not in mapping
        if (isset($dates_map[$user_id])) {
            return $dates_map[$user_id];
        }
        
        // Fallback : generate a date based on ID (should never happen for IDs 2-11)
        $day_offset = ($user_id - 2) % 4; // 0-3 for the 4 days
        $hours = 8 + (($user_id * 3) % 10); // Hours between 8h and 17h
        $minutes = (($user_id * 7) % 60);
        $seconds = (($user_id * 13) % 60);
        
        $date = strtotime("2025-08-05 +{$day_offset} days +{$hours} hours +{$minutes} minutes +{$seconds} seconds");
        
        return date('Y-m-d H:i:s', $date);
    }
    
    /**
     * Creates presences for an existing user
     * Checks if presences already exist before inserting
     * Generates a deterministic updated_at date between 05.08.2025 and 08.08.2025
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
        
        // Check if updated_at column exists
        $query = $this->db->query("SHOW COLUMNS FROM `tbl_presences` LIKE 'updated_at'");
        $column_exists = $query->getNumRows() > 0;
        
        // Build presence data
        $presence_data = [
            'fk_user_id' => $user_id
        ];
        
        // Add updated_at only if column exists
        // (it may not exist if the AddUpdatedAtToPresences migration has not been executed yet)
        if ($column_exists) {
            // Generate a deterministic date between 05.08.2025 and 08.08.2025
            $presence_data['updated_at'] = $this->generateUpdatedAt($user_id);
        }
        
        // Add presence data
        $presence_data = array_merge($presence_data, $presences);
        
        $this->db->table('tbl_presences')->insert($presence_data);
    }
}
