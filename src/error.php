<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2017 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate Jul 2, 2017 2:06:56 PM
 */

define('NV_SYSTEM', true);

// Thư mục gốc của site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __file__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';

// Xac dinh kieu giao dien mac dinh
$global_config['current_theme_type'] = $nv_Request->get_string('nv' . NV_LANG_DATA . 'themever', 'cookie', '');
if (!in_array($global_config['current_theme_type'], $global_config['array_theme_type'])) {
    $global_config['current_theme_type'] = '';
    $nv_Request->set_Cookie('nv' . NV_LANG_DATA . 'themever', '', NV_LIVE_COOKIE_TIME);
}

// Xac dinh giao dien chung
$is_mobile = false;
$theme_type = '';
$_theme_mobile = $global_config['mobile_theme'];
if ((($client_info['is_mobile'] and (empty($global_config['current_theme_type']) or empty($global_config['switch_mobi_des']))) or ($global_config['current_theme_type'] == 'm' and !empty($global_config['switch_mobi_des']))) and !empty($_theme_mobile) and file_exists(NV_ROOTDIR . '/themes/' . $_theme_mobile . '/theme_error.php')) {
    $site_theme = $_theme_mobile;
    $is_mobile = true;
    $theme_type = 'm';
} else {
    if (empty($global_config['current_theme_type']) and ($client_info['is_mobile'] or empty($_theme_mobile))) {
        $global_config['current_theme_type'] = 'r';
    }

    $_theme = $global_config['site_theme'];
    if (!empty($_theme) and file_exists(NV_ROOTDIR . '/themes/' . $_theme . '/theme_error.php')) {
        $site_theme = $_theme;
        $theme_type = $global_config['current_theme_type'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/default/theme_error.php')) {
        $site_theme = 'default';
        $theme_type = $global_config['current_theme_type'];
    } else {
        trigger_error('Error! Does not exist themes default', 256);
    }
}

// Xac lap lai giao kieu giao dien hien tai
if ($theme_type != $global_config['current_theme_type']) {
    $global_config['current_theme_type'] = $theme_type;
    $nv_Request->set_Cookie('nv' . NV_LANG_DATA . 'themever', $theme_type, NV_LIVE_COOKIE_TIME);
}
unset($theme_type);

$global_config['module_theme'] = $site_theme;

// Ket noi ngon ngu theo theme
$nv_Lang->loadTheme($site_theme);

$error_code = $nv_Request->get_int('code', 'get', 404);

$title = $nv_Lang->existsGlobal('error_' . $error_code . '_title') ? $nv_Lang->getGlobal('error_' . $error_code . '_title') : 'Error Code: ' . $error_code;

if ($nv_Lang->existsGlobal('error_' . $error_code . '_content')) {
    $content = $nv_Lang->getGlobal('error_' . $error_code . '_content');
} else {
    switch ($error_code) {
        case 400:
            $content = 'Bad Request';
            break;
        case 403:
            $content = 'Forbidden';
            break;
        case 404:
            $content = 'Not Found';
            break;
        case 405:
            $content = 'Method Not Allowed';
            break;
        case 408:
            $content = 'Request Time-out';
            break;
        case 500:
            $content = 'Internal Server Error';
            break;
        case 502:
            $content = 'Bad Gateway';
            break;
        case 503:
            $content = 'Service Temporarily Unavailable';
            break;
        case 504:
            $content = 'Gateway Time-out';
            break;
        default:
            $content = 'Error code: ' . $error_code;
            break;
    }
}

nv_info_die($title, $title, $content, $error_code);
