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
    't' => 'Thông báo bật xác thực hai bước cho tài khoản thành viên',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website <a href="{$Home}"><strong>{$site_name}</strong></a> vừa kích hoạt chức năng xác thực hai bước qua ứng dụng. Thông tin:<br /><br />- Thời gian: <strong>{$time}</strong><br />- IP: <strong>{$ip}</strong><br />- Trình duyệt: <strong>{$browser}</strong><br /><br />Nếu đây đúng là bạn, hãy bỏ qua email này. Nếu đây không phải là bạn, rất có thể tài khoản của bạn đã bị đánh cắp. Hãy liên hệ với quản trị site để được hỗ trợ'
];
$module_emails[Tpl2Step::DEACTIVATE_2STEP] = [
    'sys_pids' => '5',
    'is_system' => 1,
    'catid' => Cat::CAT_USER,
    't' => 'Thông báo tắt xác thực hai bước cho tài khoản thành viên',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website <a href="{$Home}"><strong>{$site_name}</strong></a> vừa tắt chức năng xác thực hai bước qua ứng dụng. Thông tin:<br /><br />- Thời gian: <strong>{$time}</strong><br />- IP: <strong>{$ip}</strong><br />- Trình duyệt: <strong>{$browser}</strong><br /><br />Nếu đây đúng là bạn, hãy bỏ qua email này. Nếu đây không phải là bạn, mời kiểm tra lại thông tin cá nhân tại <a href="{$link}">{$link}</a>'
];
$module_emails[Tpl2Step::RENEW_BACKUPCODE] = [
    'sys_pids' => '5',
    'is_system' => 1,
    'catid' => Cat::CAT_USER,
    't' => 'Thông báo tạo lại mã dự phòng xác thực hai bước cho tài khoản thành viên',
    's' => 'Thông báo bảo mật',
    'c' => '{$greeting_user}<br /><br />Tài khoản của bạn tại website <a href="{$Home}"><strong>{$site_name}</strong></a> vừa tạo lại mã dự phòng. Thông tin:<br /><br />- Thời gian: <strong>{$time}</strong><br />- IP: <strong>{$ip}</strong><br />- Trình duyệt: <strong>{$browser}</strong><br /><br />Nếu đây đúng là bạn, hãy bỏ qua email này. Nếu đây không phải là bạn, mời kiểm tra lại thông tin cá nhân tại <a href="{$link}">{$link}</a>'
];
