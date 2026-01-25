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

use NukeViet\Client\Browser;
use NukeViet\Template\Config;

// Thiết lập config riêng của giao diện
if (!empty($nv_Request) and $nv_Request->isset_request('store_theme_config', 'post')) {
    $respon = [
        'error' => 1,
        'message' => 'Unknow error'
    ];
    if (!defined('NV_IS_AJAX')) {
        $respon['message'] = 'Wrong ajax!!!';
        nv_jsonOutput($respon);
    }
    if ($nv_Request->get_title('store_theme_config', 'post', '') !== NV_CHECK_SESSION) {
        $respon['message'] = 'Wrong checksess!!!';
        nv_jsonOutput($respon);
    }

    $config_name = $nv_Request->get_title('config_name', 'post', '');
    $config_value = $nv_Request->get_title('config_value', 'post', '');

    $sql = "SELECT * FROM " . NV_AUTHORS_GLOBALTABLE . "_vars WHERE admin_id=" . $admin_info['admin_id'] . "
    AND theme=" . $db->quote($admin_info['admin_theme']) . " AND config_name=" . $db->quote($config_name);
    $row = $db->query($sql)->fetch();

    if (empty($row)) {
        $sql = "INSERT INTO " . NV_AUTHORS_GLOBALTABLE . "_vars (
            admin_id, theme, config_name, config_value
        ) VALUES (
            " . $admin_info['admin_id'] . ", " . $db->quote($admin_info['admin_theme']) . ",
            " . $db->quote($config_name) . ", " . $db->quote($config_value) . "
        )";
    } else {
        $sql = "UPDATE " . NV_AUTHORS_GLOBALTABLE . "_vars SET config_value=" . $db->quote($config_value) . " WHERE id=" . $row['id'];
    }
    $db->query($sql);

    $respon['error'] = 0;
    nv_jsonOutput($respon);
}

/**
 * @param string $contents
 * @param number $head_site
 * @return string
 */
