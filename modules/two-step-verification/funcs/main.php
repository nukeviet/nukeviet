<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
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

/*
 * Tắt xác thực hai bước
 * Lưu ý quan trọng: Chỉ tài khoản thành viên đã full xác thực mới có thể tắt!
 * Không cho phép tắt nếu tài khoản này mới chỉ login 1 bước
 */
if ($nv_Request->isset_request('turnoff2step', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    if (!defined('NV_IS_AJAX') or $tokend != NV_CHECK_SESSION or !defined('NV_IS_USER')) {
        nv_htmlOutput('Wrong URL');
    }
    $db->query('UPDATE ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . ' SET active2step=0, secretkey=\'\' WHERE userid=' . $user_info['userid']);

    // Gửi email thông báo bảo mật
    $m_time = nv_date('H:i:s d/m/Y', NV_CURRENTTIME);
    $m_link = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true);
    $m_link_manager = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true);
    $message = sprintf($lang_module['email_2step_off'], $m_time, NV_CLIENT_IP, NV_USER_AGENT, $m_link_manager, $user_info['username'], $m_link, $global_config['site_name']);
    nv_sendmail('', $user_info['email'], $lang_module['email_subject'], $message);

    nv_htmlOutput('OK');
}

// Tạo lại mã dự phòng
if ($nv_Request->isset_request('changecode2step', 'post')) {
    $tokend = $nv_Request->get_title('tokend', 'post', '');
    if (!defined('NV_IS_AJAX') or $tokend != NV_CHECK_SESSION) {
        nv_htmlOutput('Wrong URL');
    }
    nv_creat_backupcodes();
    $nv_Request->set_Session('showcode_' . $module_data, 1);

    // Gửi email thông báo bảo mật
    $m_time = nv_date('H:i:s d/m/Y', NV_CURRENTTIME);
    $m_link = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA, true);
    $m_link_manager = NV_MY_DOMAIN . nv_url_rewrite(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name, true);
    $message = sprintf($lang_module['email_code_renew'], $m_time, NV_CLIENT_IP, NV_USER_AGENT, $m_link_manager, $user_info['username'], $m_link, $global_config['site_name']);
    nv_sendmail('', $user_info['email'], $lang_module['email_subject'], $message);

    nv_htmlOutput('OK');
}

$sql = 'SELECT * FROM ' . $db_config['prefix'] . '_' . $site_mods[NV_BRIDGE_USER_MODULE]['module_data'] . '_backupcodes WHERE userid=' . $user_info['userid'];
$backupcodes = $db->query($sql)->fetchAll();

$autoshowcode = false;
if ($nv_Request->isset_request('showcode_' . $module_data, 'session')) {
    $autoshowcode = true;
    $nv_Request->unset_request('showcode_' . $module_data, 'session');
}

$canonicalUrl = getCanonicalUrl($page_url, true, true);

$contents = nv_theme_info_2step($backupcodes, $autoshowcode);

include NV_ROOTDIR . '/includes/header.php';
echo nv_site_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
