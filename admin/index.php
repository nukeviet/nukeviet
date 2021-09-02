<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

define('NV_ADMIN', true);

//Xac dinh thu muc goc cua site
define('NV_ROOTDIR', str_replace('\\', '/', realpath(pathinfo(__FILE__, PATHINFO_DIRNAME) . '/../')));

require NV_ROOTDIR . '/includes/mainfile.php';

// Admin dang nhap
if (!defined('NV_IS_ADMIN') or !isset($admin_info) or empty($admin_info)) {
    require NV_ROOTDIR . '/includes/core/admin_access.php';
    require NV_ROOTDIR . '/includes/core/admin_login.php';
    exit(0);
}

// Khong cho xac dinh tu do cac variables
$array_url_instruction = $select_options = [];

if (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_global.php')) {
    require NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_global.php';
} elseif (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_global.php')) {
    require NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_global.php';
} elseif (file_exists(NV_ROOTDIR . '/includes/language/en/admin_global.php')) {
    require NV_ROOTDIR . '/includes/language/en/admin_global.php';
}

include_once NV_ROOTDIR . '/includes/core/admin_functions.php';
$admin_mods = [];
$sql = 'SELECT * FROM ' . NV_AUTHORS_GLOBALTABLE . '_module WHERE act_' . $admin_info['level'] . ' = 1 ORDER BY weight ASC';
$list = $nv_Cache->db($sql, '', 'authors');
foreach ($list as $row) {
    $row['custom_title'] = isset($lang_global[$row['lang_key']]) ? $lang_global[$row['lang_key']] : $row['module'];
    $admin_mods[$row['module']] = $row;
}
if (!defined('NV_IS_GODADMIN') and empty($global_config['idsite'])) {
    unset($admin_mods['seotools']);
}

// Chặn BOT index và follow khu vực quản trị
$nv_BotManager->setNoIndex()->setNoFollow();

