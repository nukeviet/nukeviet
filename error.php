<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';

// Xac dinh kieu giao dien mac dinh
$global_config['current_theme_type'] = $nv_Request->get_string('nv' . NV_LANG_DATA . 'themever', 'cookie', '');
if (!in_array($global_config['current_theme_type'], $global_config['array_theme_type'], true)) {
    $global_config['current_theme_type'] = '';
    $nv_Request->set_Cookie('nv' . NV_LANG_DATA . 'themever', '', NV_LIVE_COOKIE_TIME);
}

// Xac dinh giao dien chung
$is_mobile = false;
$theme_type = '';
$_theme_mobile = $global_config['mobile_theme'];
if ((($client_info['is_mobile'] and (empty($global_config['current_theme_type']) or empty($global_config['switch_mobi_des']))) or ($global_config['current_theme_type'] == 'm' and !empty($global_config['switch_mobi_des']))) and !empty($_theme_mobile) and file_exists(NV_ROOTDIR . '/themes/' . $_theme_mobile . '/theme.php')) {
    $site_theme = $_theme_mobile;
    $is_mobile = true;
    $theme_type = 'm';
} else {
    if (empty($global_config['current_theme_type']) and ($client_info['is_mobile'] or empty($_theme_mobile))) {
        $global_config['current_theme_type'] = 'r';
    }

    $_theme = $global_config['site_theme'];
    if (!empty($_theme) and file_exists(NV_ROOTDIR . '/themes/' . $_theme . '/theme.php')) {
        $site_theme = $_theme;
        $theme_type = $global_config['current_theme_type'];
    } elseif (file_exists(NV_ROOTDIR . '/themes/default/theme.php')) {
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

// Doc file cau hinh giao dien
$cache_file = NV_LANG_DATA . '_' . $site_theme . '_configposition_' . NV_CACHE_PREFIX . '.cache';
if (($cache = $nv_Cache->getItem('themes', $cache_file)) != false) {
    $theme_config_positions = unserialize($cache);
} else {
    $_themeConfig = nv_object2array(simplexml_load_file(NV_ROOTDIR . '/themes/' . $site_theme . '/config.ini'));
    if (isset($_themeConfig['positions']['position']['name'])) {
        $theme_config_positions = [
            $_themeConfig['positions']['position']
        ];
    } elseif (isset($_themeConfig['positions']['position'])) {
        $theme_config_positions = $_themeConfig['positions']['position'];
    } else {
        $theme_config_positions = [];
        $_ini_file = file_get_contents(NV_ROOTDIR . '/themes/' . $site_theme . '/config.ini');
        if (preg_match_all('/<position>[\t\n\s]+<name>(.*?)<\/name>[\t\n\s]+<tag>(\[[a-zA-Z0-9_]+\])<\/tag>[\t\n\s]+<\/position>/s', $_ini_file, $_m)) {
            foreach ($_m[1] as $_key => $value) {
                $theme_config_positions[] = [
                    'name' => $value,
                    'tag' => $_m[2][$_key]
                ];
            }
        }
    }
    if (!empty($theme_config_positions)) {
        $nv_Cache->setItem('themes', $cache_file, serialize($theme_config_positions));
    }
}
require NV_ROOTDIR . '/themes/' . $site_theme . '/theme.php';

// Ket noi ngon ngu theo theme
if (file_exists(NV_ROOTDIR . '/themes/' . $site_theme . '/language/' . NV_LANG_INTERFACE . '.php')) {
    require NV_ROOTDIR . '/themes/' . $site_theme . '/language/' . NV_LANG_INTERFACE . '.php';
} elseif (file_exists(NV_ROOTDIR . '/themes/' . $site_theme . '/language/en.php')) {
    require NV_ROOTDIR . '/themes/' . $site_theme . '/language/en.php';
}

$error_code = $nv_Request->get_int('code', 'get', 404);

$title = isset($lang_global['error_' . $error_code . '_title']) ? $lang_global['error_' . $error_code . '_title'] : 'Error Code: ' . $error_code;

if (isset($lang_global['error_' . $error_code . '_content'])) {
    $content = $lang_global['error_' . $error_code . '_content'];
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

if (function_exists('nv_error_theme')) {
    nv_error_theme($title, $content, $error_code);
} else {
    nv_info_die($title, $title, $content, $error_code);
}
