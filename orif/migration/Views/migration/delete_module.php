<?php

?>
<div id="page-content-wrapper">
    <div class="container">
        <div class="row">
            <div class="col-12">
                    <div>
                        <h1><?= lang('migration_lang.migration_module_delete')?></h1>
                        <div class = "alert alert-info" ><?= lang('migration_lang.delete_module_explanation')?></div>

                    </div>
                    <div class="text-right">
                        <a href="<?= base_url('migration'); ?>" class="btn btn-default">
                            <?= lang('common_lang.btn_cancel'); ?>
                        </a>
                        <a href="<?= base_url(uri_string().'/2'); ?>" class="btn btn-danger">
                            <?= lang('migration_lang.remove'); ?>
                        </a>
                    </div>
            </div>
        </div>
    </div>
</div>
