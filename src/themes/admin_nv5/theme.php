<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (!defined('NV_MAINFILE')) {
    die('Stop!!!');
}

/**
 * nv_get_submenu()
 *
 * @param mixed $mod
 * @return
 */
function nv_get_submenu($mod)
{
    global $module_name, $global_config, $admin_mods, $nv_Lang;

    $submenu = array();

    if (file_exists(NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $mod . '/admin.menu.php')) {
        $nv_Lang->loadModule($mod, true, true);
        include NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $mod . '/admin.menu.php';
        $nv_Lang->changeLang();
    }

    return $submenu;
}

/**
 * nv_get_submenu_mod()
 *
 * @param mixed $module_name
 * @return
 */
function nv_get_submenu_mod($module_name)
{
    global $global_config, $db, $site_mods, $admin_info, $db_config, $admin_mods, $nv_Lang;

    $submenu = array();
    if (isset($site_mods[$module_name])) {
        $module_info = $site_mods[$module_name];
        $module_file = $module_info['module_file'];
        $module_data = $module_info['module_data'];
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/admin.menu.php')) {
            $nv_Lang->loadModule($module_file, false, true);
            include NV_ROOTDIR . '/modules/' . $module_file . '/admin.menu.php';
            $nv_Lang->changeLang();
        }
    }
    return $submenu;
}

/**
 * nv_admin_theme()
 *
 * @param mixed $contents
 * @param integer $head_site
 * @return
 */