$site_mods = nv_site_mods();
if (!isset($admin_mods[$admin_info['main_module']]) and !isset($site_mods[$admin_info['main_module']])) {
    $admin_info['main_module'] = 'siteinfo';
}
$module_name = strtolower($nv_Request->get_title(NV_NAME_VARIABLE, 'post,get', $admin_info['main_module']));
if (preg_match($global_config['check_module'], $module_name)) {
    $include_functions = $include_file = $include_menu = $lang_file = $mod_theme_file = '';
    $module_data = $module_file = $module_name;

    $op = $nv_Request->get_title(NV_OP_VARIABLE, 'post,get', 'main');

    if (empty($op) or $op == 'functions') {
        $op = 'main';
    } elseif (!preg_match('/^[a-z0-9\-\_\/\+]+$/i', $op)) {
        nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
    }

    if (empty($site_mods) and $module_name != 'language') {
        $sql = 'SELECT setup FROM ' . $db_config['prefix'] . "_setup_language WHERE lang='" . NV_LANG_DATA . "'";
        $setup = $db->query($sql)->fetchColumn();
        if (empty($setup)) {
            nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_NAME_VARIABLE . '=language');
        }
    }
    $menu_top = [];
    if (isset($admin_mods['database']) and !(defined('NV_IS_GODADMIN') or (defined('NV_IS_SPADMIN') and $global_config['idsite'] > 0))) {
        unset($admin_mods['database']);
    }

    if (isset($site_mods[$module_name])) {
        $module_info = $site_mods[$module_name];
        $module_file = $module_info['module_file'];
        $module_data = $module_info['module_data'];
        $module_upload = $module_info['module_upload'];

        $include_functions = NV_ROOTDIR . '/modules/' . $module_file . '/admin.functions.php';
        $include_menu = NV_ROOTDIR . '/modules/' . $module_file . '/admin.menu.php';
        $include_file = NV_ROOTDIR . '/modules/' . $module_file . '/admin/' . $op . '.php';

        //Ket noi ngon ngu cua module
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php')) {
            require NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_INTERFACE . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_DATA . '.php')) {
            require NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_' . NV_LANG_DATA . '.php';
        } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php')) {
            require NV_ROOTDIR . '/modules/' . $module_file . '/language/admin_en.php';
        }
    } elseif (isset($admin_mods[$module_name])) {
        $module_info = $admin_mods[$module_name];
        if (md5($module_info['module'] . '#' . $module_info['act_1'] . '#' . $module_info['act_2'] . '#' . $module_info['act_3'] . '#' . $global_config['sitekey'])) {
            $module_upload = $module_file = $module_name;
            $include_functions = NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_file . '/functions.php';
            $include_menu = NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_file . '/admin.menu.php';
            $include_file = NV_ROOTDIR . '/' . NV_ADMINDIR . '/' . $module_file . '/' . $op . '.php';

            // Ket noi voi file ngon ngu cua module
            if (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_' . $module_file . '.php')) {
                require NV_ROOTDIR . '/includes/language/' . NV_LANG_INTERFACE . '/admin_' . $module_file . '.php';
            } elseif (file_exists(NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_' . $module_file . '.php')) {
                require NV_ROOTDIR . '/includes/language/' . NV_LANG_DATA . '/admin_' . $module_file . '.php';
            } elseif (file_exists(NV_ROOTDIR . '/includes/language/en/admin_' . $module_file . '.php')) {
                require NV_ROOTDIR . '/includes/language/en/admin_' . $module_file . '.php';
            }
        }
    }

    if (file_exists($include_functions) and file_exists($include_file)) {
        define('NV_IS_MODADMIN', true);

        $array_lang_admin = [];

        if ($global_config['lang_multi']) {
            $_language_array = nv_scandir(NV_ROOTDIR . '/includes/language', '/^[a-z]{2}$/');
            foreach ($_language_array as $lang_i) {
                if (file_exists(NV_ROOTDIR . '/includes/language/' . $lang_i . '/global.php')) {
                    $array_lang_admin[$lang_i] = $language_array[$lang_i]['name'];
                }
            }
        }

        // Ket noi voi giao dien chung cua admin
        $admin_info['admin_theme'] = (!empty($admin_info['admin_theme']) and file_exists(NV_ROOTDIR . '/themes/' . $global_config['admin_theme'] . '/theme.php')) ? $admin_info['admin_theme'] : $global_config['admin_theme'];
        require NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/theme.php';

        // Ket noi giao dien cua module
        $global_config['module_theme'] = '';
        if (is_dir(NV_ROOTDIR . '/themes/' . $admin_info['admin_theme'] . '/modules/' . $module_file)) {
            $global_config['module_theme'] = $admin_info['admin_theme'];
        } elseif (is_dir(NV_ROOTDIR . '/themes/admin_default/modules/' . $module_file)) {
            $global_config['module_theme'] = 'admin_default';
        }

        $allow_func = [];
        //Ket noi menu cua module
        if (file_exists($include_menu)) {
            require $include_menu;
        }

        require $include_functions;

        if (is_dir(NV_ROOTDIR . '/modules/' . $module_file . '/plugin/')) {
            // Kết nối với các Plugin
            $plugin_filename = scandir(NV_ROOTDIR . '/modules/' . $module_file . '/plugin/');
            foreach ($plugin_filename as $_filename) {
                if (preg_match('/^([a-zA-Z0-9\-\_]+)\_(admin)\.php$/', $_filename, $m)) {
                    $plugin_name = $m[1];
                    if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/plugin_' . $plugin_name . '_admin_' . NV_LANG_INTERFACE . '.php')) {
                        require NV_ROOTDIR . '/modules/' . $module_file . '/language/plugin_' . $plugin_name . '_admin_' . NV_LANG_INTERFACE . '.php';
                    } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/plugin_' . $plugin_name . '_admin_' . NV_LANG_DATA . '.php')) {
                        require NV_ROOTDIR . '/modules/' . $module_file . '/language/plugin_' . $plugin_name . '_admin_' . NV_LANG_DATA . '.php';
                    } elseif (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/language/plugin_' . $plugin_name . '_admin_en.php')) {
                        require NV_ROOTDIR . '/modules/' . $module_file . '/language/plugin_' . $plugin_name . '_admin_en.php';
                    }
                    require NV_ROOTDIR . '/modules/' . $module_file . '/plugin/' . $plugin_name . '_admin.php';
                }
            }
        }

        if (in_array($op, $allow_func, true)) {
            $admin_menu_mods = [];
            if (!empty($menu_top) and !empty($submenu)) {
                $admin_menu_mods[$module_name] = $menu_top['custom_title'];
            } elseif (isset($site_mods[$module_name])) {
                $admin_menu_mods[$module_name] = $site_mods[$module_name]['admin_title'];
            }
            foreach ($site_mods as $key => $value) {
                if ($value['admin_file']) {
                    $admin_menu_mods[$key] = $value['admin_title'];
                }
            }
            require $include_file;
            exit();
        }
        nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['admin_no_allow_func'], 404);
    } elseif (isset($site_mods[$module_name]) and $op == 'main') {
        $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET admin_file=0 WHERE title= :module_name');
        $sth->bindParam(':module_name', $module_name, PDO::PARAM_STR);
        $sth->execute();

        $nv_Cache->delMod('modules');
    }
}

nv_info_die($lang_global['error_404_title'], $lang_global['error_404_title'], $lang_global['error_404_content'], 404);
