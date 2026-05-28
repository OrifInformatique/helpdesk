<?php
/**
 * Config for common module
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */

namespace Common\Config;

use CodeIgniter\Config\BaseConfig;

class AdminPanelConfig extends BaseConfig
{
    /** Update this array to customize admin pannel tabs for your needs 
     *  Syntax : ['label'=>'tab label','pageLink'=>'tab link']
    */
    public array $tabs = [
        ['label'=>'user_lang.title_user_list', 'pageLink'=>'user/admin/listUser'],
        ['label'=>'role_lang.title_list_role', 'pageLink'=>'helpdesk/role'],
    ];
}