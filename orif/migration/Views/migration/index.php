<?php
use CodeIgniter\I18n\Time;
use User\Database;?>

<div id="migrationBody" class="container" >
    <!-- Page title -->
    <h2><?=lang("migration_lang.migration_title") ?></h2>
    <div class="alert-danger row align-items-center justify-content-between mb-2 pl-2 pr-2">
        <span class="text-left"><?=lang("migration_lang.delete_module_warning") ?></span>
        <a href="<?=base_url('migration/delete_module/')?>" class="btn btn-danger text-right" style="max-width: 190px;margin-block: 15px"><?=lang('migration_lang.btn_hard_delete_migration')?></a>
    </div>
    <!-- Error messages -->
    <?php if ($error!==null):?>
        <div class="alert alert-danger text-center" style="max-width: max(350px,70%);align-self: center;display: block">
            <p><?=$error?></p>
        </div>
    <?php endif;?>

    <!-- Applied and "to apply" migration tabs -->
    <ul class="nav nav-tabs">
        <li class="nav-item">
            <a aria-current="page" class="nav-link active text-primary" onclick="selectTab(event,'M')"><?=lang('migration_lang.header_migration') ?></a>
        </li>
        <li class="nav-item"><a class="nav-link text-primary" onclick="selectTab(event,'H')"><?=lang('migration_lang.header_history') ?></a></li>
    </ul>
    <?php foreach($migrations as $migrationModuleList) :?>
    <?php foreach ($migrationModuleList as $migration):?>
        <?php if ($migration['status']==0):?>
    <div class="alert-info row align-items-center justify-content-between mt-2 mb-2 pl-2 pr-2 pb-2 pt-2">
        <span class="text-left"><?=lang("migration_lang.migrate_all_explain") ?></span>
        <a class="btn btn-primary text-white" style="margin-block: 15px"  onclick="migrateAllMigration()"><?=lang('migration_lang.apply_all_migrations')?></a>
    </div>
        <?php break;endif;?>
    <?php endforeach;?>
    <?php endforeach;?>
    <table class="table-hover table-striped migrationTable col-sm-12" style="display: <?=isset($selected)&&$selected=='migration'?'table':'none'?>">
        <thead>
        <tr><th></th><th><?=lang('migration_lang.module_name')?></th><th><?=lang('migration_lang.migration_name')?></th><th><?=lang('migration_lang.creation_date')?></th><th></th></tr>
        </thead>
        <tbody>
        <?php foreach ($migrations as $migrationmodulelbl => $migrationModuleDatas){?>
            <tr class="migrationModuleIndex"><td><input type="checkbox" class="form-check"/></td><td style="pointer-events: none"><?=strtoupper($migrationmodulelbl)?></td></tr>
        <?php foreach($migrationModuleDatas as $migrationElement){ ?>
                <tr>
                    <td><input type="checkbox" class="form-check"/> </td>
                    <td><?=strtoupper($migrationmodulelbl)?></td><td><?=substr($migrationElement['name'],0,strlen($migrationElement['name'])-4)?></td>
                    <td><?=(new Time(str_replace('-','/',$migrationElement['creation_date'])))->toLocalizedString()?></td>
                    <td>
                        <span>
                            <?php if($migrationElement['status']!=1):?>
                            <a href="<?=base_url('migration/migrate/'.base64_encode(json_encode($migrationElement)))?>" class="btn btn-primary btn-success"><?=lang('migration_lang.migrate')?></a>
                            <?php endif;?>
                            <?php if($migrationElement['status']!=2):?>
                            <a href="<?=base_url('migration/remove/'.base64_encode(json_encode($migrationElement)))?>" class="btn btn-primary btn-danger"><?=lang('migration_lang.remove')?></a>
                            <?php endif?>
                    </span>
                    </td>
                </tr>
        <?php }?>

        <?php }?>
        </tbody>

    </table>
        <table class="table-hover table-striped migrationHistoryTable migrationTable col-sm-12" style="display: <?=isset($selected)&&$selected=='history'?'table':'none'?>">
            <thead>
        <tr><th></th><th><?=lang('migration_lang.module_name')?></th><th><?=lang('migration_lang.migration_class')?></th><th><?=lang('migration_lang.migration_date')?></th><th><?=lang('migration_lang.batch_number')?></th></tr>
        </thead>
        <tbody>
        <?php $i=0;
        if (isset($history))
        foreach ($history as $migrationRow){?>
                <tr>
                    <td></td>
                    <td><?=strtoupper(explode('\\',$migrationRow['class'])[0])?></td><td><?=explode('\\',$migrationRow['class'])[count(explode('\\',$migrationRow['class']))-1]?></td>
                    <td><?=isset($migrationRow['time'])?(new Time())->setTimestamp($migrationRow['time'])->toLocalizedString():'' ?></td>
                    <td><?=($migrationRow['batch'])?></td>
                    <td>
                        <span>
                            <?php if($migrationRow['status']!=0):?>
                                <a class="btn btn-primary" onclick="displayPopup('<?=strtoupper(explode('\\',$migrationRow['class'])[0])?>','<?=explode('\\',$migrationRow['class'])[count(explode('\\',$migrationRow['class']))-1]?>','<?=($migrationRow['batch'])-1?>')"><?=lang('migration_lang.rollback')?></a>
                            <?php endif;?>
                            <?php if($migrationRow['status']!=2):?>
                                <a href="<?=base_url('migration/remove/'.base64_encode(json_encode($migrationRow)))?>" class="btn btn-primary btn-danger"><?=lang('migration_lang.remove')?></a>
                            <?php endif;
                            ?>
                    </span>
                    </td>
                </tr>

        <?php
        $i++;
        }?>

        </tbody>
        </table>
    <span class="multipleControlMigration" style="display: none">
        <span class="text-white"><p class="text-white migrationSelectedElement">N</p><span><?=lang('migration_lang.selected_elements')?></span></span><span class="migrationControlContainer"><span><button class="btn btn-success" onclick="migrateMultipleFile()"><?=lang('migration_lang.migrate')?></button><button class="btn btn-danger" onclick="removeMultipleFile()"><?=lang('migration_lang.remove')?></button></span></span>
    </span>
</div>


<script type="text/javascript" src="<?=base_url('Scripts/migrationscripts.js')?>" defer></script>
<script defer>
    setTimeout(()=>{initMigrationView();},100)
</script>
