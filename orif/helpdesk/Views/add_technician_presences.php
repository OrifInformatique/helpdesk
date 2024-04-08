<?php

/**
 * add_technician_presences view
 * 
 * @author      Orif (DeDy)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 * 
 */

if(isset($users) && !empty($users))
{
    $options = ['' => ''];
    
    foreach($users as $user)
    {
        $options[$user['fk_user_id']] = $user['last_name_user_data'].' '.$user['first_name_user_data'];
    }
}

echo view('Helpdesk\Common\body_start');

echo form_open(base_url('/helpdesk/presences/add_technician_presences'), ['id' => 'add-technician-presences']);

if(isset($users) && !empty($users))
{
    echo form_label(lang('MiscTexts.add_technician_presences_label'));
    echo form_dropdown('technician', $options, isset($old_add_tech_form['technician']) ? $old_add_tech_form['technician'] : '');
    echo '<button type="submit" class="btn btn-continue"><span>'.lang('Buttons.continue').'</span></button>';
}

else
    echo '<p>'.lang('Infos.all_technicians_have_presences').'</p>';
?>
    <a class="btn btn-back" href="<?= base_url('/helpdesk/presences/presences_list') ?>"><span><?= lang('Buttons.back')?></span></a>
<?= form_close() ?>