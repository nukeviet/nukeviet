<?php

/**
 * NukeViet Content Management System
 * @version 5.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2025 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';
// Danh sách các file trong thư mục modules
$modulefilelist = get_module_filelist();
// Danh sách các file trong thư mục themes
$themefilelist = get_theme_filelist();

// IMG thong ke truy cap + online
if ($global_config['statistic'] and isset($sys_mods['statistics']) and $nv_Request->get_string('second', 'get') == 'statimg') {
    include_once NV_ROOTDIR . '/includes/core/statimg.php';
}

// Xuất ảnh QR-CODE
nv_apply_hook('', 'get_qr_code', [$nv_Request]);

// Google Sitemap
if ($nv_Request->isset_request(NV_NAME_VARIABLE, 'get') and $nv_Request->get_string(NV_NAME_VARIABLE, 'get') == 'SitemapIndex') {
    nv_xmlSitemapIndex_generate();
    exit();
}

// Check user
if (defined('NV_IS_USER')) {
    http_response_code(403);
    trigger_error('Hacking attempt', 256);
}
require NV_ROOTDIR . '/includes/core/is_user.php';

if (defined('NV_IS_USER')) {
    if (((int) $user_info['pass_reset_request'] == 1) or (!empty($global_config['pass_timeout']) && (((int) $user_info['pass_creation_time'] + (int) $global_config['pass_timeout']) < NV_CURRENTTIME))) {
        /*
        * Nếu buộc phải thay đổi mật khẩu hoặc thay đổi mật khẩu định kỳ
        * Thì chuyển hướng đến trang thay đổi mật khẩu
        */
        if (getPageUrl(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/password', true, false) === false and getPageUrl(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=logout', true, false) === false) {
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/password');
        }
    } elseif ($user_info['email_reset_request'] == 1) {
        // Nếu bắt buộc thay đổi email thì chuyển hướng đến trang thay đổi email
        if (getPageUrl(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/email', true, false) === false and getPageUrl(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=logout', true, false) === false) {
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=editinfo/email');
        }
    }
}

// Hook sector 5
nv_apply_hook('', 'sector5');

// Cap nhat trang thai online
if ($global_config['online_upd'] and !defined('NV_IS_AJAX') and !defined('NV_IS_MY_USER_AGENT')) {
    require NV_ROOTDIR . '/includes/core/online.php';
}

// Thong ke
if ($global_config['statistic'] and !defined('NV_IS_AJAX') and !defined('NV_IS_MY_USER_AGENT')) {
    if (!$nv_Request->isset_request(STATISTIC_COOKIE_NAME . NV_LANG_DATA, 'cookie')) {
        require NV_ROOTDIR . '/includes/core/stat.php';
    }
}

// Referer + Gqueries
if ($global_config['referer_blocker'] and $client_info['is_myreferer'] === 0 and !defined('NV_IS_MY_USER_AGENT')) {
    require NV_ROOTDIR . '/includes/core/referer.php';
}

// Xác định biến $module_name
if ($nv_Request->isset_request(NV_NAME_VARIABLE, 'post,get')) {
    $home = 0;
    $module_name = $nv_Request->get_string(NV_NAME_VARIABLE, 'post,get');

    // Ghi vào session yêu cầu xem trước giao diện người dùng
    if ($module_name == 'nv-preview-theme') {
        $theme = $nv_Request->get_title('theme', 'post,get', '');
        $checksum = $nv_Request->get_title('checksum', 'post,get', '');
        if (in_array($theme, $global_config['array_preview_theme'], true) and $checksum == md5(NV_LANG_DATA . $theme . $global_config['sitekey'])) {
            $nv_Request->set_Session('nv_preview_theme_' . NV_LANG_DATA, $theme);
        }
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
    }

    // Ghi vào cookie yêu cầu thay đổi giao diện người dùng
    if ($module_name == 'nv-choose-theme') {
        $theme = $nv_Request->get_title('theme', 'post,get', '');
        $tokend = $nv_Request->get_title('tokend', 'post,get', '');
        if ($tokend === NV_CHECK_SESSION) {
            if (in_array($theme, $global_config['array_user_allowed_theme'], true)) {
                $nv_Request->set_Cookie('nv_u_theme_' . NV_LANG_DATA, $theme, NV_LIVE_COOKIE_TIME);
            } else {
                $nv_Request->unset_request('nv_u_theme_' . NV_LANG_DATA, 'cookie');
            }
        }
        $nv_BotManager->setPrivate();
        nv_htmlOutput('OK');
    }

    if (empty($module_name)) {
        $module_name = $global_config['rewrite_op_mod'];
    }
} else {
    $home = 1;
    $module_name = $global_config['site_home_module'];
    $meta_property['og:title'] = $global_config['site_name'];
}

