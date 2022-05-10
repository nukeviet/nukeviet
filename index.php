<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (isset($_GET['response_headers_detect'])) {
    if ((isset($_SERVER['HTTPS']) and (strtolower($_SERVER['HTTPS']) == 'on' or $_SERVER['HTTPS'] == '1')) or $_SERVER['SERVER_PORT'] == 443) {
        header('x-is-https: 1');
    } else {
        header('x-is-http: 1');
    }
    exit(0);
}

define('NV_SYSTEM', true);

// Xac dinh thu muc goc cua site
define('NV_ROOTDIR', pathinfo(str_replace(DIRECTORY_SEPARATOR, '/', __FILE__), PATHINFO_DIRNAME));

require NV_ROOTDIR . '/includes/mainfile.php';
require NV_ROOTDIR . '/includes/core/user_functions.php';

// Google Sitemap
if ($nv_Request->isset_request(NV_NAME_VARIABLE, 'get') and $nv_Request->get_string(NV_NAME_VARIABLE, 'get') == 'SitemapIndex') {
    nv_xmlSitemapIndex_generate();
    exit();
}

// Check user
if (defined('NV_IS_USER')) {
    trigger_error('Hacking attempt', 256);
}
require NV_ROOTDIR . '/includes/core/is_user.php';

/*
 * Kết nối với các plugin trước khi gọi các module ngoài site
 * Các plugin này có thể sử dụng thông tin thành viên
 */
if (isset($nv_plugin_area[5])) {
    foreach ($nv_plugin_area[5] as $_fplugin) {
        include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
    }
}

// Cap nhat trang thai online
if ($global_config['online_upd'] and !defined('NV_IS_AJAX') and !defined('NV_IS_MY_USER_AGENT')) {
    require NV_ROOTDIR . '/includes/core/online.php';
}

// Thong ke
if ($global_config['statistic'] and !defined('NV_IS_AJAX') and !defined('NV_IS_MY_USER_AGENT')) {
    if (!$nv_Request->isset_request('statistic_' . NV_LANG_DATA, 'cookie')) {
        require NV_ROOTDIR . '/includes/core/stat.php';
    }
}

// Referer + Gqueries
if ($global_config['referer_blocker'] and $client_info['is_myreferer'] === 0 and !defined('NV_IS_MY_USER_AGENT')) {
    require NV_ROOTDIR . '/includes/core/referer.php';
}

if ($nv_Request->isset_request(NV_NAME_VARIABLE, 'get') or $nv_Request->isset_request(NV_NAME_VARIABLE, 'post')) {
    $home = 0;
    $module_name = $nv_Request->get_string(NV_NAME_VARIABLE, 'post,get');

    if (empty($module_name)) {
        $module_name = $global_config['rewrite_op_mod'];
    } elseif ($module_name == 'nv-preview-theme') {
        // Kiểm tra xem trước giao diện
        $theme = $nv_Request->get_title('theme', 'post,get', '');
        $checksum = $nv_Request->get_title('checksum', 'post,get', '');
        if (in_array($theme, $global_config['array_preview_theme'], true) and $checksum == md5(NV_LANG_DATA . $theme . $global_config['sitekey'])) {
            $nv_Request->set_Session('nv_preview_theme_' . NV_LANG_DATA, $theme);
        }
        nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
    } elseif ($module_name == 'nv-choose-theme') {
        // Thay đổi giao diện người dùng
        $theme = $nv_Request->get_title('theme', 'post,get', '');
        $tokend = $nv_Request->get_title('tokend', 'post,get', '');
        if ($tokend === NV_CHECK_SESSION and in_array($theme, $global_config['array_user_allowed_theme'], true)) {
            $nv_Request->set_Cookie('nv_u_theme_' . NV_LANG_DATA, $theme, NV_LIVE_COOKIE_TIME);
        }
        $nv_BotManager->setPrivate();
        nv_htmlOutput('OK');
    }
} else {
    $home = 1;
    $module_name = $global_config['site_home_module'];
    $meta_property['og:title'] = $global_config['site_name'];
}

