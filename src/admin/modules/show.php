<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_IS_FILE_MODULES')) {
    exit('Stop!!!');
}

/**
 * nv_show_funcs()
 *
 * @throws PDOException
 */
function nv_show_funcs()
{
    global $nv_Cache, $db, $lang_global, $lang_module, $global_config, $site_mods, $nv_Request, $module_file;

    $mod = $nv_Request->get_title('mod', 'get', '');

    if (empty($mod) or !preg_match($global_config['check_module'], $mod)) {
        exit(0);
    }

    $sth = $db->prepare('SELECT module_file, module_upload, custom_title, admin_file FROM ' . NV_MODULES_TABLE . ' WHERE title= :mod');
    $sth->bindParam(':mod', $mod, PDO::PARAM_STR);
    $sth->execute();
    $row = $sth->fetch();
    if (empty($row)) {
        exit(0);
    }

    $mod_file = $row['module_file'];
    $admin_file = (file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/admin.functions.php') and file_exists(NV_ROOTDIR . '/modules/' . $mod_file . '/admin/main.php')) ? 1 : 0;

    $is_delCache = false;

    if ($admin_file != (int) ($row['admin_file'])) {
        $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET admin_file=' . $admin_file . ' WHERE title= :title');
        $sth->bindParam(':title', $mod, PDO::PARAM_STR);
        $sth->execute();

        $is_delCache = true;
    }

    $local_funcs = nv_scandir(NV_ROOTDIR . '/modules/' . $mod_file . '/funcs', $global_config['check_op_file']);

    if (!empty($local_funcs)) {
        $local_funcs = preg_replace($global_config['check_op_file'], '\\1', $local_funcs);
        $local_funcs = array_flip($local_funcs);
    }

    $module_version = [];
    $version_file = NV_ROOTDIR . '/modules/' . $mod_file . '/version.php';

    if (file_exists($version_file)) {
        $module_name = $mod;
        $module_upload = $row['module_upload'];
        require_once $version_file;
    }

    if (empty($module_version)) {
        $timestamp = NV_CURRENTTIME - date('Z', NV_CURRENTTIME);
        $module_version = [
            'name' => $mod,
            'modfuncs' => 'main',
            'is_sysmod' => 0,
            'virtual' => 0,
            'version' => '4.1.00',
            'date' => date('D, j M Y H:i:s', $timestamp) . ' GMT',
            'author' => '',
            'note' => ''
        ];
    }

    $module_version['submenu'] = isset($module_version['submenu']) ? trim($module_version['submenu']) : '';
    $modfuncs = array_map('trim', explode(',', $module_version['modfuncs']));
    $arr_in_submenu = array_map('trim', explode(',', $module_version['submenu']));

    $data_funcs = [];
    $weight_list = [];

    $sth = $db->prepare('SELECT * FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :in_module ORDER BY subweight ASC');
    $sth->bindParam(':in_module', $mod, PDO::PARAM_STR);
    $sth->execute();

    while ($row = $sth->fetch()) {
        $func = $row['func_name'];
        $show_func = in_array($func, $modfuncs, true) ? 1 : 0;

        if ($row['show_func'] != $show_func) {
            $row['show_func'] = $show_func;
            $db->query('UPDATE ' . NV_MODFUNCS_TABLE . ' SET show_func=' . $show_func . ' WHERE func_id=' . $row['func_id']);
            $is_delCache = true;
        }

        $data_funcs[$func]['func_id'] = $row['func_id'];
        $data_funcs[$func]['layout'] = empty($row['layout']) ? '' : $row['layout'];
        $data_funcs[$func]['show_func'] = $row['show_func'];
        $data_funcs[$func]['alias'] = $row['alias'];
        $data_funcs[$func]['func_custom_name'] = $row['func_custom_name'];
        $data_funcs[$func]['func_site_title'] = empty($row['func_site_title']) ? $row['func_custom_name'] : $row['func_site_title'];
        $data_funcs[$func]['in_submenu'] = $row['in_submenu'];
        $data_funcs[$func]['subweight'] = $row['subweight'];

        if ($show_func) {
            $weight_list[] = $row['subweight'];
        }
    }

    $act_funcs = array_intersect_key($data_funcs, $local_funcs);
    $old_funcs = array_diff_key($data_funcs, $local_funcs);
    $new_funcs = array_diff_key($local_funcs, $data_funcs);

    $is_refresh = false;
    if (!empty($old_funcs)) {
        foreach ($old_funcs as $func => $values) {
            $db->query('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE func_id = ' . $values['func_id']);
            $db->query('DELETE FROM ' . NV_MODFUNCS_TABLE . ' WHERE func_id = ' . $values['func_id']);
            $db->query('DELETE FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id = ' . $values['func_id']);
            $is_delCache = true;
        }

        $db->query('OPTIMIZE TABLE ' . NV_BLOCKS_TABLE . '_weight');
        $db->query('OPTIMIZE TABLE ' . NV_MODFUNCS_TABLE);
        $db->query('OPTIMIZE TABLE ' . NV_PREFIXLANG . '_modthemes');
        $is_refresh = true;
    }

    if (!empty($new_funcs)) {
        $mod_theme = 'default';

        if (!empty($site_mods[$mod]['theme']) and file_exists(NV_ROOTDIR . '/themes/' . $site_mods[$mod]['theme'] . '/config.ini')) {
            $mod_theme = $site_mods[$mod]['theme'];
        } elseif (!empty($global_config['site_theme']) and file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini')) {
            $mod_theme = $global_config['site_theme'];
        }

        $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $mod_theme . '/config.ini');
        $layoutdefault = (string) $xml->layoutdefault;

        $array_keys = array_keys($new_funcs);

        $sth2 = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_modthemes (func_id, layout, theme) VALUES (:func_id, :layout, :theme)');

        foreach ($array_keys as $func) {
            $show_func = in_array($func, $modfuncs, true) ? 1 : 0;
            try {
                $data = [];
                $data['func_name'] = $func;
                $data['alias'] = $func;
                $data['func_custom_name'] = ucfirst($func);
                $data['in_module'] = $mod;

                $_sql = 'INSERT INTO ' . NV_MODFUNCS_TABLE . ' (func_name, alias, func_custom_name, in_module, show_func, in_submenu, subweight, setting) VALUES ( :func_name, :alias, :func_custom_name, :in_module, ' . $show_func . ", 0, 0, '')";
                $func_id = $db->insert_id($_sql, 'func_id', $data);
                if ($show_func) {
                    $sth2->bindParam(':func_id', $func_id, PDO::PARAM_INT);
                    $sth2->bindParam(':layout', $layoutdefault, PDO::PARAM_STR);
                    $sth2->bindParam(':theme', $mod_theme, PDO::PARAM_STR);
                    $sth2->execute();
                    nv_setup_block_module($mod, $func_id);
                }
            } catch (PDOException $e) {
            }
        }

        $is_refresh = true;
        $is_delCache = true;
    }

    if ($is_refresh) {
        nv_fix_subweight($mod);

        $act_funcs = [];
        $weight_list = [];

        $sth = $db->prepare('SELECT * FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :in_module AND show_func=1 ORDER BY subweight ASC');
        $sth->bindParam(':in_module', $mod, PDO::PARAM_STR);
        $sth->execute();
        while ($row = $sth->fetch()) {
            $func = $row['func_name'];

            $act_funcs[$func]['func_id'] = $row['func_id'];
            $act_funcs[$func]['layout'] = empty($row['layout']) ? '' : $row['layout'];
            $act_funcs[$func]['show_func'] = $row['show_func'];
            $act_funcs[$func]['alias'] = $row['alias'];
            $act_funcs[$func]['func_custom_name'] = $row['func_custom_name'];
            $act_funcs[$func]['func_site_title'] = empty($row['func_site_title']) ? $row['func_custom_name'] : $row['func_site_title'];
            $act_funcs[$func]['in_submenu'] = $row['in_submenu'];
            $act_funcs[$func]['subweight'] = $row['subweight'];

            $weight_list[] = $row['subweight'];
        }
    }

    if ($is_delCache) {
        $nv_Cache->delMod('modules');
        $nv_Cache->delMod('themes');
    }

    $fun_change_alias = (isset($module_version['change_alias'])) ? array_map('trim', explode(',', $module_version['change_alias'])) : [];
    if (empty($fun_change_alias)) {
        $module_version['virtual'] = 0;
    }

    $xtpl = new XTemplate('aj_show_funcs_theme.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('GLANG', $lang_global);
    foreach ($act_funcs as $funcs => $values) {
        if ($values['show_func']) {
            $values['func_name'] = $funcs;
            $values['in_submenu_checked'] = ($values['in_submenu']) ? 'checked="checked" ' : '';
            $values['disabled'] = (in_array($funcs, $arr_in_submenu, true)) ? '' : ' disabled';
            $xtpl->assign('ROW', $values);

            foreach ($weight_list as $new_weight) {
                $xtpl->assign('WEIGHT', [
                    'key' => $new_weight,
                    'selected' => $new_weight == $values['subweight'] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.loop.weight');
            }

            if ($module_version['virtual']) {
                if ($funcs != 'main' and in_array($funcs, $fun_change_alias, true)) {
                    $xtpl->parse('main.loop.change_alias');
                } else {
                    $xtpl->parse('main.loop.show_alias');
                }
            }
            $xtpl->parse('main.loop');
        }
    }
    if ($module_version['virtual']) {
        $xtpl->parse('main.change_alias_head');
    }
    $xtpl->parse('main');

    include NV_ROOTDIR . '/includes/header.php';
    echo $xtpl->text('main');
    include NV_ROOTDIR . '/includes/footer.php';
}

if ($nv_Request->isset_request('aj', 'get')) {
    if ($nv_Request->get_title('aj', 'get') == 'show_funcs') {
        nv_show_funcs();
        exit();
    }
}

$mod = $nv_Request->get_title('mod', 'get', '');

if (empty($mod) or !preg_match($global_config['check_module'], $mod)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$sth = $db->prepare('SELECT custom_title FROM ' . NV_MODULES_TABLE . ' WHERE title= :title');
$sth->bindParam(':title', $mod, PDO::PARAM_STR);
$sth->execute();
$row = $sth->fetch();

if (empty($row)) {
    nv_redirect_location(NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&' . NV_NAME_VARIABLE . '=' . $module_name);
}

$page_title = sprintf($lang_module['funcs_list'], $row['custom_title']);

$contents = [];

$contents['div_id'][0] = 'show_funcs';
$contents['div_id'][1] = 'action';

$contents['ajax'][0] = "nv_show_funcs('show_funcs');";
$contents['ajax'][1] = $nv_Request->isset_request('func_id,pos', 'get') ? 'nv_bl_list(' . $nv_Request->get_int('func_id', 'get') . ",'" . $nv_Request->get_title('pos', 'get') . "','action');" : '';

$contents = show_funcs_theme($contents);

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
