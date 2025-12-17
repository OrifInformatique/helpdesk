<?php
/**
 * Roles List View
 *
 * @author      Orif (WaAd)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
helper("form");
?>
<div class="container">
    <div class="row">
        <div class="col">
            <h1 class="title-section"><?= lang('role_lang.title_list_role') ?? 'Liste des rôles'; ?></h1>
        </div>
    </div>
    <div class="row">
        <div class="col-sm-3 text-left">
            <a href="<?= base_url('helpdesk/role/saveRole'); ?>" class="btn btn-primary">
                <?= lang('common_lang.btn_new_m') ?? 'Nouveau'; ?>
            </a>
        </div>
    </div>
    <div class="row mt-2">
        <table class="table table-hover">
        <thead>
            <tr>
                <th><?= lang('role_lang.field_name_role') ?? 'Nom du rôle'; ?></th>
                <th><?= lang('role_lang.field_priority_role') ?? 'Priorité'; ?></th>
                <th><?= lang('role_lang.field_min_assignation_first_technician_role') ?? 'Min 1er tech'; ?></th>
                <th><?= lang('role_lang.field_max_assignation_first_technician_role') ?? 'Max 1er tech'; ?></th>
                <th><?= lang('role_lang.field_min_assignation_second_technician_role') ?? 'Min 2ème tech'; ?></th>
                <th><?= lang('role_lang.field_max_assignation_second_technician_role') ?? 'Max 2ème tech'; ?></th>
                <th><?= lang('role_lang.field_min_assignation_third_technician_role') ?? 'Min 3ème tech'; ?></th>
                <th><?= lang('role_lang.field_max_assignation_third_technician_role') ?? 'Max 3ème tech'; ?></th>
                <th></th>
            </tr>
        </thead>
        <tbody>
            <?php if (!empty($roles)) : ?>
                <?php foreach($roles as $role) { ?>
                    <tr>
                        <td><a href="<?= base_url('helpdesk/role/saveRole/'.$role['id_role']); ?>"><?= esc($role['name_role']); ?></a></td>
                        <td><?= esc($role['priority_role']); ?></td>
                        <td><?= esc($role['min_assignation_first_technician_role'] ?? '-'); ?></td>
                        <td><?= esc($role['max_assignation_first_technician_role'] ?? '-'); ?></td>
                        <td><?= esc($role['min_assignation_second_technician_role'] ?? '-'); ?></td>
                        <td><?= esc($role['max_assignation_second_technician_role'] ?? '-'); ?></td>
                        <td><?= esc($role['min_assignation_third_technician_role'] ?? '-'); ?></td>
                        <td><?= esc($role['max_assignation_third_technician_role'] ?? '-'); ?></td>
                        <td><a href="<?= base_url('helpdesk/role/deleteRole/'.$role['id_role']); ?>" class="close">×</a></td>
                    </tr>
                <?php } ?>
            <?php else : ?>
                <tr>
                    <td colspan="9" class="text-center"><?= lang('common_lang.no_data') ?? 'Aucun rôle trouvé'; ?></td>
                </tr>
            <?php endif; ?>
        </tbody>
        </table>
    </div>
</div>

