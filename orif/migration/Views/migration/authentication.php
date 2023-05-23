<?php
?>


<head>
    <link href="<?=base_url('css/bootstrap.min.css') ?>" rel="stylesheet">
</head>
<div class="container">
<header class="header bg-primary text-white align-text-top col-sm-12"><h1 class="card-title"><?= lang('migration_lang.initialization') ?>  <?=lang('common_lang.app_title')?></h1></header>
<body>
<span><h3 class="col-sm-12 alert-primary section-title"><?= lang('migration_lang.initialization') ?> <?=lang('common_lang.app_title')?></h3>
    <div id="message" class="alert-danger">
        <?=\CodeIgniter\Services::session()->get('mig_authorized')=='false'?lang('migration_lang.wrong_credentials'):null?>
    </div>
    <form method="post" action="<?=base_url('migration/authenticate')?>" class="col-sm-12" enctype="multipart/form-data">
        <p><?=lang('migration_lang.migration_connexion_explanation')?></p>
    <label for="migpassword" class="section-title"><?=lang('user_lang.field_password')?></label>
    <input type="password" id="migpassword" name="migpassword" class="form-control col-sm-4" required>
        <div class="col-sm-4 text-right" style="padding-top: .75rem">
        <input type="submit" class="btn btn-primary"/></input>
        </div>

    </form>
</span>




</div>
</body>
</div>
<script type="text/javascript">
    document.querySelectorAll('#login-bar .col-sm-12.col-md-3.text-right').forEach((loginBarLink)=>{loginBarLink.remove()});
</script>