<?php
/**
 * English translations for user module
 * 
 * @author      Orif (KoYo)
 * @link        https://github.com/OrifInformatique
 * @copyright   Copyright (c), Orif (http://www.orif.ch)
 */

return[
// Page titles
'title_user_list'                => 'User list',
'title_user_update'              => 'Edit a user',
'title_user_new'                 => 'Add a user',
'title_user_delete'              => 'Delete a user',
'title_user_password_reset'      => 'Reset password',
'title_page_login'               => 'Sign in',
'title_administration'           => 'Administration',
'title_register_account'         => 'Register account',
'title_email_validation'         => 'Validate e-mail',

// Buttons
'btn_admin'                      => 'Administration',
'btn_login'                      => 'Sign in',
'btn_logout'                     => 'Sign out',
'btn_connect_with_local_account' => 'Sign in with local account',
'btn_change_my_password'         => 'Change my password',
'btn_back'                       => 'Return',
'btn_cancel'                     => 'Cancel',
'btn_save'                       => 'Register',
'btn_hard_delete_user'           => 'Delete this user',
'btn_disable_user'               => 'Disable this user',
'btn_next'                       => 'Next',

// Fields labels
'field_username'                 => 'Username',
'field_password'                 => 'Password',
'field_email'                    => 'E-mail',
'field_old_password'             => 'Old password',
'field_new_password'             => 'New password',
'field_password_confirm'         => 'Confirm password',
'field_usertype'                 => 'User type',
'field_user_active'              => 'Activated',
'field_deleted_users_display'    => 'Display disabled users',
'field_login_input'              => 'Username or E-mail',
'field_verification_code'        => 'Verification code',

// Error messages
'msg_err_user_not_exist'         => 'Selected user doesn\'t exist',
'msg_err_user_already_inactive'  => 'User is alreade inactive',
'msg_err_user_already_active'    => 'User is already active',
'msg_err_user_type_not_exist'    => 'User type doesn\'t exist',
'msg_err_username_not_unique'    => 'This username is already in use, Please chose another one',
'msg_err_useremail_not_unique'   => 'This e-mail is already in use, Please chose another one',
'msg_err_access_denied_header'   => 'Access denied',
'msg_err_access_denied_message'  => 'You are not authorised access to this function',
'msg_err_invalid_password'       => 'Username and password invalid',
'msg_err_invalid_old_password'   => 'Old password is invalid',
'msg_err_password_not_matches'   => 'The password doesn\'t match with confirmation of password.',
'msg_err_azure_unauthorized'     => 'Log in with Microsoft Azure is not available. Please check client secret validity',
'msg_err_default'                => 'An error occured',
'msg_err_azure_no_token'         => 'Azure has not responded',
'msg_err_azure_mismatch'         => 'Tokens do not match',
'msg_err_validation_code'        => 'Verification code entered is invalid.',
'msg_err_attempts'               => 'Attempts left: ',

// Error code messages
'azure_error'                    => 'Azure',
'code_error_401'                 => '401 - Not Authorized',
'code_error_403'                 => '403 - Access denied',

// Other texts
'what_to_do'                     => 'What do you wish to do ?',
'user'                           => 'User',
'user_delete'                    => 'Deactivate or delete this user',
'user_reactivate'                => 'Recover this user',
'user_disabled_info'             => 'This user is deactivated. You can recover it by clicking on the corresponding link.',
'user_delete_explanation'        => 'The deactivation of a user account makes it possible to make the account unusable while keeping the informations in the archives. '
                                         .'This notably makes it possible to keep the history of actions done by that account.<br><br>'
                                         .'In case of permanent deletion, all information about that user will be deleted.',
'user_allready_disabled'         => 'This user is already disabled. Would you like to delete it permanently ?',
'user_update_usertype_himself'   => 'You can not update your own user type. That action must be done by another administrator.',
'user_delete_himself'            => 'You can not deactivate or delete your own account. That action must be done by another administrator.',
'page_my_password_change'        => 'Change my password',
'user_first_azure_connexion'     => 'You are using Microsoft login for the first time. To register your account, please indicate your e-mail finishing by @formation.orif.ch or @orif.ch.',
'user_validation_code'           => 'A verification code has been sent to your E-mail.<br>Please Enter the code.'

];