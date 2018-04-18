<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 31/05/2010, 00:36
 */

if (! defined('NV_MAINFILE')) {
    die('Stop!!!');
}

function nv_mailHTML($title, $content, $footer='')
{
    global $global_config, $lang_global;

    $xtpl = new XTemplate('mail.tpl', NV_ROOTDIR . '/themes/default/system');
    $xtpl->assign('SITE_URL', NV_MY_DOMAIN);
    $xtpl->assign('GCONFIG', $global_config);
    $xtpl->assign('LANG', $lang_global);
    $xtpl->assign('MESSAGE_TITLE', $title);
    $xtpl->assign('MESSAGE_CONTENT', $content);
    $xtpl->assign('MESSAGE_FOOTER', $footer);
    $xtpl->parse('main');
    return $xtpl->text('main');
}

/**
 * nv_get_submenu()
 *
 * @param mixed $mod
 * @return
 */
function nv_get_submenu($mod)
{
    global $module_name, $global_config, $admin_mods, $lang_global;

    $submenu = array();

    if (file_exists(NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $mod . '/admin.menu.php')) {
        //ket noi voi file ngon ngu cua module
        if (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_' . $mod . '.php')) {
            include NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_' . $mod . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_' . $mod . '.php')) {
            include NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_' . $mod . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/includes/language/en/admin_' . $mod . '.php')) {
            include NV_ROOTDIR . '/includes/language/en/admin_' . $mod . '.php';
        }

        include NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $mod . '/admin.menu.php';
        unset($lang_module);
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
    global  $lang_global, $global_config, $db, $site_mods, $admin_info, $db_config, $admin_mods;

    $submenu = array();
    if (isset($site_mods[$module_name])) {
        $module_info = $site_mods[$module_name];
        $module_file = $module_info['module_file'];
        $module_data = $module_info['module_data'];
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/admin.menu.php')) {
            //ket noi voi file ngon ngu cua module
            if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php')) {
                include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php';
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_DATA . '.php')) {
                include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_DATA . '.php';
            } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php')) {
                include NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php';
            }

            include NV_ROOTDIR . '/modules/' . $module_file . '/admin.menu.php';
            unset($lang_module);
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
    global $global_config, $lang_global, $admin_mods, $site_mods, $admin_menu_mods, $module_name, $module_file, $module_info, $admin_info, $page_title, $submenu, $select_options, $op, $set_active_op, $array_lang_admin, $my_head, $my_footer, $array_mod_title, $array_url_instruction, $op, $client_info, $nv_plugin_area;

    $dir_template = '';

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
    if (! empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }

    if (isset($nv_plugin_area[4])) {
        // Kết nối với các plugin sau khi xây dựng nội dung module
        foreach ($nv_plugin_area[4] as $_fplugin) {
            include NV_ROOTDIR . '/includes/plugin/' . $_fplugin;
        }
    }

    $xtpl = new XTemplate($file_name_tpl, $dir_template);
    $xtpl->assign('NV_SITE_COPYRIGHT', $global_config['site_name'] . ' [' . $global_config['site_email'] . '] ');
    $xtpl->assign('NV_SITE_NAME', $global_config['site_name']);
    $xtpl->assign('NV_SITE_TITLE', $global_config['site_name'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $lang_global['admin_page'] . ' ' . NV_TITLEBAR_DEFIS . ' ' . $module_info['custom_title']);
    $xtpl->assign('SITE_DESCRIPTION', empty($global_config['site_description']) ? $page_title : $global_config['site_description']);
    $xtpl->assign('NV_CHECK_PASS_MSTIME', (intval($global_config['admin_check_pass_time']) - 62) * 1000);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('NV_ADMINDIR', NV_ADMINDIR);
    $xtpl->assign('NV_BASE_ADMINURL', NV_BASE_ADMINURL);
    $xtpl->assign('NV_ASSETS_DIR', NV_ASSETS_DIR);
    $xtpl->assign('MODULE_NAME', $module_name);
    $xtpl->assign('MODULE_FILE', $module_file);
    $xtpl->assign('NV_ADMIN_THEME', $admin_info['admin_theme']);
    $xtpl->assign('NV_SAFEMODE', $admin_info['safemode']);
    $xtpl->assign('LANG', $lang_global);
    $xtpl->assign('SITE_FAVICON', $site_favicon);

    if (file_exists(NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/css/' . $module_file . '.css')) {
        $xtpl->assign('NV_CSS_MODULE_THEME', NV_BASE_SITEURL . 'themes/' . $admin_info['admin_theme'] . '/css/' . $module_file . '.css');
        $xtpl->parse('main.css_module');
    } elseif (file_exists(NV_ROOTDIR . '/themes/admin_default/css/' . $module_file . '.css')) {
        $xtpl->assign('NV_CSS_MODULE_THEME', NV_BASE_SITEURL . 'themes/admin_default/css/' . $module_file . '.css');
        $xtpl->parse('main.css_module');
    }

    $xtpl->assign('NV_LANG_VARIABLE', NV_LANG_VARIABLE);
    $xtpl->assign('NV_LANG_INTERFACE', NV_LANG_INTERFACE);
    $xtpl->assign('NV_NAME_VARIABLE', NV_NAME_VARIABLE);
    $xtpl->assign('NV_OP_VARIABLE', NV_OP_VARIABLE);
    $xtpl->assign('NV_SITE_TIMEZONE_OFFSET', round(NV_SITE_TIMEZONE_OFFSET / 3600));
    $xtpl->assign('NV_CURRENTTIME', nv_date('T', NV_CURRENTTIME));
    $xtpl->assign('NV_COOKIE_PREFIX', $global_config['cookie_prefix']);

    if (file_exists(NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/js/' . $module_file . '.js')) {
        $xtpl->assign('NV_JS_MODULE', NV_BASE_SITEURL . 'themes/' . $admin_info['admin_theme'] . '/js/' . $module_file . '.js');
        $xtpl->parse('main.module_js');
    } elseif (file_exists(NV_ROOTDIR . '/themes/admin_default/js/' . $module_file . '.js')) {
        $xtpl->assign('NV_JS_MODULE', NV_BASE_SITEURL . 'themes/admin_default/js/' . $module_file . '.js');
        $xtpl->parse('main.module_js');
    }

    if ($head_site == 1) {
        $xtpl->assign('NV_GO_CLIENTSECTOR', $lang_global['go_clientsector']);
        $lang_site = (! empty($site_mods)) ? NV_LANG_DATA : $global_config['site_lang'];
        $xtpl->assign('NV_GO_CLIENTSECTOR_URL', NV_BASE_SITEURL . 'index.php?' . NV_LANG_VARIABLE . '=' . $lang_site);
        $xtpl->assign('NV_LOGOUT', $lang_global['admin_logout_title']);
        $xtpl->assign('NV_GO_ALL_NOTIFICATION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=siteinfo&amp;' . NV_OP_VARIABLE . '=notification');

        if (! empty($array_lang_admin)) {
            $xtpl->assign('NV_LANGDATA', $lang_global['langdata']);
            $xtpl->assign('NV_LANGDATA_CURRENT', $array_lang_admin[NV_LANG_DATA]);
            $xtpl->assign('NV_LANGINTERFACE_CURRENT', $array_lang_admin[NV_LANG_INTERFACE]);
            foreach ($array_lang_admin as $lang_i => $lang_name) {
                $xtpl->assign('LANGVALUE', $lang_name);
                $xtpl->assign('DISABLED', ($lang_i == NV_LANG_DATA) ? " class=\"disabled\"" : "");
                $xtpl->assign('LANGOP', NV_BASE_ADMINURL . 'index.php?langinterface=' . NV_LANG_INTERFACE . '&' . NV_LANG_VARIABLE . '=' . $lang_i);
                $xtpl->parse('main.langdata.option');

                $xtpl->assign('DISABLED', ($lang_i == NV_LANG_INTERFACE) ? " class=\"disabled\"" : "");
                $xtpl->assign('LANGOP', NV_BASE_ADMINURL . 'index.php?langinterface=' . $lang_i . '&' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA);
                $xtpl->parse('main.langinterface.option');
            }
            $xtpl->parse('main.langdata');
            $xtpl->parse('main.langinterface');
        }

        // Top_menu
        $top_menu = $admin_mods;
        if (sizeof($top_menu) > 8) {
            if ($module_name != 'authors') {
                unset($top_menu['authors']);
            }
            if ($module_name != 'language') {
                unset($top_menu['language']);
            }
        }
        foreach ($top_menu as $m => $v) {
            if (! empty($v['custom_title'])) {
                $array_submenu = nv_get_submenu($m);

                $xtpl->assign('TOP_MENU_CLASS', $array_submenu ? ' class="dropdown"' : '');
                $xtpl->assign('TOP_MENU_HREF', $m);
                $xtpl->assign('TOP_MENU_NAME', $v['custom_title']);

                if (! empty($array_submenu)) {
                    $xtpl->parse('main.top_menu_loop.has_sub');

                    foreach ($array_submenu as $mop => $submenu_i) {
                        $xtpl->assign('SUBMENULINK', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m . '&amp;' . NV_OP_VARIABLE . '=' . $mop);
                        $xtpl->assign('SUBMENUTITLE', $submenu_i);
                        $xtpl->parse('main.top_menu_loop.submenu.submenu_loop');
                    }

                    $xtpl->parse('main.top_menu_loop.submenu');
                }

                $xtpl->parse('main.top_menu_loop');
            }
        }

        $xtpl->parse('main.top_menu');

        if ($admin_info['current_login'] >= NV_CURRENTTIME - 60) {
            if (! empty($admin_info['last_login'])) {
                $temp = sprintf($lang_global['hello_admin1'], $admin_info['username'], date('H:i d/m/Y', $admin_info['last_login']), $admin_info['last_ip']);
                $xtpl->assign('HELLO_ADMIN1', $temp);
                $xtpl->parse('main.hello_admin');
            } else {
                $temp = sprintf($lang_global['hello_admin3'], $admin_info['username']);
                $xtpl->assign('HELLO_ADMIN3', $temp);
                $xtpl->parse('main.hello_admin3');
            }
        } else {
            $temp = sprintf($lang_global['hello_admin2'], $admin_info['username'], nv_convertfromSec(NV_CURRENTTIME - $admin_info['current_login']), $admin_info['current_ip']);
            $xtpl->assign('HELLO_ADMIN2', $temp);
            $xtpl->parse('main.hello_admin2');
        }

        // Admin photo
        $xtpl->assign('ADMIN_USERNAME', $admin_info['username']);
        if (! empty($admin_info['photo']) and file_exists(NV_ROOTDIR . '/' . $admin_info['photo'])) {
            $xtpl->assign('ADMIN_PHOTO', NV_BASE_SITEURL . $admin_info['photo']);
        } else {
            $xtpl->assign('ADMIN_PHOTO', NV_BASE_SITEURL . 'themes/default/images/users/no_avatar.png');
        }

        // Vertical menu
        foreach ($admin_menu_mods as $m => $v) {
            $xtpl->assign('MENU_CLASS', (($module_name == $m) ? ' class="active"' : ''));
            $xtpl->assign('MENU_HREF', $m);
            $xtpl->assign('MENU_NAME', $v);

            if ($m != $module_name) {
                $submenu = nv_get_submenu_mod($m);

                $xtpl->assign('MENU_CLASS', $submenu ? ' class="dropdown"' : '');

                if (! empty($submenu)) {
                    foreach ($submenu as $n => $l) {
                        $xtpl->assign('MENU_SUB_HREF', $m);
                        $xtpl->assign('MENU_SUB_OP', $n);
                        $xtpl->assign('MENU_SUB_NAME', (is_array($l) and isset($l['title'])) ? $l['title'] : $l);
                        $xtpl->parse('main.menu_loop.submenu.loop');
                    }
                    $xtpl->parse('main.menu_loop.submenu');
                }
            } elseif (! empty($submenu)) {
                foreach ($submenu as $n => $l) {
                    if (is_array($l) and isset($l['submenu'])) {
                        $_subtitle = $l['title'];
                        $_submenu_i = $l['submenu'];
                    } else {
                        $_subtitle = $l;
                        $_submenu_i = '';
                    }
                    $xtpl->assign('MENU_SUB_CURRENT', (((! empty($op) and $op == $n) or (! empty($set_active_op) and $set_active_op == $n)) ? 'subactive' : 'subcurrent'));
                    $xtpl->assign('MENU_SUB_HREF', $m);
                    $xtpl->assign('MENU_SUB_OP', $n);
                    $xtpl->assign('MENU_SUB_NAME', $_subtitle);
                    $xtpl->assign('MENU_CLASS', '');
                    if (! empty($_submenu_i)) {
                        $xtpl->assign('MENU_CLASS', ' class="dropdown"');
                        foreach ($_submenu_i as $sn => $sl) {
                            $xtpl->assign('CUR_SUB_OP', $sn);
                            $xtpl->assign('CUR_SUB_NAME', $sl);
                            $xtpl->parse('main.menu_loop.current.submenu.loop');
                        }
                        $xtpl->parse('main.menu_loop.current.submenu');
                    }
                    $xtpl->parse('main.menu_loop.current');
                }
            }
            $xtpl->parse('main.menu_loop');
        }

        // Notification icon
        if ($global_config['notification_active']) {
            $xtpl->parse('main.notification');
            $xtpl->parse('main.notification_js');
        }
    }

    if (! empty($select_options)) {
        $xtpl->assign('PLEASE_SELECT', $lang_global['please_select']);

        foreach ($select_options as $value => $link) {
            $xtpl->assign('SELECT_NAME', $link);
            $xtpl->assign('SELECT_VALUE', $value);
            $xtpl->parse('main.select_option.select_option_loop');
        }

        $xtpl->parse('main.select_option');
    }
    if (isset($site_mods[$module_name]['main_file']) and $site_mods[$module_name]['main_file']) {
        $xtpl->assign('NV_GO_CLIENTMOD', $lang_global['go_clientmod']);
        $xtpl->parse('main.site_mods');
    }

    if (isset($array_url_instruction[$op])) {
        $xtpl->assign('NV_INSTRUCTION', $lang_global['go_instrucion']);
        $xtpl->assign('NV_URL_INSTRUCTION', $array_url_instruction[$op]);
        $xtpl->parse('main.url_instruction');
    }

    /**
     * Breadcrumbs
     * Note: If active is true, the link will be dismiss
     * If empty $array_mod_title and $page_title, breadcrumbs do not display
     * By default, breadcrumbs is $page_title
     */
    if (empty($array_mod_title) and ! empty($page_title)) {
        $array_mod_title = array(
            0 => array(
                'title' => $page_title,
                'link' => '',
                'active' => true,
            )
        );
    }

    if (! empty($array_mod_title)) {
        foreach ($array_mod_title as $breadcrumbs) {
            $xtpl->assign('BREADCRUMBS', $breadcrumbs);

            if (! empty($breadcrumbs['active'])) {
                $xtpl->parse('main.breadcrumbs.loop.active');
            }

            if (! empty($breadcrumbs['link']) and empty($breadcrumbs['active'])) {
                $xtpl->parse('main.breadcrumbs.loop.linked');
            } else {
                $xtpl->parse('main.breadcrumbs.loop.text');
            }
            $xtpl->parse('main.breadcrumbs.loop');
        }
        $xtpl->parse('main.breadcrumbs');
    }

    $xtpl->assign('THEME_ERROR_INFO', nv_error_info());
    $xtpl->assign('MODULE_CONTENT', $contents);
    $xtpl->assign('NV_COPYRIGHT', sprintf($lang_global['copyright'], $global_config['site_name']));

    if (defined('CKEDITOR')) {
        $xtpl->parse('main.ckeditor');
    }

    if (defined('NV_IS_SPADMIN') and $admin_info['level'] == 1) {
        $xtpl->parse('main.memory_time_usage');
    }

    if ($client_info['browser']['key'] == 'explorer' and $client_info['browser']['version'] < 9) {
        $xtpl->parse('main.lt_ie9');
    }

    $xtpl->parse('main');
    $sitecontent = $xtpl->text('main');

    if (! empty($my_head)) {
        $sitecontent = preg_replace('/(<\/head>)/i', $my_head . "\\1", $sitecontent, 1);
    }
    if (! empty($my_footer)) {
        $sitecontent = preg_replace('/(<\/body>)/i', $my_footer . "\\1", $sitecontent, 1);
    }

    return $sitecontent;
}