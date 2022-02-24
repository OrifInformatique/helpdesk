<?php

use CodeIgniter\I18n\Time;
use User\Database;?>
<div class="migrationBody">
    <h1 style="padding-left: 15%">Migrations</h1>
    <?php if ($error!==null):?>

    <div class="alert alert-danger text-center" style="max-width: max(350px,70%);align-self: center;display: block">
        <p><?=$error?></p>
    </div>
    <?php endif;?>
    <div class="migrationViewContainer">
        <div class="migrationViewHeader">
            <span><div class="migrationViewHeaderSelector" style=" left: <?=isset($selected)&&$selected=='migration'?'10.1%':'50%'?>;"></div><h2 class="btn btn-secondary text-white " onclick="moveSelector('l')"><?=lang('migration_lang.header_migration')?></h2><h2 class="btn btn-secondary text-white text-white" onclick="moveSelector('r')"><?=lang('migration_lang.header_history')?></h2></span>
        </div>
        <div class="migrationViewBody">
        </div>
    </div>
    <table class="table-hover table-striped migrationTable" style="display: <?=isset($selected)&&$selected=='migration'?'table':'none'?>">
        <thead>
        <tr><th></th><th><?=lang('migration_lang.module_name')?></th><th><?=lang('migration_lang.migration_name')?></th><th><?=lang('migration_lang.creation_date')?></th><th></th></tr>
        </thead>
        <tbody>
        <?php foreach ($migrations as $migrationmodulelbl => $migrationModuleDatas){?>
        <?php foreach($migrationModuleDatas as $migrationElement){ ?>
                <tr>
                    <td></td>
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
        <table class="table-hover table-striped migrationHistoryTable migrationTable" style="display: <?=isset($selected)&&$selected=='history'?'table':'none'?>">
        <thead>
        <tr><th></th><th><?=lang('migration_lang.module_name')?></th><th><?=lang('migration_lang.migration_class')?></th><th><?=lang('migration_lang.migration_date')?></th><th></th></tr>
        </thead>
        <tbody>
        <?php $i=0;
        if (isset($history))
        foreach ($history as $migrationRow){?>
                <tr>
                    <td></td>
                    <td><?=strtoupper(explode('\\',$migrationRow['class'])[0])?></td><td><?=explode('\\',$migrationRow['class'])[count(explode('\\',$migrationRow['class']))-1]?></td>
                    <td><?=isset($migrationRow['time'])?(new Time())->setTimestamp($migrationRow['time'])->toLocalizedString():'' ?></td>
                    <td>
                        <span>
                            <?php if($migrationRow['status']!=0):?>
                                <a href="<?=base_url('migration/rollback/'.($migrationRow['batch']-1))?>" class="btn btn-primary"><?=lang('migration_lang.rollback')?></a>
                            <?php endif;?>
                            <?php if($migrationRow['status']!=2):?>
                                <a href="<?=base_url('migration/remove/'.base64_encode(json_encode($migrationRow)))?>" class="btn btn-primary btn-danger"><?=lang('migration_lang.remove')?></a>
                            <?php endif
                            ?>
                    </span>
                    </td>
                </tr>

        <?php
        $i++;
        }?>
        </tbody>
    </table>
    <a href="<?=base_url('migration/delete_module/')?>" class="btn btn-danger" style="max-width: 190px;margin-left: 10%;margin-block: 15px">Supprimmer le module</a>
</div>


<script type="text/javascript">
    document.querySelectorAll('#login-bar .col-sm-12.col-md-3.text-right').forEach((loginBarLink)=>{loginBarLink.remove()});
    function moveSelector(direction){
        if (direction==='r') {
            document.querySelector('.migrationViewHeaderSelector').style.left = '50%';
            document.querySelector('.migrationHistoryTable').style.display='table'
            document.querySelector('.migrationTable').style.display='none'
            document.cookie = "selected=history";

        }

        else{
            document.querySelector('.migrationViewHeaderSelector').style.left = '10.1%';
            document.querySelector('.migrationTable').style.display='table'
            document.querySelector('.migrationHistoryTable').style.display='none'
            document.cookie = `selected=migration;`;

        }
    }
    window.history.replaceState(null,null,'<?=base_url('migration')?>')
</script>
