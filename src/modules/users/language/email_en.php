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

use NukeViet\Module\users\Shared\Emails;
use NukeViet\Template\Email\Cat;

if ($module_name == 'users') {
    $catid = Cat::CAT_USER;
    $pids = '3';
    $is_system = 1;
    $pfile = '';
} else {
    $catid = Cat::CAT_MODULE;
    $pids = '';
    $is_system = 0;
    $pfile = 'emf_code_user.php';
}

$module_emails[Emails::REGISTER_ACTIVE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Account activation via email',
    's' => 'Activate information',
    'c' => '{$greeting_user}<br /><br />Your account at website {$site_name} waitting to activate. To activate, please click link follow:<br /><br />URL: <a href="{$link}">{$link}</a><br /><br />Account information:<br /><br />Username: {$username}<br />Email: {$email}<br /><br />Activate expired on {$active_deadline}<br /><br />This is email automatic sending from website {$site_name}.'
];
$module_emails[Emails::USER_DELETE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email notification to delete account',
    's' => 'Email notification to delete account',
    'c' => '{$greeting_user}<br /><br />We are so sorry to delete your account at website {$site_name}.'
];
$module_emails[Emails::NEW_2STEP_CODE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Send new backup code',
    's' => 'New backup code',
    'c' => '{$greeting_user}<br /><br /> backup code to your account at the website {$site_name} has been changed. Here is a new backup code: <br /><br />{foreach from=$new_code item=code}{$code}<br />{/foreach}<br />You keep your backup safe. If you lose your phone and lose your backup code, you will no longer be able to access your account.<br /><br />This is an automated message sent to your e-mail from website {$site_name}. If you do not understand the content of this letter, simply delete it.'
];
$module_emails[Emails::NEW_INFO] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification that the account has been created when the member successfully registers in the form',
    's' => 'Your account was created',
    'c' => '{$greeting_user}<br /><br />Your account at website {$site_name} activated. Your login information:<br /><br />Username: {$username}<br />Email: {$email}<br /><br />Please click the link below to log in:<br />URL: <a href="{$link}">{$link}</a><br /><br />This is email automatic sending from website {$site_name}.'
];
$module_emails[Emails::NEW_INFO_OAUTH] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification that the account has been created when the member successfully registers via Oauth',
    's' => 'Your account was created',
    'c' => '{$greeting_user}<br /><br />Your account at website {$site_name} activated. To log into your account please visit the page: <a href="{$link}">{$link}</a> and press the button: Sign in with {$oauth_name}.<br /><br />This is email automatic sending from website {$site_name}.'
];
$module_emails[Emails::ADDED_BY_LEADER] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification of account created by group leader',
    's' => 'Your account was created',
    'c' => '{$greeting_user}<br /><br />Your account at website {$site_name} activated. Your login information:<br /><br />URL: <a href="{$link}">{$link}</a><br />Username: {$username}<br />Email: {$email}<br /><br />This is email automatic sending from website {$site_name}.'
];
$module_emails[Emails::ADDED_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification of account created by administrator',
    's' => 'Your account was created',
    'c' => '{$greeting_user}<br /><br />Your account at website {$site_name} has been created. Here are the logins:<br /><br />URL: <a href="{$link}">{$link}</a><br />Username: {$username}<br />Password: {$password}<br />{if $pass_reset gt 0 or $email_reset gt 0}<br />Note:<br />{if $pass_reset eq 2}- We recommend that you change your password before using the account.<br />{elseif $pass_reset eq 1}- You need to change your password before using the account.<br />{/if}{if $email_reset eq 2}- We recommend that you change your email before using the account.<br />{elseif $email_reset eq 1}- You need to change your email before using the account.<br />{/if}{/if}<br />This is an automated message sent to Your email box from website {$site_name}. If you do not understand the content of this letter, simply delete it.'
];
$module_emails[Emails::SAFE_KEY] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Send verification code when user turns on/ off safe mode',
    's' => 'Safe mode verification code',
    'c' => '{$greeting_user}<br /><br />You sent a request using safe mode in website {$site_name}. Below is a verifykey  for activating or off safe mode:<br /><br /><strong>{$code}</strong><br /><br />This verifykey only works on-off safe mode once only. After you turn off safe mode, this verification code will be worthless.<br /><br />These are automatic messages sent to your e-mail inbox from website {$site_name}.'
];
$module_emails[Emails::SELF_EDIT] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notify account changes just made by the user',
    's' => 'Update account infomation success',
    'c' => '{$greeting_user}<br /><br />Your account on the website {$site_name} has been updated {if $send_newvalue}with new {$label}: <strong>{$newvalue}</strong>{else}new {$label}{/if}.<br /><br />These are automatic messages sent to your e-mail inbox from website {$site_name}.'
];
$module_emails[Emails::EDIT_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notify account changes just made by the administrator',
    's' => 'Your account has been updated',
    'c' => '{$greeting_user}<br /><br />Your account on the website {$site_name} has been updated. Below are your login details:<br /><br />URL: <a href="{$link}">{$link}</a><br />Alias: {$username}<br />Email: {$email}{if not empty($password)}<br />Password: {$password}{/if}<br />{if $pass_reset gt 0 or $email_reset gt 0}<br />Notice:<br />{if $pass_reset eq 2}- We recommend that you change your password before using the account.<br />{elseif $pass_reset eq 1}- You are required to change your password before using the account.<br />{/if}{if $email_reset eq 2}- We recommend that you change your email before using the account.<br />{elseif $email_reset eq 1}- You are required to change your email before using the account.<br />{/if}{/if}<br />This is an automated email sent to your inbox from the website {$site_name}. If you do not understand the contents of this email, simply delete it.'
];
$module_emails[Emails::VERIFY_EMAIL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Confirmation email to change account email',
    's' => 'Activation information for changing email',
    'c' => '{$greeting_user}<br /><br />You sent a request to change the email address of the personal Account on the website {$site_name}. To complete this change, you must confirm your new email address by entering the verifykey below in the appropriate fields in the area Edit Account Information:<br /><br />Verifykey: <strong>{$code}</strong><br /><br />This key expires on {$deadline}.<br /><br />These are automatic messages sent to your e-mail inbox from website {$site_name}. If you do not understand anything about the contents of this letter, simply delete it.'
];
$module_emails[Emails::GROUP_JOIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notice asking to join the group',
    's' => 'Request to join group',
    'c' => 'Hello leader <strong>{$group_name}</strong>,<br /><br /><strong>{$full_name}</strong> has sent the request to join the group <strong>{$group_name}</strong> you are managing. You need to approve this request!<br /><br />Please <a href="{$link}"> visit this link </a> to approve membership.'
];
$module_emails[Emails::LOST_ACTIVE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Resend account activation information',
    's' => 'Activate account',
    'c' => '{$greeting_user}<br /><br />Your account at website {$site_name} waitting to activate. To activate, please click link follow:<br /><br />URL: <a href="{$link}">{$link}</a><br />Account information:<br />Account: {$username}<br />Email: {$email}<br />Password: {$password}<br /><br />Activate expired on {$active_deadline}<br /><br />This is email automatic sending from website {$site_name}.'
];
$module_emails[Emails::LOST_PASS] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Instructions for retrieving member password',
    's' => 'Guide password recovery',
    'c' => '{$greeting_user}<br /><br />You propose to change my login password at the website {$site_name}. To change your password, you will need to enter the verification code below in the corresponding box at the password change area.<br /><br />Verification code: <strong>{$code}</strong><br /><br />This code is only used once and before the deadline of {$deadline}.<br />More information about this request:<br />- IP: <strong>{$ip}</strong><br />- Browser: <strong>{$user_agent}</strong><br />- Time: <strong>{$request_time}</strong><br /><br />This letter is automatically sent to your email inbox from site {$site_name}. If you do not know anything about the contents of this letter, just delete it.'
];
$module_emails[Emails::R2S] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notice that two-step authentication has been successfully removed',
    's' => '2-Step Verification is turned off',
    'c' => '{$greeting_user}<br /><br />At your request, we have turned off 2-Step Verification for your account at the {$site_name} website.<br /><br />This is email automatic sending from website {$site_name}.'
];
$module_emails[Emails::R2S_REQUEST] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Instructions for turning off two-step authentication when forgetting code',
    's' => 'Information about turning off 2-step verification',
    'c' => '{$greeting_user}<br /><br />We have received a request to turn off 2-step verification for your account at the {$site_name} website. If you sent this request yourself, please use the Verification Code below to proceed:<br /><br />Verification Code: <strong>{$code}</strong><br /><br />This is email automatic sending from website {$site_name}.'
];
$module_emails[Emails::OAUTH_LEADER_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification oauth is added to the account by the team leader',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />We are informing you that a third party account <strong>{$oauth_name}</strong> has just been connected to your <strong>{$username}</strong> account by the group leader.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Third-party accounts Management</a>'
];
$module_emails[Emails::OAUTH_SELF_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification oauth is added to the account by the user themselves',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />The third party account <strong>{$oauth_name}</strong> has just been connected to your <strong>{$username}</strong> account. If this was not your intention, please quickly remove it from your account by visiting the third party account management area.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Third-party accounts Management</a>'
];
$module_emails[Emails::OAUTH_LEADER_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification oauth is removed to the account by the team leader',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />We are informing you that the third party account <strong>{$oauth_name}</strong> has just been disconnected from your <strong>{$username}</strong> account by the group leader.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Third-party accounts Management</a>'
];
$module_emails[Emails::OAUTH_SELF_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification oauth is removed to the account by the user themselves',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />The third-party account <strong>{$oauth_name}</strong> has just been disconnected from your <strong>{$username}</strong> account. If this is not your intention, please quickly contact the site administrator for help.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Third-party accounts Management</a>'
];
$module_emails[Emails::OAUTH_VERIFY_EMAIL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Send email verification code when logging in via Oauth and the email matches your existing account',
    's' => 'New e-mail verification',
    'c' => 'Hello!<br /><br />You have sent a request to verify your email address: {$email}. Copy the code below and paste it into the Verification code box on the site.<br /><br />Verification code: <strong>{$code}</strong><br /><br />This is email automatic sending from website {$site_name}.'
];
$module_emails[Emails::ACTIVE_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email notifies users when the administrator activates the account',
    's' => 'Your account has been created',
    'c' => '{$greeting_user}<br /><br />Your account at website {$site_name} activated. {if empty($oauth_name)}Your login information:<br /><br />URL: <a href="{$link}">{$link}</a><br />Username: {$username}<br />{if not empty($password)}Password: {$password}{/if}{else}To log into your account please visit the page: <a href="{$link}">{$link}</a> and press the button: <strong>Sign in with {$oauth_name}</strong>.{if not empty($password)}<br /><br />You can also log in using the usual method with the following information:<br />Username: {$username}<br />Password: {$password}{/if}{/if}{if $pass_reset gt 0 or $email_reset gt 0}<br />Note:<br />{if $pass_reset eq 2}- We recommend that you change your password before using the account.<br />{elseif $pass_reset eq 1}- You need to change your password before using the account.<br />{/if}{if $email_reset eq 2}- We recommend that you change your email before using the account.<br />{elseif $email_reset eq 1}- You need to change your email before using the account.<br />{/if}{/if}<br />This is email automatic sending from website {$site_name}.'
];
$module_emails[Emails::REQUEST_RESET_PASS] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email asking user to change password',
    's' => '{if $pass_reset eq 2}Recommended account password change{else}Need to change account password{/if}',
    'c' => '{$greeting_user}<br /><br />The {$site_name} website administration informs: For security reasons, {if $pass_reset eq 2}we recommend that you{else}you need to{/if} change your account password as soon as possible. To change your password, you need to first visit the <a href="{$link}">Manage Personal Account page</a>, select the Account Settings button, then the Password button, and follow the instructions.'
];
$module_emails[Emails::OFF2S_BY_ADMIN] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notification to users that two-step authentication has been turned off by the administrator',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />Your account has just had two-step authentication disabled by your administrator. We send you this email to inform you.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Manage two-step authentication</a>'
];
$module_emails[Emails::OAUTH_ADMIN_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notify users when administrators delete their third-party account',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />We are informing you that the third party account <strong>{$oauth_name}</strong> has just been disconnected from your account by an administrator. We send you this email to inform you.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Third-party accounts Management</a>'
];
$module_emails[Emails::OAUTH_TRUNCATE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Notify users when administrators delete all of their third-party accounts',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />We inform you that all third party accounts have been disconnected from your account by an administrator.<br /><br /><a href="{$link}" style="font-family:Roboto,RobotoDraft,Helvetica,Arial,sans-serif;line-height:16px;color:#ffffff;font-weight:400;text-decoration:none;font-size:14px;display:inline-block;padding:10px 24px;background-color:#4184f3;border-radius:5px;min-width:90px">Third-party accounts Management</a>'
];
$module_emails[Emails::REQUEST_RESET_EMAIL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email requesting user to change email',
    's' => '{if $email_reset eq 2}Recommended email change{else}Need to change email{/if}',
    'c' => '{$greeting_user}<br /><br />The {$site_name} website administration informs: For security reasons, {if $email_reset eq 2}we recommend that you{else}you need to{/if} change your account email as soon as possible. To change your email, you need to first visit the <a href="{$link}">Manage Personal Account page</a>, select the Account Settings button, then the Email button, and follow the instructions.'
];
$module_emails[Emails::SECURITY_KEY_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email adding security key',
    's' => 'A security key has been added to your account',
    'c' => '{$greeting_user}<br /><br />A security key named &quot;{$security_key}&quot; has just been added to your account on the website {$site_name}. This action originated from:
<ul>
    <li>Browser: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Action time: <strong>{$action_time}</strong></li>
</ul>
<p>We send this mandatory notification to your email to ensure that it was you who performed the action. If it was not you, please urgently visit the <a href="{$tstep_link}">two-step authentication management page</a> to review the security keys. Also, <a href="{$pass_link}">change your password immediately</a> to ensure safety.</p>
Reminder: Have you stored your backup codes? If not, please take a moment to download and store them carefully, as they are the last resort to ensure you can access your account in case you lose the devices used for two-step authentication. You can <a href="{$code_link}">download them here</a>.'
];
$module_emails[Emails::PASSKEY_ADD] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email adding passkey',
    's' => 'A passkey has been added to your account',
    'c' => '{$greeting_user}<br /><br />A passkey named &quot;{$passkey}&quot; has just been added to your account on the website {$site_name}. This action originated from:
