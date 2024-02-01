<?php

/**
 * Start of body component
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

?>

<div class="container-fluid">
  <?php if(isset($title) && !empty($title)){echo '<h2>'.$title.'</h2>';} ?>

  <?php if(isset($messages['success'])): ?>
      <div class="d-flex justify-content-center alert alert-success custom-alert fade">
          <?= '<i class="far fa-check-circle"></i>'.$messages['success']; ?>
      </div>
  <?php endif; ?>

  <?php if(isset($messages['error'])): ?>
      <div class="d-flex justify-content-center alert alert-danger custom-alert fade">
          <?= ('<i class="fa-solid fa-circle-exclamation"></i>'.$messages['error']); ?>
      </div>
  <?php endif; ?>