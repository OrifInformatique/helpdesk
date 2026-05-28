<?php
/**
 * Config for user module
 *
 * @author      Orif (ViDi,HeMa)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (https://www.orif.ch)
 */
namespace User\Config;

use CodeIgniter\Config\BaseConfig;

class UserConfig extends BaseConfig
{
    /* Access levels */
    public int $access_lvl_guest            =   1;
    public int $access_lvl_registered       =   2;
    public int $access_lvl_admin            =   4;

    /* Default access level for Azure logged in users */
    public int $azure_default_access_lvl    =   2;
    
    /* Validation rules */
    public int $username_min_length         =   3;
    public int $username_max_length         =   45;
    public int $password_min_length         =   6;
    public int $password_max_length         =   72;
    public int $email_max_length            =   100;
    
    /* Other rules */
    public string $password_hash_algorithm     =   PASSWORD_BCRYPT;
}