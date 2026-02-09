<?php

/**
 * Spark command to manage holidays
 * 
 * Usage: php spark holidays:manage
 * 
 * This command allows deleting and adding holidays in the database
 */

namespace App\Commands;

use CodeIgniter\CLI\BaseCommand;
use CodeIgniter\CLI\CLI;
use Helpdesk\Models\Holidays_model;

class ManageHolidays extends BaseCommand
{
    protected $group       = 'Holidays';
    protected $name        = 'holidays:manage';
    protected $description = 'Manages holidays: removes duplicates and adds new entries';

    public function run(array $params)
    {
        CLI::write('========================================', 'white');
        CLI::write('GESTION DES VACANCES', 'white');
        CLI::write('========================================', 'white');
        CLI::newLine();

        $holidays_model = new Holidays_model();

        // 0. Supprimer tous les doublons
        CLI::write('0. Suppression des doublons...', 'cyan');
        $duplicateStats = $holidays_model->removeDuplicates();
        if ($duplicateStats['deleted'] > 0) {
            CLI::write("   ✓ Supprimé {$duplicateStats['deleted']} doublon(s), conservé {$duplicateStats['kept']} vacance(s) unique(s).", 'green');
        } else {
            CLI::write('   ✓ Aucun doublon trouvé.', 'green');
        }
        CLI::newLine();

        // 1. Supprimer les IDs 1 et 2
        CLI::write('1. Suppression des IDs 1 et 2...', 'cyan');
        
        $idsToDelete = [1, 2];
        foreach ($idsToDelete as $id) {
            $holiday = $holidays_model->find($id);
            if ($holiday) {
                $holidays_model->delete($id);
                CLI::write("   ✓ Supprimé : ID {$id} - {$holiday['name_holiday']} ({$holiday['start_date_holiday']} à {$holiday['end_date_holiday']})", 'green');
            } else {
                CLI::write("   ⚠ ID {$id} non trouvé.", 'yellow');
            }
        }
        
        CLI::newLine();

        // 2. Add "jeune fédéral"
        CLI::write('2. Adding "jeune fédéral" holiday...', 'cyan');
        
        $jeuneFederalData = [
            'name_holiday' => 'jeune fédéral',
            'start_date_holiday' => '2025-09-22 00:00:00',
            'end_date_holiday' => '2025-09-22 23:59:00'
        ];
        
        // Find all occurrences of "jeune fédéral" with these exact dates
        $existingJeuneFederal = $holidays_model->where('name_holiday', 'jeune fédéral')
                                                ->where('start_date_holiday', $jeuneFederalData['start_date_holiday'])
                                                ->where('end_date_holiday', $jeuneFederalData['end_date_holiday'])
                                                ->findAll();
        
        if (!empty($existingJeuneFederal)) {
            // Supprimer les doublons, garder seulement le premier
            $keepId = $existingJeuneFederal[0]['id_holiday'];
            $deletedCount = 0;
            foreach ($existingJeuneFederal as $holiday) {
                if ($holiday['id_holiday'] != $keepId) {
                    $holidays_model->delete($holiday['id_holiday']);
                    $deletedCount++;
                }
            }
            if ($deletedCount > 0) {
                CLI::write("   ⚠ Supprimé {$deletedCount} doublon(s) de 'jeune fédéral'.", 'yellow');
            }
            CLI::write('   ⚠ Les vacances "jeune fédéral" existent déjà avec ces dates.', 'yellow');
        } else {
            $holidays_model->insert($jeuneFederalData);
            CLI::write("   ✓ Ajouté : {$jeuneFederalData['name_holiday']} ({$jeuneFederalData['start_date_holiday']} à {$jeuneFederalData['end_date_holiday']})", 'green');
        }
        
        CLI::newLine();

        // 3. Add Christmas holidays
        CLI::write('3. Adding Christmas holidays...', 'cyan');
        
        $noelData = [
            'name_holiday' => 'Noël',
            'start_date_holiday' => '2025-12-23 12:00:00',
            'end_date_holiday' => '2026-01-02 23:59:00'
        ];
        
        // Find all occurrences of "Noël" with these exact dates
        $existingNoel = $holidays_model->where('name_holiday', 'Noël')
                                        ->where('start_date_holiday', $noelData['start_date_holiday'])
                                        ->where('end_date_holiday', $noelData['end_date_holiday'])
                                        ->findAll();
        
        if (!empty($existingNoel)) {
            // Supprimer les doublons, garder seulement le premier
            $keepId = $existingNoel[0]['id_holiday'];
            $deletedCount = 0;
            foreach ($existingNoel as $holiday) {
                if ($holiday['id_holiday'] != $keepId) {
                    $holidays_model->delete($holiday['id_holiday']);
                    $deletedCount++;
                }
            }
            if ($deletedCount > 0) {
                CLI::write("   ⚠ Supprimé {$deletedCount} doublon(s) de 'Noël'.", 'yellow');
            }
            CLI::write('   ⚠ Les vacances de Noël existent déjà avec ces dates.', 'yellow');
        } else {
            $holidays_model->insert($noelData);
            CLI::write("   ✓ Ajouté : {$noelData['name_holiday']} ({$noelData['start_date_holiday']} à {$noelData['end_date_holiday']})", 'green');
        }
        
        CLI::newLine();

        // 4. Afficher toutes les vacances
        CLI::write('4. Liste actuelle des vacances:', 'cyan');
        $allHolidays = $holidays_model->orderBy('start_date_holiday', 'ASC')->findAll();
        
        if (empty($allHolidays)) {
            CLI::write('   Aucune vacance enregistrée.', 'yellow');
        } else {
            foreach ($allHolidays as $holiday) {
                CLI::write("   - ID {$holiday['id_holiday']}: {$holiday['name_holiday']} ({$holiday['start_date_holiday']} à {$holiday['end_date_holiday']})", 'white');
            }
        }
        
        CLI::newLine();
        CLI::write('✓ Opération terminée avec succès!', 'green');
    }
}
