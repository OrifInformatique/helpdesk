<?php
?>
<div class="overlay-modal">
<div style="max-width: 600px">
<div class="col-sm-12">
    <div class="card">
        <div class="bg-primary text-white card-header"><span class="col-sm-4"><?=$moduleName?></span><span class="col-sm-4"><?=$className?></span></div>
        <div class="card-body">
            <?=lang('migration_lang.cancel_migration')?>
        </div>
        <div class="card-footer">
            <a class="btn btn-primary text-white" href="<?=base_url('migration/rollback').'/'.$batchNumber?>"><?=lang('common_lang.yes')?></a>
            <a class="btn btn-outline-secondary" onclick="closePopup(event)"><?=lang('common_lang.btn_cancel')?></a>
        </div>
    </div>
</div>
</div>
</div>
<script type="text/javascript" src="<?=base_url('Scripts/migrationscripts.js')?>" defer></script>