// Báo lỗi 404 khi giá trị của $module_name không hợp lệ
if (!preg_match($global_config['check_module'], $module_name)) {
    nv_error404();
}

// Xác định biến $op
$op = $nv_Request->get_string(NV_OP_VARIABLE, 'post,get') ?: 'main';

if (!empty($global_config['rewrite_op_mod']) and !isset($sys_mods[$module_name])) {
    $op = $module_name . ($op == 'main' ? '' : '/' . $op);
    $module_name = $global_config['rewrite_op_mod'];
}

// Danh sách module ngoài site
$site_mods = nv_site_mods();

// Phương án xử lý khi module không có trong danh sách module ngoài site
if (!isset($site_mods[$module_name])) {
    if (isset($sys_mods[$module_name])) {
        $groups_view = (string) $sys_mods[$module_name]['groups_view'];
        // Chuyển khách đến trang đăng nhập nếu module chỉ cấp cho thành viên
        if ($groups_view == '4' and !defined('NV_IS_USER')) {
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
        }

        // Báo lỗi khi module chỉ dành cho admin
        if (($groups_view == '1' or $groups_view == '2') and !defined('NV_IS_ADMIN')) {
            nv_info_die($nv_Lang->getGlobal('error_404_title'), $nv_Lang->getGlobal('site_info'), $nv_Lang->getGlobal('module_for_admin'), 404);
        }
    }

    // Báo lỗi 404 với các trường hợp khác
    nv_error404();
}

// Kiểm tra sự tồn tại của tệp tin main.php của module
$include_file = NV_ROOTDIR . '/modules/' . $site_mods[$module_name]['module_file'] . '/funcs/main.php';
if (!module_file_exists($include_file)) {
    if (isset($site_mods[$module_name]['funcs']['main'])) {
        $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET act=2 WHERE title= :title');
        $sth->bindParam(':title', $module_name, PDO::PARAM_STR);
        $sth->execute();

        nv_insert_notification('modules', 'auto_deactive_module', [
            'custom_title' => $site_mods[$module_name]['custom_title']
        ]);
        $nv_Cache->delMod('modules');
    }

    exit();
}

// Global variable for module
$module_info = $site_mods[$module_name];
$module_file = $module_info['module_file'];
$module_data = $module_info['module_data'];
$module_upload = $module_info['module_upload'];
$module_captcha = nv_module_captcha($module_name);

