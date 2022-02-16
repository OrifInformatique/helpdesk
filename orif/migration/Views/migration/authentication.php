<?php
?>
<style>
    .header{
        color: white;
        text-align: center;
        padding-block: 5px;
    }
    .section-title{
        padding-left: 20px;
    }
    .background{
        display: flex;
        flex-direction: row;
        align-items: center;
        justify-content: space-between;
        width: 80%;
        height: 80%;
        border-radius: 100%;
        box-sizing: border-box;
        padding-block-start: 10px;
        padding: 15% 10%;
        border: 8px #bfa78a solid;
        animation: rotate 0.9s linear infinite;

    }
    .item{
        position: relative;
        align-self: start;
        display: block;
        border-radius: 100%;
        width: 10px;
        height: 10px;
        bottom: 63%;

    }
    .item:nth-child(1){
        background-color: #958259;

    }

    .item:nth-child(2){
        background-color: white;
        position: relative;
        bottom: 73%;
    }
    .item:nth-child(3){
        background-color: #958259;

    }

    @keyframes rotate {
        0%{
            transform:rotate(0deg);
        }
        100%{
            transform:rotate(360deg);

        }

    }



</style>
<head>
    <link href="<?=base_url('css/bootstrap.min.css') ?>" rel="stylesheet">
</head>
<header class="header bg-primary"><h1 class="card-title"><?= lang('migration_lang.initialization') ?>  <?=lang('common_lang.app_title')?></h1></header>
<body>
<span style="display: flex;flex-direction: column"><h3 class="alert-primary section-title"><?= lang('migration_lang.initialization') ?> <?=lang('common_lang.app_title')?></h3>
    <div id="message" class="alert-danger" style="max-width: 600px;border-radius: 2px;padding-left: 20px">
        <?=\CodeIgniter\Services::session()->get('mig_authorized')=='false'?lang('migration_lang.wrong_credentials'):null?>
    </div>
    <form method="post" action="<?=base_url('migration/authenticate')?>" enctype="multipart/form-data">
        <p style="padding-left: 1.2rem"><?=lang('migration_lang.migration_connexion_explanation')?></p>
    <label for="migpassword" class="section-title"><?=lang('user_lang.field_password')?></label>
    <input type="password" id="migpassword" name="migpassword" class="form-control" style="max-width: 300px;margin-left: 15px;margin-bottom: 5px" required>
    <input type="submit" class="btn btn-primary" style="max-width: 150px;margin-left: 190px"/></input>
    </form>
</span>




</div>
</body>
<script type="text/javascript">
    document.querySelectorAll('#login-bar .col-sm-12.col-md-3.text-right').forEach((loginBarLink)=>{loginBarLink.remove()});
</script>