function nv_admin_theme(?string $contents, $head_site = 1)
{
    global $admin_info, $nv_Lang, $global_config, $module_info, $page_title, $module_file, $module_name, $op, $browser, $client_info, $site_mods, $admin_mods, $db, $array_lang_admin, $select_options, $admin_menu_mods, $submenu, $set_active_op, $array_url_instruction, $array_mod_title, $my_head, $my_footer;

    $file_name_tpl = $head_site == 1 ? 'main.tpl' : 'content.tpl';
    $tpl_dir = get_tpl_dir($admin_info['admin_theme'], NV_DEFAULT_ADMIN_THEME, '/system/' . $file_name_tpl);

    $sql = "SELECT config_name, config_value FROM " . NV_AUTHORS_GLOBALTABLE . "_vars WHERE admin_id=" . $admin_info['admin_id'] . "
    AND theme=" . $db->quote($admin_info['admin_theme']) . " AND (lang='all' OR lang=" . $db->quote(NV_LANG_DATA) . ")";
    $theme_config = $db->query($sql)->fetchAll(PDO::FETCH_KEY_PAIR);
    !isset($theme_config['color_mode']) && $theme_config['color_mode'] = 'auto';
    !isset($theme_config['dir']) && $theme_config['dir'] = 'ltr';
    $theme_config['dir'] == 'rtl' && Config::setRtl(true);

    $nv_Lang->loadFile(NV_ROOTDIR . '/themes/' . $tpl_dir . '/language/' . NV_LANG_INTERFACE . '.php');

    $tpl = new \NukeViet\Template\NVSmarty();
    $tpl->registerPlugin('modifier', 'date', 'nv_date');
    $tpl->registerPlugin('modifier', 'submenu', 'nv_get_submenu');
    $tpl->registerPlugin('modifier', 'submenumod', 'nv_get_submenu_mod');
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $tpl_dir . '/system');
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('GCONFIG', $global_config);
    $tpl->assign('MODULE_INFO', $module_info);
    $tpl->assign('PAGE_TITLE', $page_title ?: $nv_Lang->getGlobal('admin_page'));
    $tpl->assign('BREADCRUMBS', $array_mod_title);
    $tpl->assign('MODULE_FILE', $module_file);
    $tpl->assign('MODULE_NAME', $module_name);
    $tpl->assign('OP', $op);
    $tpl->assign('ADMIN_INFO', $admin_info);
    $tpl->assign('IS_IE', $browser->isBrowser(Browser::BROWSER_IE));
    $tpl->assign('CLIENT_INFO', $client_info);
    $tpl->assign('SITE_MODS', $site_mods);
    $tpl->assign('ADMIN_MODS', $admin_mods);
    $tpl->assign('TCONFIG', $theme_config);
    $tpl->assign('LANG_ADMIN', $array_lang_admin);
    $tpl->assign('SELECT_OPTIONS', $select_options);
    $tpl->assign('HELP_URLS', $array_url_instruction);

    // Biến này để sử dụng trên các tệp khác gọi tpl
    $tpl->assign('ADMIN_THEME', $admin_info['admin_theme']);

    // Menu của các module
    $array_mod_menu = $array_mod_current = [];
    foreach ($admin_menu_mods as $m => $v) {
        if ($m != $module_name) {
            // Các module khác
            $array_mod_menu[] = [
                'name' => $m,
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m,
                'title' => $v,
                'icon' => (isset($site_mods[$m]) and !empty($site_mods[$m]['icon'])) ? $site_mods[$m]['icon'] : 'fa-solid fa-globe',
                'subs' => nv_get_submenu_mod($m)
            ];
        } else {
            // Module hiện tại
            $array_mod_current = [
                'link' => NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m,
                'title' => $v,
                'icon' => (isset($site_mods[$m]) and !empty($site_mods[$m]['icon'])) ? $site_mods[$m]['icon'] : ((isset($admin_mods[$m]) and !empty($admin_mods[$m]['icon'])) ? $admin_mods[$m]['icon'] : 'fa-solid fa-globe'),
                'active' => ((empty($op) or $op == 'main') or (!empty($set_active_op) and $set_active_op == 'main')) ? true : false,
                'subs' => []
            ];
            if (!empty($submenu)) {
                foreach ($submenu as $_op => $_op_title) {
                    $subs = [];
                    $subs['link'] = preg_match('/^\#/', $_op) ? '#' : NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $m . '&amp;' . NV_OP_VARIABLE . '=' . $_op;
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
    $tpl->assign('MOD_MENU', $array_mod_menu);
    $tpl->assign('MOD_CURRENT', $array_mod_current);

    // Icon site
    $site_favicon = NV_BASE_SITEURL . 'favicon.ico';
    if (!empty($global_config['site_favicon']) and file_exists(NV_ROOTDIR . '/' . $global_config['site_favicon'])) {
        $site_favicon = NV_BASE_SITEURL . $global_config['site_favicon'];
    }
    $tpl->assign('FAVICON', $site_favicon);

    // CSS riêng của module
    $theme_tpl = get_tpl_dir([$admin_info['admin_theme'], NV_DEFAULT_ADMIN_THEME], '', '/css/' . $module_file . '.css');
    $css_module = '';
    if (!empty($theme_tpl)) {
        $css_module = $theme_tpl . '/css/' . $module_file . ($theme_config['dir'] == 'rtl' ? '.rtl' : '') . '.css';
        if ($theme_config['dir'] == 'rtl' and !theme_file_exists($css_module)) {
            $css_module = $theme_tpl . '/css/' . $module_file . '.css';
        }
    }
    $tpl->assign('CSS_MODULE', $css_module ? (NV_STATIC_URL . 'themes/' . $css_module) : '');

    // JS riêng của module
    $theme_tpl = get_tpl_dir([$admin_info['admin_theme'], NV_DEFAULT_ADMIN_THEME], '', '/js/' . $module_file . '.js');
    $js_module = '';
    if (!empty($theme_tpl)) {
        $js_module = NV_STATIC_URL . 'themes/' . $theme_tpl . '/js/' . $module_file . '.js';
    }
    $tpl->assign('JS_MODULE', $js_module);

    $whitelisted_attr = ['target'];
    if (!empty($global_config['allowed_html_tags']) and in_array('iframe', $global_config['allowed_html_tags'])) {
        $whitelisted_attr[] = 'frameborder';
        $whitelisted_attr[] = 'allowfullscreen';
    }
    $tpl->assign('WHITELISTED_ATTR', "['" . implode("', '", $whitelisted_attr). "']");
    $tpl->assign('JSDATE_GET', nv_region_config('jsdate_get'));
    $tpl->assign('JSDATE_POST', nv_region_config('jsdate_post'));
    $tpl->assign('JS_AM', nv_region_config('am_char'));
    $tpl->assign('JS_PM', nv_region_config('pm_char'));

    $tpl->assign('MODULE_CONTENT', $contents);

    $sitecontent = $tpl->fetch($file_name_tpl);
    $sitecontent = str_replace('[THEME_ERROR_INFO]', nv_error_info(), $sitecontent);

    if (!empty($my_head)) {
        $sitecontent = preg_replace('/(<\/head>)/i', $my_head . '\\1', $sitecontent, 1);
    }
    if (!empty($my_footer)) {
        $sitecontent = preg_replace('/(<\/body>)/i', $my_footer . '\\1', $sitecontent, 1);
    }

    return $sitecontent;
}

/**
 * @param string $mod
 * @return array
 */
function nv_get_submenu($mod)
{
    // Các biến global này cần dùng khi include nên không xóa
    global $module_name, $global_config, $admin_mods, $nv_Lang;

    $submenu = [];

    if (file_exists(NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $mod . '/admin.menu.php')) {
        $nv_Lang->loadModule($mod, true, true);
        include NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $mod . '/admin.menu.php';
        $nv_Lang->changeLang();
    }

    return $submenu;
}

/**
 * @param string $module_name
 * @return array
 */
function nv_get_submenu_mod($module_name)
{
    // Các biến global này cần dùng khi include nên không xóa
    global $global_config, $db, $site_mods, $admin_info, $db_config, $admin_mods, $nv_Lang;

    $submenu = [];
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
