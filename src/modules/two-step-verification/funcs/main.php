<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_MOD_2STEP_VERIFICATION')) {
    exit('Stop!!!');
}

$page_title = $module_info['site_title'];
$key_words = $module_info['keywords'];
$page_url = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;

// Tự động chuyển đến trang thiết lập nếu hệ thống bắt buộc xác thực ở quản trị, hoặc tất cả các khu vực
if (empty($user_info['active2step']) and in_array((int) $global_config['two_step_verification'], [1, 3], true)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name . '&' . NV_OP_VARIABLE . '=setup');
}

$array_data = [];
// checkss khớp với modules/users/funcs/editinfo.php thay đổi cần cập nhật
$array_data['checkss'] = md5(NV_CHECK_SESSION . '_' . NV_BRIDGE_USER_MODULE . '_editinfo_' . $user_info['userid']);
$array_data['form_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . NV_BRIDGE_USER_MODULE . '&amp;' . NV_OP_VARIABLE . '=editinfo/passkey';
$array_data['page_url'] = $page_url;
$array_data['show_type'] = $nv_Request->get_title('type', 'get', '');
$array_data['publicKeys'] = [];
$array_data['login_keys'] = 0;
$array_data['security_keys'] = 0;

// Lấy danh sách khóa đăng nhập, khóa bảo mật
$sql = 'SELECT id, keyid, created_at, last_used_at, clid, enable_login, nickname
FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_passkey WHERE userid=' . $user_info['userid'];
$result = $db->query($sql);
while ($_row = $result->fetch()) {
    $array_data['publicKeys'][$_row['keyid']] = $_row;
    if (!empty($_row['enable_login'])) {
        $array_data['login_keys']++;
    } else {
        $array_data['security_keys']++;
    }
}
$result->closeCursor();

/*
 * Tắt xác thực hai bước
 * Lưu ý quan trọng: Chỉ tài khoản thành viên đã full xác thực mới có thể tắt!
 * Không cho phép tắt nếu tài khoản này mới chỉ login 1 bước
 */
if ($nv_Request->isset_request('turnoff2step', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    if (!defined('NV_IS_AJAX') or $tokend != NV_CHECK_SESSION or !defined('NV_IS_USER')) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Not allowed!'
        ]);
    }

    $sql = 'UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET
        active2step=0, secretkey=\'\', last_update=' . NV_CURRENTTIME . '
    WHERE userid=' . $user_info['userid'];
    $db->query($sql);

    // Xóa security keys
    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_passkey WHERE userid=' . $user_info['userid'] . ' AND enable_login=0';
    $db->query($sql);

    // Xóa mã dự phòng
    $sql = 'DELETE FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_backupcodes WHERE userid=' . $user_info['userid'];
    $db->query($sql);

    // Gửi email thông báo bảo mật
    $send_data = [[
        'to' => $user_info['email'],
        'data' => [
            'greeting_user' => greeting_for_user_create($user_info['username'], $user_info['first_name'], $user_info['last_name'], $user_info['gender']),
            'Home' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN),
            'time' => nv_datetime_format(NV_CURRENTTIME, 0, 0),
            'ip' => NV_CLIENT_IP,
            'browser' => NV_USER_AGENT,
            'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN)
        ]
    ]];
    nv_sendmail_template_async([$module_file, NukeViet\Template\Email\Tpl2Step::DEACTIVATE_2STEP], $send_data);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_deactive_2step', '', $user_info['userid']);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'OK'
    ]);
}

// Tạo lại mã dự phòng
if ($nv_Request->isset_request('changecode2step', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    if (!defined('NV_IS_AJAX') or $tokend != NV_CHECK_SESSION) {
        nv_jsonOutput([
            'status' => 'error',
            'mess' => 'Not allowed!'
        ]);
    }
    nv_creat_backupcodes();

    // Gửi email thông báo bảo mật
    $send_data = [[
        'to' => $user_info['email'],
        'data' => [
            'greeting_user' => greeting_for_user_create($user_info['username'], $user_info['first_name'], $user_info['last_name'], $user_info['gender']),
            'Home' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, NV_MY_DOMAIN),
            'time' => nv_datetime_format(NV_CURRENTTIME, 0, 0),
            'ip' => NV_CLIENT_IP,
            'browser' => NV_USER_AGENT,
            'link' => urlRewriteWithDomain(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, NV_MY_DOMAIN)
        ]
    ]];
    nv_sendmail_template_async([$module_file, NukeViet\Template\Email\Tpl2Step::RENEW_BACKUPCODE], $send_data);
    nv_insert_logs(NV_LANG_DATA, $module_name, 'log_renew_backupcode', '', $user_info['userid']);
    nv_jsonOutput([
        'status' => 'ok',
        'mess' => 'OK'
    ]);
}

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_backupcodes WHERE userid=' . $user_info['userid'];
$array_data['backupcodes'] = $db->query($sql)->fetchAll();

$array_data['print_code_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=print';
$array_data['download_code_url'] = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;downloadcode=' . md5('downloadcode' . NV_CHECK_SESSION);
$array_data['text_codes'] = [];
foreach ($array_data['backupcodes'] as $code) {
    if (!empty($code['is_used'])) {
        continue;
    }
    $array_data['text_codes'][] = $code['code'];
}
$array_data['text_codes'] = implode("\n", $array_data['text_codes']);

// Tải xuống code
if ($nv_Request->isset_request('downloadcode', 'get') and $nv_Request->get_title('downloadcode', 'get', '') == md5('downloadcode' . NV_CHECK_SESSION)) {
    $filename = change_alias(NV_SERVER_NAME) . '-recovery-codes.txt';
    $data = '';

    foreach ($array_data['backupcodes'] as $code) {
        if (!empty($code['is_used'])) {
            continue;
        }
        $data .= $code['code'] . "\n";
    }

    header('Pragma: public');
    header('Expires: 0');
    header('Cache-Control:');
    header('Cache-Control: public');
    header('Content-Description: File Transfer');
    header('Content-Type: text/plain; charset=UTF-8');
    header('Content-Disposition: attachment; filename="' . $filename . '";');
    header('Last-Modified: ' . date('D, d M Y H:i:s \G\M\T', NV_CURRENTTIME));
    header('Content-Length: ' . strlen($data));

    echo $data;
    exit();
}

// In code ra
if ($array_op[0] ?? '' == 'print') {
    $page_url .= '&amp;' . NV_OP_VARIABLE . '=print';
    $canonicalUrl = getCanonicalUrl($page_url, true, true);

    $contents = nv_theme_print_code($array_data['backupcodes']);
    include NV_ROOTDIR . '/includes/header.php';
    echo nv_site_theme($contents, false);
    include NV_ROOTDIR . '/includes/footer.php';
}

// Sửa App
if ($array_data['show_type'] == 'app') {
    $array_data['secretkey'] = strtolower(nv_get_secretkey());
} elseif ($nv_Request->isset_request($module_data . '_secretkey', 'session')) {
    $nv_Request->unset_request($module_data . '_secretkey', 'session');
}

$canonicalUrl = getCanonicalUrl($page_url);

$contents = nv_theme_info_2step($array_data);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