if (preg_match($global_config['check_module'], $module_name)) {
    $site_mods = nv_site_mods();
    // IMG thong ke truy cap + online
    if ($global_config['statistic'] and isset($sys_mods['statistics']) and $nv_Request->get_string('second', 'get') == 'statimg') {
        include_once NV_ROOTDIR . '/includes/core/statimg.php';
    }
    $op = $nv_Request->get_string(NV_OP_VARIABLE, 'post,get', 'main');
    if (empty($op)) {
        $op = 'main';
    }

    if ($global_config['rewrite_op_mod'] != '' and !isset($sys_mods[$module_name])) {
        $op = ($op == 'main') ? $module_name : $module_name . '/' . $op;
        $module_name = $global_config['rewrite_op_mod'];
    }

    // Kiểm tra module có trong hệ thống hay không
    if (isset($site_mods[$module_name])) {
        // Global variable for module
        $module_info = $site_mods[$module_name];
        $module_file = $module_info['module_file'];
        $module_data = $module_info['module_data'];
        $module_upload = $module_info['module_upload'];
        $module_captcha = $module_name == 'users' ? $global_config['captcha_type'] : (!empty($module_config[$module_name]['captcha_type']) ? $module_config[$module_name]['captcha_type'] : '');
        if (!(empty($module_captcha) or in_array($module_captcha, ['captcha', 'recaptcha'], true)) or ($module_captcha == 'recaptcha' and (empty($global_config['recaptcha_sitekey']) or empty($global_config['recaptcha_secretkey'])))) {
            $module_captcha = 'captcha';
        }

        $include_file = NV_ROOTDIR . '/modules/' . $module_file . '/funcs/main.php';

        if (file_exists($include_file)) {
            if (empty($global_config['switch_mobi_des'])) {
                $global_config['array_theme_type'] = array_diff($global_config['array_theme_type'], [
                        'm'
                    ]);
            }
            // Tùy chọn kiểu giao diện
            if ($nv_Request->isset_request('nv' . NV_LANG_DATA . 'themever', 'get')) {
                $nv_BotManager->setNoIndex()->printToHeaders();
                $theme_type = $nv_Request->get_title('nv' . NV_LANG_DATA . 'themever', 'get', '', 1);
                if (in_array($theme_type, $global_config['array_theme_type'], true)) {
                    $nv_Request->set_Cookie('nv' . NV_LANG_DATA . 'themever', $theme_type, NV_LIVE_COOKIE_TIME);
                }

                $nv_redirect = nv_get_redirect('get', true);
                if (empty($nv_redirect)) {
                    $nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA;
                }
                nv_redirect_location($nv_redirect);
            }

            // Xac dinh cac $op, $array_op
            $array_op = [];

            if (!preg_match('/^[a-z0-9\-\_\/\+]+$/i', $op)) {
                nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
            }

            if ($op != 'main' and !isset($module_info['funcs'][$op])) {
                $array_op = explode('/', $op);
                $op = (isset($module_info['funcs'][$array_op[0]])) ? $array_op[0] : 'main';
            }
            $op_file = $op;

            // Xac dinh quyen dieu hanh module
            if ($module_info['is_modadmin']) {
                define('NV_IS_MODADMIN', true);
            }

            if (defined('NV_IS_SPADMIN')) {
                $drag_block = $nv_Request->get_int('drag_block', 'session', 0);
                if ($nv_Request->isset_request('drag_block', 'get')) {
                    $drag_block = $nv_Request->get_int('drag_block', 'get', 0);
                    $nv_Request->set_Session('drag_block', $drag_block);

                    $nv_redirect = nv_get_redirect('get', true);

                    if (empty($nv_redirect)) {
                        $nv_redirect = NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name;
                    }
                    nv_redirect_location($nv_redirect);
                }
                if ($drag_block) {
                    define('NV_IS_DRAG_BLOCK', true);
                    $adm_int_lang = $nv_Request->get_string('int_lang', 'cookie');
                    if ($adm_int_lang != NV_LANG_DATA) {
                        $nv_Request->set_Cookie('int_lang', NV_LANG_DATA, NV_LIVE_COOKIE_TIME);
                    }
                }
            }

            // Ket noi ngon ngu cua module
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php')) {
                require NV_ROOTDIR . '/modules/' . $module_file . '/language/' . NV_LANG_INTERFACE . '.php';
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php')) {
                require NV_ROOTDIR . '/modules/' . $module_file . '/language/en.php';
            }

            // Xem trước giao diện
            if (($nv_preview_theme = $nv_Request->get_title('nv_preview_theme_' . NV_LANG_DATA, 'session', '')) != '' and in_array($nv_preview_theme, $global_config['array_preview_theme'], true) and file_exists(NV_ROOTDIR . '/themes/' . $nv_preview_theme . '/theme.php')) {
                if (preg_match($global_config['check_theme_mobile'], $nv_preview_theme)) {
                    $is_mobile = true;
                    $global_config['current_theme_type'] = 'm';
                } else {
                    $is_mobile = false;
                    $global_config['current_theme_type'] = $nv_Request->get_string('nv' . NV_LANG_DATA . 'themever', 'cookie', '');
                    $array_theme_type = array_flip($global_config['array_theme_type']);
                    unset($array_theme_type['m']);
                    if (!isset($array_theme_type[$global_config['current_theme_type']])) {
                        $array_theme_type = array_flip($array_theme_type);
                        $global_config['current_theme_type'] = current($array_theme_type);
                    }
                    unset($array_theme_type);
                }
                $global_config['module_theme'] = $global_config['site_theme'] = $nv_preview_theme;
                unset($nv_preview_theme);
            } else {
                /*
                 * Xác định kiểu giao diện
                 * - responsive: r
                 * - desktop: d
                 * - mobile: m
                 */
                $global_config['current_theme_type'] = $nv_Request->get_string('nv' . NV_LANG_DATA . 'themever', 'cookie', '');
                if (!in_array($global_config['current_theme_type'], $global_config['array_theme_type'], true)) {
                    $global_config['current_theme_type'] = '';
                    $nv_Request->set_Cookie('nv' . NV_LANG_DATA . 'themever', '', NV_LIVE_COOKIE_TIME);
                }

                // Xac dinh giao dien chung
                $is_mobile = false;
                $theme_type = '';
                $_theme_mobile = empty($module_info['mobile']) ? $global_config['mobile_theme'] : (($module_info['mobile'] == ':pcsite') ? $global_config['site_theme'] : (($module_info['mobile'] == ':pcmod') ? $module_info['theme'] : $module_info['mobile']));
                if (
                        (
                            // Giao diện mobile tự động nhận diện dựa vào client
                            ($client_info['is_mobile'] and in_array('m', $global_config['array_theme_type'], true)
                                and (empty($global_config['current_theme_type']) or empty($global_config['switch_mobi_des'])))
                            // Giao diện mobile lấy từ chuyển đổi giao diện
                            or ($global_config['current_theme_type'] == 'm' and !empty($global_config['switch_mobi_des']))
                        )
                        and !empty($_theme_mobile) and file_exists(NV_ROOTDIR . '/themes/' . $_theme_mobile . '/theme.php')
                    ) {
                    $global_config['module_theme'] = $_theme_mobile;
                    $is_mobile = true;
                    $theme_type = 'm';
                } else {
                    if (empty($global_config['current_theme_type']) and in_array('r', $global_config['array_theme_type'], true) and ($client_info['is_mobile'] or empty($_theme_mobile))) {
                        $global_config['current_theme_type'] = 'r';
                    }

                    $_theme = (!empty($module_info['theme'])) ? $module_info['theme'] : $global_config['site_theme'];
                    $_u_theme = $nv_Request->get_title('nv_u_theme_' . NV_LANG_DATA, 'cookie', '');

                    if (in_array($_u_theme, $global_config['array_user_allowed_theme'], true) and file_exists(NV_ROOTDIR . '/themes/' . $_u_theme . '/theme.php')) {
                        // Giao diện do người dùng chọn
                        $global_config['module_theme'] = $_u_theme;
                        $global_config['site_theme'] = $_u_theme;
                    } elseif (!empty($_theme) and file_exists(NV_ROOTDIR . '/themes/' . $_theme . '/theme.php')) {
                        $global_config['module_theme'] = $_theme;
                    } elseif (file_exists(NV_ROOTDIR . '/themes/default/theme.php')) {
                        $global_config['module_theme'] = 'default';
                    } else {
                        trigger_error('Error! Does not exist themes default', 256);
                    }
                    $theme_type = $global_config['current_theme_type'];
                    unset($_theme, $_u_theme);
                }

                // Xac lap lai giao kieu giao dien hien tai
                if ($theme_type != $global_config['current_theme_type']) {
                    $global_config['current_theme_type'] = $theme_type;
                    $nv_Request->set_Cookie('nv' . NV_LANG_DATA . 'themever', $theme_type, NV_LIVE_COOKIE_TIME);
                }
                unset($theme_type);
            }

            // Xac dinh layout funcs cua module
            $cache_file = NV_LANG_DATA . '_' . md5($module_name . '_' . $global_config['module_theme']) . '_' . NV_CACHE_PREFIX . '.cache';
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
            $cache_file = NV_LANG_DATA . '_' . $global_config['module_theme'] . '_configposition_' . NV_CACHE_PREFIX . '.cache';
            if (($cache = $nv_Cache->getItem('themes', $cache_file)) != false) {
                $theme_config_positions = unserialize($cache);
            } else {
                $_themeConfig = nv_object2array(simplexml_load_file(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/config.ini'));
                if (isset($_themeConfig['positions']['position']['name'])) {
                    $theme_config_positions = [
                            $_themeConfig['positions']['position']
                        ];
                } elseif (isset($_themeConfig['positions']['position'])) {
                    $theme_config_positions = $_themeConfig['positions']['position'];
                } else {
                    $theme_config_positions = [];
                    $_ini_file = file_get_contents(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/config.ini');
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
            require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/theme.php';

            // Ket noi ngon ngu theo theme
            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/language/' . NV_LANG_INTERFACE . '.php')) {
                require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/language/' . NV_LANG_INTERFACE . '.php';
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/language/en.php')) {
                require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/language/en.php';
            }

            // Xac dinh template module
            $module_info['template'] = $global_config['module_theme'];
            if (!file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_info['module_theme'])) {
                if (file_exists(NV_ROOTDIR . '/themes/default/modules/' . $module_info['module_theme'])) {
                    $module_info['template'] = 'default';
                }
            }

            // Ket noi voi file functions.php, file chua cac function dung chung
            // cho ca module
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/functions.php')) {
                require NV_ROOTDIR . '/modules/' . $module_file . '/functions.php';
            }

            // Xac dinh op file
            $op_file = $module_info['funcs'][$op]['func_name'];

            if (file_exists(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_info['module_theme'] . '/theme.php')) {
                require NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_info['module_theme'] . '/theme.php';
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/theme.php')) {
                require NV_ROOTDIR . '/modules/' . $module_file . '/theme.php';
            }

            if (!defined('NV_IS_AJAX')) {
                nv_create_submenu();
            }

            // Ket noi voi cac op cua module de thuc hien
            if ($is_mobile and file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/mobile/' . $op_file . '.php')) {
                require NV_ROOTDIR . '/modules/' . $module_file . '/mobile/' . $op_file . '.php';
            } else {
                require NV_ROOTDIR . '/modules/' . $module_file . '/funcs/' . $op_file . '.php';
            }
            exit();
        }
        if (isset($module_info['funcs']['main'])) {
            $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET act=2 WHERE title= :title');
            $sth->bindParam(':title', $module_name, PDO::PARAM_STR);
            $sth->execute();

            nv_insert_notification('modules', 'auto_deactive_module', [
                    'custom_title' => $site_mods[$module_name]['custom_title']
                ]);
            $nv_Cache->delMod('modules');
        }
    } elseif (isset($sys_mods[$module_name])) {
        $groups_view = (string) $sys_mods[$module_name]['groups_view'];
        if (!defined('NV_IS_USER') and $groups_view == '4') {
            nv_redirect_location(NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=users&' . NV_OP_VARIABLE . '=login&nv_redirect=' . nv_redirect_encrypt($client_info['selfurl']));
        } elseif (!defined('NV_IS_ADMIN') and ($groups_view == '2' or $groups_view == '1')) {
            // Exit
            nv_info_die($lang_global['error_404_title'], $lang_global['site_info'], $lang_global['module_for_admin'], 404);
        } elseif (defined('NV_IS_USER') and !nv_user_in_groups($groups_view)) {
            nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
        }
    }
}

nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