<ul>
    <li>Browser: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Action time: <strong>{$action_time}</strong></li>
</ul>
We send this mandatory notification to your email to ensure that it was you who performed the action. If it was not you, please urgently visit the <a href="{$passkey_link}">passkey management page</a> to review the passkeys. Also, <a href="{$pass_link}">change your password immediately</a> to ensure safety.'
];
$module_emails[Emails::SECURITY_KEY_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email deleting security key',
    's' => 'Security key has been removed from your account',
    'c' => '{$greeting_user}<br /><br />The security key named &quot;{$security_key}&quot; has just been removed from your account on the website {$site_name}. This action originated from:
<ul>
    <li>Browser: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Action time: <strong>{$action_time}</strong></li>
</ul>
<p>We send this mandatory notification to your email to ensure that it was you who performed the action. If it was not you, please urgently visit the <a href="{$tstep_link}">two-step authentication management page</a> to review the security keys. Also, <a href="{$pass_link}">change your password immediately</a> to ensure safety.</p>
Reminder: Have you stored your backup codes? If not, please take a moment to download and store them carefully, as they are the last resort to ensure you can access your account in case you lose the devices used for two-step authentication. You can <a href="{$code_link}">download them here</a>.'
];
$module_emails[Emails::PASSKEY_DEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Email deleting passkey',
    's' => 'Passkey has been removed from your account',
    'c' => '{$greeting_user}<br /><br />The passkey named &quot;{$passkey}&quot; has just been removed from your account on the website {$site_name}. This action originated from:
<ul>
    <li>Browser: <strong>{$user_agent}</strong></li>
    <li>IP: <strong>{$ip}</strong></li>
    <li>Action time: <strong>{$action_time}</strong></li>
</ul>
We send this mandatory notification to your email to ensure that it was you who performed the action. If it was not you, please urgently visit the <a href="{$passkey_link}">passkey management page</a> to review the passkeys. Also, <a href="{$pass_link}">change your password immediately</a> to ensure safety.'
];
$module_emails[Emails::DELETE_ACCOUNT_SECCODE] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Account deletion confirmation code email',
    's' => 'Confirmation code for your account deletion request',
    'c' => '<p>{$greeting_user}</p> <p>We have received your request to delete your account. To proceed, please use the confirmation code below.</p> <div style="text-align: center; margin: 32px 0;"> <p style="font-size: 14px; color: #4b5563; margin-bottom: 8px;">Your confirmation code is:</p> <div style="display: inline-block; background-color: #f3f4f6; border: 1px solid #d1d5db; border-radius: 6px; padding: 12px 24px;"> <span style="font-size: 24px; font-weight: bold; letter-spacing: 0.2em; color: #111827; font-family: monospace;">{$code}</span> </div> </div> <p>This code is valid for 10 minutes. Please do not share this code with anyone.</p> <p>If you did not request this action, please ignore this email and contact our support team immediately to protect your account.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> This is a mandatory notification related to activity on your account. Please do not reply and ignore this email if you do not understand the content. </p>'
];
$module_emails[Emails::DELETE_ACCOUNT_PENDING] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Account scheduled for deletion notification email',
    's' => 'Your account has been scheduled for deletion',
    'c' => '<p>{$greeting_user}</p> <p>Your account deletion request has been confirmed. Your account will be permanently deleted around <strong style="color: #111827;">{$action_time}</strong>.</p> <p>After deletion, all data and related services will be irreversible.</p> <div style="text-align: center; margin: 32px 0;"> <p style="color: #4b5563; margin-bottom: 16px;">If you change your mind, you can cancel this request at any time before the deadline above by clicking the button below and logging in again.</p> <a href="{$link}" style="display: inline-block; padding: 12px 32px; font-weight: bold; color: white; background-color: #2563eb; text-decoration: none; border-radius: 6px;">Cancel account deletion request</a> </div> <p>If you did not make this request, please click the link above to cancel the request or contact our support team immediately.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> This is a mandatory notification related to activity on your account. Please do not reply and ignore this email if you do not understand the content. </p>'
];
$module_emails[Emails::DELETE_ACCOUNT_CANCEL] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Account deletion request cancellation email',
    's' => 'Your account deletion request has been cancelled',
    'c' => '<p>{$greeting_user}</p> <p>We confirm that your account deletion request has been successfully cancelled as per your request.</p> <p>Your account remains <strong>safe</strong> and operates normally. You can continue to use all our services without interruption.</p> <p>If you were not the one who performed this action, please <a href="{$pass_link}" style="color: #2563eb; font-weight: 600; text-decoration: none;">change your password</a> immediately and review <a href="{$link}" style="color: #2563eb; font-weight: 600; text-decoration: none;">recent login sessions</a> to protect your account.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> This is a mandatory notification related to activity on your account. Please do not reply and ignore this email if you do not understand the content. </p>'
];
$module_emails[Emails::DELETE_ACCOUNT_COMPLETED] = [
    'is_system' => $is_system,
    'pids' => $pids,
    'pfile' => $pfile,
    'catid' => $catid,
    't' => 'Account successfully deleted email',
    's' => 'Your account has been permanently deleted',
    'c' => '<p>{$greeting_user}</p> <p>As requested, your account has been <strong>permanently deleted</strong> from our system. This action cannot be undone.</p> <p>All personal data, activity history, and services related to this account have been irreversibly deleted.</p> <p>{$newvalue}.</p> <p>We are sorry to part ways with you and hope to have the opportunity to serve you in the future.</p> <p style="margin-top: 30px; padding-top: 20px; border-top: 1px solid #e5e7eb; font-size: 14px; color: #6b7280; font-style: italic; margin-bottom: 0px;"> This is a mandatory notification related to activity on your account. Please do not reply and ignore this email if you do not understand the content. </p>'
];
