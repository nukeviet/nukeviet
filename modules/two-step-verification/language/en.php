<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MAINFILE')) {
    exit('Stop!!!');
}

$lang_translator['author'] = 'VINADES.,JSC <contact@vinades.vn>';
$lang_translator['createdate'] = '04/03/2010, 15:22';
$lang_translator['copyright'] = '@Copyright (C) 2009-2021 VINADES.,JSC. All rights reserved';
$lang_translator['info'] = '';
$lang_translator['langtype'] = 'lang_module';

$lang_module['confirm_password'] = 'Enter the password confirmation to continue';
$lang_module['confirm_password_info'] = 'To use this feature, you need to confirm your password, enter your password in the box below and click Confirm';
$lang_module['confirm'] = 'Confirm';
$lang_module['secretkey'] = 'Secret Key';
$lang_module['wrong_confirm'] = 'Confirmation code is incorrect, please re-enter';
$lang_module['cfg_step1'] = 'Step 1: Scan the QR code';
$lang_module['cfg_step1_manual'] = 'Scan QR-code above with Two-Factor Authentication software (for example Google Authenticator) on your phone. If you can not use the camera, please';
$lang_module['cfg_step1_manual1'] = 'enter this code';
$lang_module['cfg_step1_manual2'] = 'manual';
$lang_module['cfg_step1_note'] = 'Note secrecy code';
$lang_module['cfg_step2_info'] = 'After scanning the code or manually enter successful, the application will display a 6-digit string, that string entry below to confirm';
$lang_module['cfg_step2_info2'] = 'Verification 6-digit display on the screen of the app on your phone';
$lang_module['cfg_step2'] = 'Step 2: Enter the code from the app';
$lang_module['title_2step'] = 'Two-step authentication';
$lang_module['status_2step'] = 'Two-step authentication is';
$lang_module['active_2step'] = 'ON';
$lang_module['deactive_2step'] = 'OFF';
$lang_module['backupcode_2step'] = 'You have <strong>%d</strong> unused backup codes';
$lang_module['backupcode_2step_view'] = 'See backup codes';
$lang_module['backupcode_2step_note'] = 'Note: Save a backup code carefully to prevent the loss of the phone you can use this code to access the account. If you forget it and lose your phone you can not log into your account';
$lang_module['turnoff2step'] = 'Turn off two-step authentication';
$lang_module['turnon2step'] = 'Setting up two-step authentication';
$lang_module['creat_other_code'] = 'Create new backup codes';
$lang_module['email_subject'] = 'Privacy notice';
$lang_module['email_2step_on'] = 'Your <strong>%4$s</strong> account at <a href="%5$s"><strong>%6$s</strong></a> has just enabled Two-Factor Authentication. Information:<br /><br />- Time: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Browser: <strong>%3$s</strong><br /><br />If this is you, ignore this email. If this is not you, your account is most likely stolen. Please contact the site administrator for assistance';
$lang_module['email_2step_off'] = 'Your <strong>%5$s</strong> account at <a href="%6$s"><strong>%7$s</strong></a> has just disabled Two-Factor Authentication. Information:<br /><br />- Time: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Browser: <strong>%3$s</strong><br /><br />If this is you, ignore this email. If this is not you, please check your personal information at <a href="%4$s">%4$s</a>';
$lang_module['email_code_renew'] = 'Your <strong>%5$s</strong> account at <a href="%6$s"><strong>%7$s</strong></a> has just recreated the backup code. Information:<br /><br />- Time: <strong>%1$s</strong><br />- IP: <strong>%2$s</strong><br />- Browser: <strong>%3$s</strong><br /><br />If this is you, ignore this email. If this is not you, please check your personal information at <a href="%4$s">%4$s</a>';

$lang_module['change_2step_notvalid'] = 'Your account doesn\'t have a password, so Two-Step Authentication can\'t be changed. Please create a password and then return to this page. Please <a class="btn btn-primary btn-xs" href="%s">click here</a> to create a password';