if (!preg_match('/^[a-z0-9\-\_\/\+]+$/i', $op)) {
    nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

// Xac dinh biến $array_op
$array_op = [];
if ($op != 'main' and !isset($module_info['funcs'][$op])) {
    $array_op = explode('/', $op);
    $op = (isset($module_info['funcs'][$array_op[0]])) ? $array_op[0] : 'main';
}

// Không cho truy cập trực tiếp vào /[lang]/[module-name]/sitemap/ chỉ truy cập vào /sitemap-[lang].[module-name].xml
if ($op == 'sitemap' and (empty($module_info['sitemap']) or !preg_match('/\.' . nv_preg_quote($module_name) . '[a-zA-Z0-9\-\.]*\.xml/', $nv_Request->request_uri))) {
    nv_error404();
}

// OpenSearch Link tag
$opensearch_link = [];
if (!empty($global_config['opensearch_link'])) {
    $opensearch_link = json_decode($global_config['opensearch_link'], true);
    if (json_last_error() !== JSON_ERROR_NONE) {
        $opensearch_link = [];
    }
}

// Xac dinh quyen dieu hanh module
if ($module_info['is_modadmin']) {
    define('NV_IS_MODADMIN', true);
}

if (defined('NV_IS_SPADMIN')) {
    if ($nv_Request->isset_request('drag_block', 'get')) {
        $nv_Request->set_Session('drag_block', $nv_Request->get_int('drag_block', 'get', 0));

        $nv_redirect = nv_get_redirect('get', true);
        nv_redirect_location($nv_redirect ?: NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    $drag_block = $nv_Request->get_int('drag_block', 'session', 0);
    if ($drag_block) {
        define('NV_IS_DRAG_BLOCK', true);
        $adm_int_lang = $nv_Request->get_string(INT_LANG_COOKIE_NAME, 'cookie');
        if ($adm_int_lang != NV_LANG_DATA) {
            $nv_Request->set_Cookie(INT_LANG_COOKIE_NAME, NV_LANG_DATA, NV_LIVE_COOKIE_TIME);
        }
    }
}

// Ket noi ngon ngu cua module
$nv_Lang->loadModule($module_file);

// Xác định lại các biến toàn cục liên quan đến giao diện
[$global_config['array_theme_type'], $global_config['mobile_theme'], $global_config['switch_mobi_des']] = fix_theme_configs($global_config);
$cookie_themetype = $nv_Request->get_string(CURRENT_THEMETYPE_COOKIE_NAME . NV_LANG_DATA, 'cookie', '');

// Xử lý yêu cầu thay đổi kiểu giao diện (r, d, m)
if ($nv_Request->isset_request('nv' . NV_LANG_DATA . 'themever', 'get')) {
    $nv_BotManager->setNoIndex()->printToHeaders();
    $themetype = $nv_Request->get_title('nv' . NV_LANG_DATA . 'themever', 'get', '', 1);
    if ($themetype != $cookie_themetype and in_array($themetype, $global_config['array_theme_type'], true)) {
        $nv_Request->set_Cookie(CURRENT_THEMETYPE_COOKIE_NAME . NV_LANG_DATA, $themetype, NV_LIVE_COOKIE_TIME);
    }

    $nv_redirect = nv_get_redirect('get', true);
    nv_redirect_location($nv_redirect ?: NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
}

// Xác định kiểu theme hiện tại, theme của module và theme của site
$is_mobile = false;
set_theme_configs($global_config, $is_mobile, $module_info);

// Xac dinh layout funcs cua module
$cache_file = md5($module_name . '_' . $global_config['module_theme']) . '_' . NV_CACHE_PREFIX . '.cache';
if (($cache = $nv_Cache->getItem('modules', $cache_file)) != false) {
    $module_info['layout_funcs'] = unserialize($cache);
} else {
    $module_info['layout_funcs'] = [];
    $sth = $db->prepare('SELECT f.func_name, t.layout FROM ' . NV_MODFUNCS_TABLE . ' f
                    INNER JOIN ' . NV_PREFIXLANG . '_modthemes t ON f.func_id=t.func_id
                    WHERE f.in_module = :module AND t.theme= :theme');
    $sth->bindParam(':module', $module_name, PDO::PARAM_STR);
    $sth->bindParam(':theme', $global_config['module_theme'], PDO::PARAM_STR);
    $sth->execute();
    while ($row = $sth->fetch()) {
        $module_info['layout_funcs'][$row['func_name']] = $row['layout'];
    }
    $sth->closeCursor();

    $cache = serialize($module_info['layout_funcs']);
    $nv_Cache->setItem('modules', $cache_file, $cache);
}

// Doc file cau hinh giao dien
$theme_config_positions = nv_get_blocks($global_config['module_theme']);

require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/theme.php';

// Ket noi ngon ngu theo theme
$nv_Lang->loadTheme($global_config['module_theme']);

// Xac dinh template module
$module_info['template'] = $global_config['module_theme'];
if (!theme_file_exists($global_config['module_theme'] . '/modules/' . $module_info['module_theme'])) {
    if (theme_file_exists('default/modules/' . $module_info['module_theme'])) {
        $module_info['template'] = 'default';
    }
}

// Ket noi voi file functions.php, file chua cac function dung chung
// cho ca module
if (module_file_exists($module_file . '/functions.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/functions.php';
}

// Xac dinh op file
$op_file = $module_info['funcs'][$op]['func_name'];

if (theme_file_exists($global_config['module_theme'] . '/modules/' . $module_info['module_theme'] . '/theme.php')) {
    require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_info['module_theme'] . '/theme.php';
} elseif (module_file_exists($module_file . '/theme.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/theme.php';
}

// Ket noi voi cac op cua module de thuc hien
if ($is_mobile and module_file_exists($module_file . '/mobile/' . $op_file . '.php')) {
    require NV_ROOTDIR . '/modules/' . $module_file . '/mobile/' . $op_file . '.php';
} else {
    require NV_ROOTDIR . '/modules/' . $module_file . '/funcs/' . $op_file . '.php';
}
exit();
