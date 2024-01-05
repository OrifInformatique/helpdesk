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

    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/buttons.css") ?>">

    <link rel="stylesheet" href="<?= base_url("css/helpdesk/general/custom_messages.css") ?>">

    <link rel="stylesheet" href="<?= base_url("css/helpdesk/planning/roles.css") ?>">

    <?php
    // Get the current url
    $current_url = $_SERVER['REQUEST_URI'];

    // If the url ends with "terminal", adds the terminal stylesheet and meta data
    if(strpos($current_url, 'display'))
    {
        echo '<link rel="stylesheet" href="'.base_url("css/helpdesk/terminal/terminal.css").'">';
        echo '<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">';
    }

    // If the url ends with "planning" or "public/", or contains "update_planning" or "add_technician", adds the planning stylesheet
    else if(substr($current_url, -8) === "planning" ||
            substr($current_url, -7) === "public/" ||
            strpos($current_url, 'update_planning') ||
            strpos($current_url, 'add_technician'))
    {
        echo '<link rel="stylesheet" href="' .base_url("css/helpdesk/planning/planning.css").'">';
    }

    // If the url ends with "all_presences", adds the presences stylesheet
    else if(substr($current_url, -13) === "all_presences")
    {
        echo '<link rel="stylesheet" href="'.base_url("css/helpdesk/presences/all_presences.css").'">';
    }

    // If the url ends with "my_presences", adds the presences stylesheet
    else if(substr($current_url, -12) === "my_presences")
    {
        echo '<link rel="stylesheet" href="'.base_url("css/helpdesk/presences/your_presences.css").'">';
    }

    // If the url contains "delete", adds the delete_confirmation stylesheet
    else if(strpos($current_url, 'delete'))
    {
        echo '<link rel="stylesheet" href="'.base_url("css/helpdesk/general/delete_confirmation.css").'">';
    }

    // If the url contains "holidays", adds the holidays stylesheet
    else if(strpos($current_url, 'holidays'))
    {
        echo '<link rel="stylesheet" href="'.base_url("css/helpdesk/holidays/holidays.css").'">';
        echo '<link rel="stylesheet" href="'.base_url("css/helpdesk/holidays/add_holiday.css").'">';
    }


    // // If the url contains "generate", adds the generate_planning stylesheet
    // else if(strpos($current_url, 'generate') !== false)
    // {
    //     echo '<link rel="stylesheet" href="'.base_url("css/helpdesk/planning/generate_planning.css").'">';
    // }

    ?>

</head>
<body>
    <?php
        if (ENVIRONMENT != 'production') {
            echo '<div class="alert alert-warning text-center">CodeIgniter environment variable is set to '.strtoupper(ENVIRONMENT).'. You can change it in .env file.</div>';
        }
    ?>
