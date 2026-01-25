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

use NukeViet\Template\Email\Cat;
use NukeViet\Template\Email\Tpl2Step;

$module_emails[Tpl2Step::ACTIVE_2STEP] = [
    'sys_pids' => '5',
    'is_system' => 1,
    'catid' => Cat::CAT_USER,
    't' => 'Notice to enable two-step authentication for member accounts',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />Your account at <a href="{$Home}"><strong>{$site_name}</strong></a> has just enabled Two-Factor Authentication. Information:<br /><br />- Time: <strong>{$time}</strong><br />- IP: <strong>{$ip}</strong><br />- Browser: <strong>{$browser}</strong><br /><br />If this is you, ignore this email. If this is not you, your account is most likely stolen. Please contact the site administrator for assistance'
];
$module_emails[Tpl2Step::DEACTIVATE_2STEP] = [
    'sys_pids' => '5',
    'is_system' => 1,
    'catid' => Cat::CAT_USER,
    't' => 'Notice to turn off two-step authentication for member accounts',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />Your account at <a href="{$Home}"><strong>{$site_name}</strong></a> has just disabled Two-Factor Authentication. Information:<br /><br />- Time: <strong>{$time}</strong><br />- IP: <strong>{$ip}</strong><br />- Browser: <strong>{$browser}</strong><br /><br />If this is you, ignore this email. If this is not you, please check your personal information at <a href="{$link}">{$link}</a>'
];
$module_emails[Tpl2Step::RENEW_BACKUPCODE] = [
    'sys_pids' => '5',
    'is_system' => 1,
    'catid' => Cat::CAT_USER,
    't' => 'Notice of regenerating two-step authentication backup codes for member accounts',
    's' => 'Privacy Notice',
    'c' => '{$greeting_user}<br /><br />Your account at <a href="{$Home}"><strong>{$site_name}</strong></a> has just recreated the backup code. Information:<br /><br />- Time: <strong>{$time}</strong><br />- IP: <strong>{$ip}</strong><br />- Browser: <strong>{$browser}</strong><br /><br />If this is you, ignore this email. If this is not you, please check your personal information at <a href="{$link}">{$link}</a>'
];
