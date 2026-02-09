<?php

namespace Tests\Support\Database\Seeds;

use CodeIgniter\Database\Seeder;

/**
 * Test seeder for holidays
 * 
 * TEST DATABASE : ci4_test
 * This seeder is used exclusively for unit and integration tests.
 * 
 * This seeder provides a baseline set of holidays for testing.
 * Additional holidays can be added dynamically during integration tests.
 * 
 * Holidays included:
 * - Christmas holidays (Noël): 2025-12-23 to 2026-01-02
 */
class TestInsertHolidaysData extends Seeder
{
    public function run()
    {
        $db = \Config\Database::connect('tests');
        
        $data = [
            [
                'name_holiday' => 'Noël',
                'start_date_holiday' => '2025-12-23 12:00:00',
                'end_date_holiday' => '2026-01-02 23:59:00',
            ],
        ];
        
        foreach ($data as $holiday) {
            // Check if record already exists (idempotent)
            $existing = $db->table('tbl_holidays')
                ->where('name_holiday', $holiday['name_holiday'])
                ->where('start_date_holiday', $holiday['start_date_holiday'])
                ->where('end_date_holiday', $holiday['end_date_holiday'])
                ->get()
                ->getRowArray();
            
            if (!$existing) {
                $db->table('tbl_holidays')->insert($holiday);
            }
        }
    }
}
