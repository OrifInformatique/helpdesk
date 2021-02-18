<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Copied from Bootstrap model https://getbootstrap.com/docs/4.6/getting-started/introduction/) -->

    <title><?php
        if (!isset($title) || is_null($title) || $title == '') {
            echo lang('Common_lang.page_prefix');
        } else {
            echo lang('Common_lang.page_prefix').' - '.$title;
        }
    ?></title>

    <!-- Icon -->
    <link rel="shortcut icon" href="<?= base_url("images/favicon.ico"); ?>" type="image/x-icon" />

    <!-- Bootstrap  -->
    <!-- Orif Bootstrap CSS personalized with https://bootstrap.build/app -->
    <link rel="stylesheet" href="<?= base_url("css/bootstrap.min.css"); ?>" />
    <!-- jquery, popper and Bootstrap javascript -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Application styles -->
    <link rel="stylesheet" href="<?= base_url("css/MY_styles.css"); ?>" />
</head>
<body>
    <?php
        if (ENVIRONMENT != 'production') {
            echo '<div class="alert alert-warning text-center">CodeIgniter environment variable is set to '.strtoupper(ENVIRONMENT).'. You can change it in .env file.</div>';
        }
    ?>