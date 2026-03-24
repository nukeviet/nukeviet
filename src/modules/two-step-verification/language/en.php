<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '20/07/2023, 07:15';
$lang_translator['copyright'] = '@Copyright (C) 2010 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['confirm_password'] = 'Enter password to continue';
$lang_module['confirm_password_info'] = 'To use this feature, you need to confirm your password, enter your password in the box below and click Confirm';
$lang_module['confirm'] = 'Confirm';
$lang_module['secretkey'] = 'Secret Key';
$lang_module['wrong_confirm'] = 'Code is incorrect, please re-enter';
$lang_module['cfg_step1'] = 'Scan the QR code';
$lang_module['cfg_step1_manual'] = 'Scan the QR-code with an app that supports Two-step authentication on your phone (eg. Google Authenticator). If the QR-code cannot be scanned, please';
$lang_module['cfg_step1_manual1'] = 'click here';
$lang_module['cfg_step1_manual2'] = 'to enter the Setup Key manually';
$lang_module['cfg_step1_note'] = 'Note: You should keep this key secret';
$lang_module['cfg_step2_info'] = 'If the above operation is successful, the application will display a 6-digit string. Please enter that string in the box below to confirm.';
$lang_module['cfg_step2_info2'] = '6-digit code';
$lang_module['cfg_step2'] = 'Enter the code from the app';
$lang_module['title_2step'] = 'Two-step authentication';
$lang_module['title_2step_off'] = 'Two-step authentication is off';
$lang_module['title_2step_off_note'] = 'Two-step authentication helps protect your account better by requiring an additional verification step beyond your password. Enable two-step authentication today to enhance the security of your personal information!';
$lang_module['title_2step_off_note2'] = 'Two-step authentication helps protect your account better by requiring an additional verification step beyond your password';
$lang_module['title_2step_off_note3'] = 'We recommend that you enable two-step authentication on your account. If you need to change your configuration or create new backup codes, you can do so in the settings below instead of turning off this feature';
$lang_module['title_2step_turnon'] = 'Enable two-step authentication (2FA)';
$lang_module['status_on'] = 'On';
$lang_module['status_off'] = 'Off';
$lang_module['active_2step'] = 'On';
$lang_module['deactive_2step'] = 'Off';
$lang_module['backupcode_2step'] = 'You have <strong>%d</strong> unused backup codes';
$lang_module['backupcode_2step_view'] = 'See backup codes';
$lang_module['backupcode_2step_note'] = 'Note: Please store backup codes carefully! If you lose your phone, you can use them to verify account access. If you forget your codes and lose your phone, you won\'t be able to sign in to your account.';
$lang_module['creat_other_code'] = 'Regenerate backup codes';
$lang_module['creat_other_note'] = 'When regenerating backup codes, please download or print them and store them in a safe place. Old backup codes that have not been used will no longer be available';
$lang_module['change_2step_notvalid'] = 'Your account doesn\'t have a password, so Two-Step Authentication can\'t be changed. Please create a password and then return to this page.<br />Please <a href="%s">click here</a> to create a password';
$lang_module['deactive_mess'] = 'Do you really want to turn off two-step authentication?';
$lang_module['turnoff_2step'] = 'Turn off two-step authentication';
$lang_module['setup_2step'] = 'Set up two-step authentication';
$lang_module['setup_key'] = 'Setup Key';
$lang_module['recovery_codes'] = 'Recovery Codes';
$lang_module['recovery_codes_note'] = 'Helps you access your account if you lose access to your device and cannot receive a two-step authentication code. Please ensure you keep them safe and memorable';
$lang_module['active_2tep_success'] = 'Successfully activated two-step authentication using app code';
$lang_module['active_2tep_success1'] = 'You have successfully activated two-step authentication using app code. Now, after logging into your account with a password, you need to enter the two-step verification code as you just did';
$lang_module['active_2tep_success2'] = 'In case you lose or damage your device and cannot access the app to get the two-step verification code, the backup codes below will help you complete the login';
$lang_module['active_2tep_success3'] = 'Keep your backup codes as safe as your password. We recommend storing them with a password manager like <a href="https://1password.com/" target="_blank">1Password</a>, <a href="https://authy.com/" target="_blank">Authy</a>, or <a href="https://keepersecurity.com/" target="_blank">Keeper</a>';
$lang_module['active_2tep_success'] = 'Successfully activated two-step authentication using app code';
$lang_module['active_2tep_success4'] = 'Please download, print, or copy and ensure you have stored them carefully before continuing';
$lang_module['active_2tep_review1'] = 'Your account is secured, two-step authentication (2FA) has been activated';
$lang_module['active_2tep_review2'] = 'Additional authentication methods';
$lang_module['active_2tep_review3'] = 'Additional authentication methods will help you access your account in case you lose your device and forget even your backup codes. Note: If all codes from the app, backup codes, and additional authentication methods cannot be used, you will lose access to your account';
$lang_module['tstep_key'] = 'Access Key';
$lang_module['tstep_app'] = 'Authentication App';
$lang_module['tstep_app_note'] = 'Use an app or browser extension to generate a 6-digit authentication code';
$lang_module['backup_methods'] = 'Backup options';
$lang_module['rcode_note'] = 'You have created <strong>%s</strong> passkeys, you can use them as a two-step authentication method. You can also add other security keys here';
$lang_module['passkey'] = 'Passkey';
$lang_module['passkey_help'] = 'Passkeys are a secure and modern way to log in to your account using only your fingerprint, face, screen lock, PIN, or hardware security key. You can also use a passkey as a two-step authentication method after logging in with a password';
$lang_module['security_keys'] = 'Security keys';
$lang_module['security_keys_add'] = 'Add security key';
$lang_module['security_keys_note'] = 'Use fingerprint, PIN, face, screen lock, or hardware security key for two-step authentication';
$lang_module['recovery_codes_creat'] = 'Generate codes';
$lang_module['passkey_not_supported'] = 'This browser/device does not support WebAuthn, so a passkey cannot be created yet. Please use a different browser/device or try again later';
$lang_module['passkey_created_at'] = 'Created at';
$lang_module['passkey_last_used_at'] = 'Last used at';
$lang_module['passkey_seenthis'] = 'Created from this browser';
$lang_module['passkey_nickname'] = 'Friendly name';
$lang_module['passkey_nickname_edit'] = 'Change friendly name';
$lang_module['configured'] = 'Configured';
$lang_module['go_config'] = 'Go to';
$lang_module['number_keys'] = '%s keys';
$lang_module['remain_code'] = '%s codes remaining';
$lang_module['lack_code'] = 'You have used almost all backup codes, please generate new backup codes to ensure security';
$lang_module['usedup_code'] = 'You have used all backup codes, please generate new ones now!';
$lang_module['code_is_available'] = 'This code is available';
$lang_module['code_is_used'] = 'This code has been used';
$lang_module['qr_expried'] = 'Timeout, reload the page to generate a new code';
$lang_module['preferred_2fa_method'] = 'Preferred two-step authentication method';
$lang_module['preferred_2fa_method_help'] = 'Choose the two-step authentication method you want to use first after logging in with a password';
