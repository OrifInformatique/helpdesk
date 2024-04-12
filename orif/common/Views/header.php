<?php
/**
 * Header view
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <!-- Copied from Bootstrap model https://getbootstrap.com/docs/4.6/getting-started/introduction/) -->

    <title><?php
        if (!isset($title) || is_null($title) || $title == '') {
            echo lang('common_lang.page_prefix');
        } else {
            echo lang('common_lang.page_prefix').' - '.$title;
        }
    ?></title>

    <!-- Icon -->
    <link rel="icon" type="image/png" href="<?= base_url("images/favicon.png"); ?>" />
    <link rel="shortcut icon" type="image/png" href="<?= base_url("images/favicon.png"); ?>" />

    <!-- Bootstrap  -->
    <!-- Orif Bootstrap CSS personalized with https://bootstrap.build/app -->
    <link rel="stylesheet" href="<?= base_url("css/bootstrap.min.css"); ?>" />
    <!-- Bootstrap icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">
    <!-- jquery, popper and Bootstrap javascript -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js" integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.0/dist/js/bootstrap.min.js" integrity="sha384-+YQ4JLhjyBLPDQt//I+STsc9iw4uQqACwlvpslubQzn4u2UU2UFM80nGisd026JF" crossorigin="anonymous"></script>
    
    <!-- Application scripts -->
    <script src="<?= base_url('Scripts/header/small_devices_header_menu.js') ?>" defer></script>
    <script src="<?= base_url('Scripts/technician_presences/select_presence_for_all_fields.js') ?>" defer></script>

    <!-- Font Awesome Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.1/css/all.min.css">
    
    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Application styles -->
    <link rel="stylesheet" href="<?= base_url("css/MY_styles.css"); ?>" />

    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/colors.css") ?>">

    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/scrollbar.css") ?>">

    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/select.css") ?>">

    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/buttons.css") ?>">

    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/custom_messages.css") ?>">

    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/planning_table.css"); ?>">
    
    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/header.css") ?>">
    
    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/title.css") ?>">
    
    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/confirm_action.css"); ?>">
    
    <link rel="stylesheet" href="<?= base_url("css/helpdesk/planning/planning_nav.css") ?>">
    
    <link rel="stylesheet" href="<?= base_url("css/helpdesk/planning/roles.css") ?>">
    
    <link rel="stylesheet" href="<?= base_url("css/helpdesk/presences/technician_presences.css"); ?>">
    
    <link rel="stylesheet" href="<?= base_url("css/helpdesk/holidays/holidays.css"); ?>">

    <?php // This condition is necessary : we can't remove the login bar only in the terminal page otherwise
    if (strpos($_SERVER['REQUEST_URI'], 'terminal')): ?>
        <link rel="stylesheet" href="<?= base_url("css/helpdesk/terminal/terminal.css"); ?>">
    <?php endif; ?>
</head>
<body>
    <?php
        if (ENVIRONMENT != 'production') {
            echo '<div class="alert alert-warning text-center">CodeIgniter environment variable is set to '.strtoupper(ENVIRONMENT).'. You can change it in .env file.</div>';
        }
    ?>