function nv_admin_theme($contents, $head_site = 1)
{
    global $global_config, $nv_Lang, $admin_mods, $site_mods, $admin_menu_mods, $module_name, $module_file, $module_info, $admin_info, $page_title, $submenu, $select_options, $op, $set_active_op, $array_lang_admin, $my_head, $my_footer, $array_mod_title, $array_url_instruction, $op, $client_info, $nv_plugin_area;

    $dir_template = '';

    // Xác định file template
    if ($head_site == 1) {
        $file_name_tpl = 'main.tpl';

        if (file_exists(NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/system/' . $file_name_tpl)) {
            $dir_template = NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/system';
        } else {
            $dir_template = NV_ROOTDIR . '/themes/admin_default/system';
            $admin_info['admin_theme'] = 'admin_default';
        }
    } else {
        $file_name_tpl = 'content.tpl';

        if (file_exists(NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/system/' . $file_name_tpl)) {
            $dir_template = NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/system';
        } else {
            $dir_template = NV_ROOTDIR . '/themes/admin_default/system';
            $admin_info['admin_theme'] = 'admin_default';
        }
    }

    $global_config['site_name'] = empty($global_config['site_name']) ? NV_SERVER_NAME : $global_config['site_name'];

    $site_favicon = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }

    if (isset($nv_plugin_area[4])) {
        // Kết nối với các plugin sau khi xây dựng nội dung module
        foreach ($nv_plugin_area[4] as $_fplugin) {
            include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
        }
    }

    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir($dir_template);
    $tpl->assign('LANG', $nv_Lang);

    $tpl->assign('NV_SITE_COPYRIGHT', $global_config['site_name'] . ' [' . $global_config['site_email'] . '] ');
    $tpl->assign('NV_SITE_NAME', $global_config['site_name']);
    $tpl->assign('NV_SITE_TITLE', $global_config['site_name'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $nv_Lang->get('admin_page') . ' ' . NV_TITLEBAR_DEFIS . ' ' . $module_info['custom_title']);
    $tpl->assign('SITE_DESCRIPTION', empty($global_config['site_description']) ? $page_title : $global_config['site_description']);
    $tpl->assign('NV_CHECK_PASS_MSTIME', (intval($global_config['admin_check_pass_time']) - 62) * 1000);
    $tpl->assign('NV_ADMINDIR', NV_ADMINDIR);
    $tpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('MODULE_FILE', $module_file);
    $tpl->assign('NV_ADMIN_THEME', $admin_info['admin_theme']);
    $tpl->assign('NV_SAFEMODE', $admin_info['safemode']);
    $tpl->assign('SITE_FAVICON', $site_favicon);
    $tpl->assign('NV_SITE_TIMEZONE_OFFSET', round(NV_SITE_TIMEZONE_OFFSET / 3600));
    $tpl->assign('NV_CURRENTTIME', nv_date('T', NV_CURRENTTIME));
    $tpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);
    $tpl->assign('THEME_SITE_HREF', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
    $tpl->assign('SITE_CHARSET', $global_config['site_charset']);

    // JS và CSS của module
    if (file_exists(NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/css/' . $module_file . '.css')) {
        $tpl->assign('NV_CSS_MODULE_THEME', NV_BASE_SITEURL . 'themes/' . $admin_info['admin_theme'] . '/css/' . $module_file . '.css');
    } elseif (file_exists(NV_ROOTDIR . '/themes/admin_default/css/' . $module_file . '.css')) {
        $tpl->assign('NV_CSS_MODULE_THEME', NV_BASE_SITEURL . 'themes/admin_default/css/' . $module_file . '.css');
    }

    if (file_exists(NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/js/' . $module_file . '.js')) {
        $tpl->assign('NV_JS_MODULE', NV_BASE_SITEURL . 'themes/' . $admin_info['admin_theme'] . '/js/' . $module_file . '.js');
    } elseif (file_exists(NV_ROOTDIR . '/themes/admin_default/js/' . $module_file . '.js')) {
        $tpl->assign('NV_JS_MODULE', NV_BASE_SITEURL . 'themes/admin_default/js/' . $module_file . '.js');
    }

    if ($head_site == 1) {
        // Thông tin tài khoản quản trị
        $tpl->assign('ADMIN_USERNAME', $admin_info['username']);
        $tpl->assign('ADMIN_LEV_LOOP', 4 - $admin_info['level']);
        $tpl->assign('ADMIN_LINK_INFO', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=users&amp;' . NV_OP_VARIABLE . '=edit&amp;userid=' . $admin_info['admin_id']);
        if (!empty($admin_info['photo']) and file_exists(NV_ROOTDIR . '/' . $admin_info['photo'])) {
            $tpl->assign('ADMIN_PHOTO', NV_BASE_SITEURL . $admin_info['photo']);
        } else {
            $tpl->assign('ADMIN_PHOTO', NV_BASE_SITEURL . 'themes/' . $admin_info['admin_theme'] . '/images/Users/no-avatar.png');
        }

        if ($admin_info['current_login'] >= NV_CURRENTTIME - 60) {
            if (!empty($admin_info['last_login'])) {
                $tpl->assign('HELLO_ADMIN', $nv_Lang->get('hello_admin1', date('H:i d/m/Y', $admin_info['last_login'])));
            } else {
                $tpl->assign('HELLO_ADMIN', $nv_Lang->get('hello_admin3'));
            }
        } else {
            $tpl->assign('HELLO_ADMIN', $nv_Lang->get('hello_admin2', nv_convertfromSec(NV_CURRENTTIME - $admin_info['current_login'])));
        }

        // Nút ở menu top
        $lang_site = (!empty($site_mods)) ? NV_LANG_DATA : $global_config['site_lang'];
        $tpl->assign('NV_GO_CLIENTSECTOR_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_site);

        // Menu các module quản trị
        $top_menu = $admin_mods;
        if (sizeof($top_menu) > 8) {
            if ($module_name != 'authors') {
                unset($top_menu['authors']);
            }
            if ($module_name != 'language') {
                unset($top_menu['language']);
            }
        }
        $array_sys_menu = [];
        $loop_sys_menu = 0;
        $key_sys_menu = 0;
        foreach ($top_menu as $m => $v) {
            if (!empty($v['custom_title'])) {
                if ($loop_sys_menu % 4 == 0) {
                    $key_sys_menu++;
                }
                if (!isset($array_sys_menu[$key_sys_menu])) {
                    $array_sys_menu[$key_sys_menu] = [];
                }
                $array_sys_menu[$key_sys_menu][$m] = [
                    'title' => $v['custom_title'],
                    'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m,
                    'issubs' => false,
                    'subs' => []
                ];
                $array_submenu = nv_get_submenu($m);
                if (!empty($array_submenu)) {
                    $array_sys_menu[$key_sys_menu][$m]['issubs'] = true;
                    foreach ($array_submenu as $mop => $submenu_i) {
                        $array_sys_menu[$key_sys_menu][$m]['subs'][] = [
                            'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m . '&amp;' . NV_OP_VARIABLE . '=' . $mop,
                            'title' => $submenu_i
                        ];
                    }
                }
                $loop_sys_menu++;
            }
        }
        $tpl->assign('SYS_MENU', $array_sys_menu);

        // Menu của các module
        $array_mod_menu = $array_mod_current = [];
        foreach ($admin_menu_mods as $m => $v) {
            if ($m != $module_name) {
                // Các module khác
                $array_mod_menu[] = [
                    'name' => $m,
                    'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m,
                    'title' => $v,
                    'icon' => isset($site_mods[$m]) ? $site_mods[$m]['icon'] : '',
                    'subs' => nv_get_submenu_mod($m)
                ];
            } else {
                // Module hiện tại
                $array_mod_current = [
                    'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m,
                    'title' => $v,
                    'icon' => isset($site_mods[$m]) ? $site_mods[$m]['icon'] : (isset($admin_mods[$m]) ? $admin_mods[$m]['icon'] : ''),
                    'active' => ((empty($op) or $op == 'main') or (!empty($set_active_op) and $set_active_op == 'main')) ? true : false,
                    'subs' => []
                ];
                if (!empty($submenu)) {
                    foreach ($submenu as $_op => $_op_title) {
                        $subs = [];
                        $subs['link'] = NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m . '&amp;' . NV_OP_VARIABLE . '=' . $_op;
                        $subs['active'] = ((!empty($op) and $op == $_op) or (!empty($set_active_op) and $set_active_op == $_op)) ? true : false;
                        $subs['open'] = false;
                        $subs['subs'] = [];
                        if (is_array($_op_title) and isset($_op_title['submenu'])) {
                            // Có menu cấp 3
                            $subs['title'] = $_op_title['title'];
                            foreach ($_op_title['submenu'] as $s_op => $s_op_title) {
                                $isSub2Active = ((!empty($op) and $op == $s_op) or (!empty($set_active_op) and $set_active_op == $s_op)) ? true : false;
                                $subs['subs'][] = [
                                    'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m . '&amp;' . NV_OP_VARIABLE . '=' . $s_op,
                                    'title' => $s_op_title,
                                    'active' => $isSub2Active
                                ];
                                if ($isSub2Active) {
                                    $subs['open'] = true;
                                }
                            }
                        } else {
                            // Tới menu cấp 2
                            $subs['title'] = $_op_title;
                        }
                        $array_mod_current['subs'][] = $subs;
                    }
                }
            }
        }

        // Thiết lập giao diện
        $tpl->assign('CONFIG_THEME', isset($admin_info['config_theme'][$admin_info['admin_theme']]) ? $admin_info['config_theme'][$admin_info['admin_theme']] : []);

        $tpl->assign('MOD_MENU', $array_mod_menu);
        $tpl->assign('MOD_CURRENT', $array_mod_current);

        // Các tùy chọn đơn tại một khu vực
        $tpl->assign('SELECT_OPTIONS', $select_options);

        // Ngôn ngữ
        $tpl->assign('ARRAY_LANGS', $array_lang_admin);

        // Thông báo
        $tpl->assign('NOTIFICATION_ACTIVE', $global_config['notification_active']);
        $tpl->assign('NV_GO_ALL_NOTIFICATION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=siteinfo&amp;' . NV_OP_VARIABLE . '=notification');

        // Tài liệu module và xem ngoài site
        if (isset($site_mods[$module_name]['main_file']) and $site_mods[$module_name]['main_file']) {
            $tpl->assign('NV_GO_CLIENTMOD', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name);
        }
        if (isset($array_url_instruction[$op])) {
            $tpl->assign('NV_URL_INSTRUCTION', $array_url_instruction[$op]);
        }
    }

    $tpl->assign('THEME_ERROR_INFO', nv_error_info());
    $tpl->assign('PAGE_TITLE', $page_title);
    $tpl->assign('BREADCRUMBS', $array_mod_title);
    $tpl->assign('MODULE_CONTENT', $contents);
    $tpl->assign('NV_COPYRIGHT', $nv_Lang->get('copyright', $global_config['site_name']));

    if (defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) {
        $memory_time_usage = true;
    } else {
        $memory_time_usage = false;
    }
    $tpl->assign('memory_time_usage', $memory_time_usage);

    $sitecontent = $tpl->fetch($file_name_tpl);

    if (!empty($my_head)) {
        $sitecontent = preg_replace('/(<\/head>)/i', $my_head . "\\1", $sitecontent, 1);
    }
    if (!empty($my_footer)) {
        $sitecontent = preg_replace('/(<\/body>)/i', $my_footer . "\\1", $sitecontent, 1);
    }

    return $sitecontent;
}

// Lưu thiết lập giao diện
if ($nv_Request->isset_request('nv_change_theme_config', 'post')) {
    $collapsed_leftsidebar = (int)$nv_Request->get_bool('collapsed_leftsidebar', 'post', false);
    $config_theme = $admin_info['config_theme'];
    $config_theme[$admin_info['admin_theme']] = [
        'collapsed_leftsidebar' => $collapsed_leftsidebar
    ];
    $sql = "UPDATE " . NV_AUTHORS_GLOBALTABLE . " SET config_theme=" . $db->quote(serialize($config_theme)) . " WHERE admin_id=" . $admin_info['admin_id'];
    if ($db->exec($sql)) {
        nv_htmlOutput('OK');
    }
    nv_htmlOutput('ERROR');
}
