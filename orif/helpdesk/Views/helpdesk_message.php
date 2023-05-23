<?php
/**
 * welcome_message view
 *
 * @author      Orif (BlAl)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
?>

<style>
  table {
    border-collapse: collapse;
    width: 100%;
    }

  th, td {
    padding: 8px;
    text-align: center;
    border: 1px solid #ddd;
  }

  thead tr:nth-child(1) th {
    text-align: center;
    font-size: 20px;
    font-weight: bold;
  }

  thead tr:nth-child(2) th {
    font-weight: bold;
  }

  tbody tr:nth-child(even) {
    background-color: #f2f2f2;
  }

  .week {
    background-color: #eee;
    padding: 8px;
    font-size: 18px;
    text-align: center;
  }

  .bg-green {
    background-color: #c5deb5;
  }

  .bg-yellow {
    background-color: #e5f874;
  }

  .bg-orange {
    background-color: #ffd965;
  }

  
</style>

<div class="container-fluid">

  <a class="btn btn-primary mb-3" href="<?=base_url('helpdesk/home/presence')?>">Presence personnel</a>

  <div class="d-flex justify-content-center">
    <div class="bg-green border border-xs-1 p-2 rounded-3 mx-4">Technicien d'astreinte</div> <!-- c5deb5 -->
    <div class="bg-yellow border-xs-1 p-2 rounded rounded-3 mx-4">Technicien de backup</div> <!-- e5f874 -->
    <div class="bg-orange border-xs-1 p-2 rounded rounded-3 mx-4">Technicien de réserve</div> <!-- ffd965 -->
  </div>

  <div class="week">
    Planning de la semaine du 
    <span class="start-date">
      <!-- Affiche le lundi de la semaine en cours -->
      <?php echo date('d/m/Y', strtotime('last monday')); ?>
    </span>
    au 
    <span class="end-date">
      <!-- Affiche le vendredi de la semaine en cours -->
      <?php echo date('d/m/Y', strtotime('friday')); ?>
    </span>
  </div>


  <table class="table-responsive position-relative">
    <thead>
      <tr>
        <th rowspan="2">Technicien</th>
        <th colspan="4">Lundi</th>
        <th colspan="4">Mardi</th>
        <th colspan="4">Mercredi</th>
        <th colspan="4">Jeudi</th>
        <th colspan="4">Vendredi</th>
      </tr>
      <tr>
        <th>8:00 10:00</th>
        <th>10:00 12:00</th>
        <th>12:45 15:00</th>
        <th>15:00 16:57</th>
        <th>8:00 10:00</th>
        <th>10:00 12:00</th>
        <th>12:45 15:00</th>
        <th>15:00 16:57</th>
        <th>8:00 10:00</th>
        <th>10:00 12:00</th>
        <th>12:45 15:00</th>
        <th>15:00 16:57</th>
        <th>8:00 10:00</th>
        <th>10:00 12:00</th>
        <th>12:45 15:00</th>
        <th>15:00 16:57</th>
        <th>8:00 10:00</th>
        <th>10:00 12:00</th>
        <th>12:45 15:00</th>
        <th>15:00 16:57</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <th>Aleksa Blagojevic Martins</th>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <th>Technicien 2</th>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
      <tr>
        <th>Technicien 3</th>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
        <td></td>
      </tr>
    </tbody>
  </table>
</div>
