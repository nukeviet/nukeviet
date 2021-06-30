<?php

/**
 * NukeViet Content Management System
 * @version 4.x
 * @author VINADES.,JSC <contact@vinades.vn>
 * @copyright (C) 2009-2021 VINADES.,JSC. All rights reserved
 * @license GNU/GPL version 2 or any later version
 * @see https://github.com/nukeviet The NukeViet CMS GitHub project
 */

if (!defined('NV_ADMIN') or !defined('NV_MAINFILE') or !defined('NV_IS_MODADMIN')) {
    exit('Stop!!!');
}

$menu_top = [
    'title' => $module_name,
    'module_file' => '',
    'custom_title' => $lang_global['mod_modules']
];

define('NV_IS_FILE_MODULES', true);

// Document
$array_url_instruction['main'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:modules:modules';
$array_url_instruction['setup'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:modules:setup';
$array_url_instruction['vmodule'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:modules:vmodule';
$array_url_instruction['edit'] = 'https://wiki.nukeviet.vn/nukeviet4:admin:modules:edit';

/**
 * nv_parse_vers()
 *
 * @param mixed $ver
 * @return
 */
function nv_parse_vers($ver)
{
    return $ver[1] . '-' . nv_date('d/m/Y', $ver[2]);
}

/**
 * nv_fix_module_weight()
 *
 * @return
 */
function nv_fix_module_weight()
{
    global $db, $nv_Cache;

    $result = $db->query('SELECT title FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC');
    $weight = 0;
    while ($row = $result->fetch()) {
        ++$weight;
        $sth = $db->prepare('UPDATE ' . NV_MODULES_TABLE . ' SET weight=' . $weight . ' WHERE title= :title');
        $sth->bindParam(':title', $row['title'], PDO::PARAM_STR);
        $sth->execute();
    }

    $nv_Cache->delMod('modules');
}

/**
 * nv_fix_subweight()
 *
 * @param mixed $mod
 * @return
 */
function nv_fix_subweight($mod)
{
    global $db;

    $subweight = 0;
    $sth = $db->prepare('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :in_module AND show_func=1 ORDER BY subweight ASC');
    $sth->bindParam(':in_module', $mod, PDO::PARAM_STR);
    $sth->execute();
    while ($row = $sth->fetch()) {
        ++$subweight;
        $db->query('UPDATE ' . NV_MODFUNCS_TABLE . ' SET subweight=' . $subweight . ' WHERE func_id=' . $row['func_id']);
    }
}

/**
 * nv_setup_block_module()
 *
 * @param mixed $mod
 * @param int   $func_id
 * @return
 */
function nv_setup_block_module($mod, $func_id = 0)
{
    global $db, $nv_Cache;

    if (empty($func_id)) {
        // xoa du lieu tai bang blocks
        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE bid in (SELECT bid FROM ' . NV_BLOCKS_TABLE . '_groups WHERE module= :module)');
        $sth->bindParam(':module', $mod, PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_groups WHERE module= :module');
        $sth->bindParam(':module', $mod, PDO::PARAM_STR);
        $sth->execute();

        $sth = $db->prepare('DELETE FROM ' . NV_BLOCKS_TABLE . '_weight WHERE func_id in (SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module= :module)');
        $sth->bindParam(':module', $mod, PDO::PARAM_STR);
        $sth->execute();
    }

    $array_funcid = [];
    $sth = $db->prepare('SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func = 1 AND in_module= :module ORDER BY subweight ASC');
    $sth->bindParam(':module', $mod, PDO::PARAM_STR);
    $sth->execute();
    while (list($func_id_i) = $sth->fetch(3)) {
        if ($func_id == 0 or $func_id == $func_id_i) {
            $array_funcid[] = $func_id_i;
        }
    }

    $weight = 0;
    $old_theme = $old_position = '';

    $sql = 'SELECT bid, theme, position FROM ' . NV_BLOCKS_TABLE . '_groups WHERE all_func= 1 ORDER BY theme ASC, position ASC, weight ASC';
    $result = $db->query($sql);
    while ($row = $result->fetch()) {
        if ($old_theme == $row['theme'] and $old_position == $row['position']) {
            ++$weight;
        } else {
            $weight = 1;
            $old_theme = $row['theme'];
            $old_position = $row['position'];
        }

        foreach ($array_funcid as $func_id) {
            $db->query('INSERT INTO ' . NV_BLOCKS_TABLE . '_weight (bid, func_id, weight) VALUES (' . $row['bid'] . ', ' . $func_id . ', ' . $weight . ')');
        }
    }

    $nv_Cache->delMod('themes');
}

/**
 * nv_setup_data_module()
 *
 * @param mixed $lang
 * @param mixed $module_name
 * @param int   $sample
 * @return
 */
function nv_setup_data_module($lang, $module_name, $sample = 0)
{
    global $nv_Cache, $db, $db_config, $global_config, $install_lang;

    $return = 'NO_' . $module_name;

    $sth = $db->prepare('SELECT module_file, module_data, module_upload, theme FROM ' . $db_config['prefix'] . '_' . $lang . '_modules WHERE title= :title');
    $sth->bindParam(':title', $module_name, PDO::PARAM_STR);
    $sth->execute();

    list($module_file, $module_data, $module_upload, $module_theme) = $sth->fetch(3);

    if (!empty($module_file)) {
        $module_version = [];
        $version_file = NV_ROOTDIR . '/modules/' . $module_file . '/version.php';

        if (file_exists($version_file)) {
            include $version_file;
        }

        $arr_modfuncs = (isset($module_version['modfuncs']) and !empty($module_version['modfuncs'])) ? array_map('trim', explode(',', $module_version['modfuncs'])) : [];

        // Delete config value in prefix_config table
        $sth = $db->prepare('DELETE FROM ' . NV_CONFIG_GLOBALTABLE . " WHERE lang= '" . $lang . "' AND module= :module");
        $sth->bindParam(':module', $module_name, PDO::PARAM_STR);
        $sth->execute();

        $nv_Cache->delAll();

        // Re-Creat all module table
        if (file_exists(NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php')) {
            $sql_recreate_module = [];

            try {
                $db->exec('ALTER DATABASE ' . $db_config['dbname'] . ' DEFAULT CHARACTER SET ' . $db_config['charset'] . ' COLLATE ' . $db_config['collation']);
            } catch (PDOException $e) {
                trigger_error($e->getMessage());
            }

            include NV_ROOTDIR . '/modules/' . $module_file . '/action_' . $db->dbtype . '.php';

            if (!empty($sql_create_module)) {
                foreach ($sql_create_module as $sql) {
                    try {
                        $db->query($sql);
                    } catch (PDOException $e) {
                        trigger_error(print_r($e, true));

                        return $return;
                    }
                }
            }
        }

        // Setup layout if site module
        $arr_func_id = [];
        $arr_show_func = [];
        $new_funcs = nv_scandir(NV_ROOTDIR . '/modules/' . $module_file . '/funcs', $global_config['check_op_file']);

        if (!empty($new_funcs)) {
            // Get default layout
            $layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/layout', $global_config['check_op_layout']);
            if (!empty($layout_array)) {
                $layout_array = preg_replace($global_config['check_op_layout'], '\\1', $layout_array);
            }

            $selectthemes = 'default';
            if (!empty($module_theme) and file_exists(NV_ROOTDIR . '/themes/' . $module_theme . '/config.ini')) {
                $selectthemes = $module_theme;
            } elseif (file_exists(NV_ROOTDIR . '/themes/' . $global_config['site_theme'] . '/config.ini')) {
                $selectthemes = $global_config['site_theme'];
            }

            $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini');
            $layoutdefault = (string) $xml->layoutdefault;
            $layout = $xml->xpath('setlayout/layout');

            $array_layout_func_default = [];
            for ($i = 0, $count = sizeof($layout); $i < $count; ++$i) {
                $layout_name = (string) $layout[$i]->name;

                if (in_array($layout_name, $layout_array, true)) {
                    $layout_funcs = $layout[$i]->xpath('funcs');
                    for ($j = 0, $count2 = sizeof($layout_funcs); $j < $count2; ++$j) {
                        $mo_funcs = (string) $layout_funcs[$j];
                        $mo_funcs = explode(':', $mo_funcs);
                        $m = $mo_funcs[0];
                        $arr_f = explode(',', $mo_funcs[1]);
                        foreach ($arr_f as $f) {
                            $array_layout_func_default[$m][$f] = $layout_name;
                        }
                    }
                }
            }

            $_layoutdefault = (isset($module_version['layoutdefault'])) ? $module_version['layoutdefault'] : '';
            if (!empty($_layoutdefault)) {
                $_layout_mod = explode(';', $_layoutdefault);
                foreach ($_layout_mod as $_layout_fun) {
                    list($layout_name, $_func) = explode(':', trim($_layout_fun));
                    $arr_f = explode(',', trim($_func));
                    foreach ($arr_f as $f) {
                        if (!isset($array_layout_func_default[$module_name][$f])) {
                            $array_layout_func_default[$module_name][$f] = $layout_name;
                        }
                    }
                }
            }

            $arr_func_id_old = [];

            $sth = $db->prepare('SELECT func_id, func_name FROM ' . $db_config['prefix'] . '_' . $lang . '_modfuncs WHERE in_module= :in_module');
            $sth->bindParam(':in_module', $module_name, PDO::PARAM_STR);
            $sth->execute();
            while ($row = $sth->fetch()) {
                $arr_func_id_old[$row['func_name']] = $row['func_id'];
            }

            $new_funcs = preg_replace($global_config['check_op_file'], '\\1', $new_funcs);
            $new_funcs = array_flip($new_funcs);
            $array_keys = array_keys($new_funcs);

            $array_submenu = (isset($module_version['submenu'])) ? array_map('trim', explode(',', $module_version['submenu'])) : [];
            foreach ($array_keys as $func) {
                $show_func = 0;
                $weight = 0;
                $in_submenu = (in_array($func, $array_submenu, true)) ? 1 : 0;
                if (isset($arr_func_id_old[$func]) and isset($arr_func_id_old[$func]) > 0) {
                    $arr_func_id[$func] = $arr_func_id_old[$func];
                    $db->query('UPDATE ' . $db_config['prefix'] . '_' . $lang . '_modfuncs SET show_func= ' . $show_func . ', in_submenu=' . $in_submenu . ', subweight=0 WHERE func_id=' . $arr_func_id[$func]);
                } else {
                    $data = [];
                    $data['func_name'] = $func;
                    $data['alias'] = $func;
                    $data['func_custom_name'] = ucfirst($func);
                    $data['in_module'] = $module_name;

                    $arr_func_id[$func] = $db->insert_id('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_modfuncs
						(func_name, alias, func_custom_name, in_module, show_func, in_submenu, subweight, setting) VALUES
					 	(:func_name, :alias, :func_custom_name, :in_module, ' . $show_func . ', ' . $in_submenu . ', ' . $weight . ", '')", 'func_id', $data);
                    if ($arr_func_id[$func]) {
                        $layout = $layoutdefault;
                        if (isset($array_layout_func_default[$module_name][$func])) {
                            if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout/layout.' . $array_layout_func_default[$module_name][$func] . '.tpl')) {
                                $layout = $array_layout_func_default[$module_name][$func];
                            }
                        }
                        $db->query('INSERT INTO ' . $db_config['prefix'] . '_' . $lang . '_modthemes (func_id, layout, theme) VALUES (' . $arr_func_id[$func] . ', ' . $db->quote($layout) . ', ' . $db->quote($selectthemes) . ')');
                    }
                }
            }

            $subweight = 0;
            foreach ($arr_modfuncs as $func) {
                if (isset($arr_func_id[$func])) {
                    $func_id = $arr_func_id[$func];
                    $arr_show_func[] = $func_id;
                    $show_func = 1;
                    ++$subweight;
                    $db->query('UPDATE ' . $db_config['prefix'] . '_' . $lang . '_modfuncs SET subweight=' . $subweight . ', show_func=' . $show_func . ' WHERE func_id=' . $func_id);
                }
            }
        } else {
            // Xoa du lieu tai bang _modfuncs
            $sth = $db->prepare('DELETE FROM ' . $db_config['prefix'] . '_' . $lang . '_modfuncs WHERE in_module= :in_module');
            $sth->bindParam(':in_module', $module_name, PDO::PARAM_STR);
            $sth->execute();
        }

        // Creat upload dirs
        if (isset($module_version['uploads_dir']) and !empty($module_version['uploads_dir'])) {
            $sth_dir = $db->prepare('INSERT INTO ' . NV_UPLOAD_GLOBALTABLE . '_dir (dirname, time, thumb_type, thumb_width, thumb_height, thumb_quality) VALUES (:dirname, 0, 0, 0, 0, 0)');

            foreach ($module_version['uploads_dir'] as $path) {
                $cp = '';
                $arr_p = explode('/', $path);

                foreach ($arr_p as $p) {
                    if (trim($p) != '') {
                        if (!is_dir(NV_UPLOADS_REAL_DIR . '/' . $cp . $p)) {
                            $mk = nv_mkdir(NV_UPLOADS_REAL_DIR . '/' . $cp, $p);
                            if ($mk[0]) {
                                try {
                                    $sth_dir->bindValue(':dirname', NV_UPLOADS_DIR . '/' . $cp . $p, PDO::PARAM_STR);
                                    $sth_dir->execute();
                                } catch (PDOException $e) {
                                }
                            }
                        }

                        $cp .= $p . '/';
                    }
                }
            }
        }

        // Creat assets dirs
        if (isset($module_version['files_dir']) and !empty($module_version['files_dir'])) {
            foreach ($module_version['files_dir'] as $path) {
                $cp = '';
                $arr_p = explode('/', $path);

                foreach ($arr_p as $p) {
                    if (trim($p) != '') {
                        if (!is_dir(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $cp . $p)) {
                            nv_mkdir(NV_ROOTDIR . '/' . NV_FILES_DIR . '/' . $cp, $p);
                        }
                        if (!is_dir(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $cp . $p)) {
                            nv_mkdir(NV_ROOTDIR . '/' . NV_MOBILE_FILES_DIR . '/' . $cp, $p);
                        }
                        $cp .= $p . '/';
                    }
                }
            }
        }

        // Install sample data
        if ($sample) {
            $sample_lang_file = NV_ROOTDIR . '/modules/' . $module_file . '/language/data_' . $lang . '.php';
            $sample_default_file = NV_ROOTDIR . '/modules/' . $module_file . '/language/data_en.php';

            if (file_exists($sample_lang_file)) {
                include $sample_lang_file;
            } elseif (file_exists($sample_default_file)) {
                include $sample_default_file;
            }
        }

        $return = 'OK_' . $module_name;

        $nv_Cache->delAll();
    }

    return $return;
}

/**
 * main_theme()
 *
 * @param mixed $contents
 * @return
 */
function main_theme($contents)
{
    global $global_config, $module_file, $lang_global, $lang_module;

    $xtpl = new XTemplate('main.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CONTENT', $contents);

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * list_theme()
 *
 * @param mixed $contents
 * @param mixed $act_modules
 * @param mixed $deact_modules
 * @param mixed $bad_modules
 * @param mixed $weight_list
 * @return
 */
function list_theme($contents, $act_modules, $deact_modules, $bad_modules, $weight_list)
{
    global $global_config, $module_file, $lang_global;

    $xtpl = new XTemplate('list.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('CAPTION', $contents['caption']);
    $xtpl->assign('GLANG', $lang_global);

    if (!empty($act_modules)) {
        foreach ($contents['thead'] as $thead) {
            $xtpl->assign('THEAD', $thead);
            $xtpl->parse('main.act_modules.thead');
        }

        $a = 0;
        foreach ($act_modules as $mod => $values) {
            $xtpl->assign('ROW', [
                'mod' => $mod,
                'values' => $values,
                'act_disabled' => (isset($values['act'][2]) and $values['act'][2] == 1) ? ' disabled="disabled"' : ''
            ]);

            foreach ($weight_list as $new_weight) {
                $xtpl->assign('WEIGHT', [
                    'key' => $new_weight,
                    'selected' => $new_weight == $values['weight'][0] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.act_modules.loop.weight');
            }

            if (!empty($values['del'])) {
                $xtpl->parse('main.act_modules.loop.delete');
            }

            $xtpl->parse('main.act_modules.loop');
        }

        $xtpl->parse('main.act_modules');
    }

    if (!empty($deact_modules)) {
        foreach ($contents['thead'] as $thead) {
            $xtpl->assign('THEAD', $thead);
            $xtpl->parse('main.deact_modules.thead');
        }

        $a = 0;
        foreach ($deact_modules as $mod => $values) {
            $xtpl->assign('ROW', [
                'mod' => $mod,
                'values' => $values,
                'act_disabled' => (isset($values['act'][2]) and $values['act'][2] == 1) ? ' disabled="disabled"' : ''
            ]);

            foreach ($weight_list as $new_weight) {
                $xtpl->assign('WEIGHT', [
                    'key' => $new_weight,
                    'selected' => $new_weight == $values['weight'][0] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.deact_modules.loop.weight');
            }

            if (!empty($values['del'])) {
                $xtpl->parse('main.deact_modules.loop.delete');
            }

            $xtpl->parse('main.deact_modules.loop');
        }

        $xtpl->parse('main.deact_modules');
    }

    if (!empty($bad_modules)) {
        foreach ($contents['thead'] as $thead) {
            $xtpl->assign('THEAD', $thead);
            $xtpl->parse('main.bad_modules.thead');
        }

        $a = 0;
        foreach ($bad_modules as $mod => $values) {
            $xtpl->assign('ROW', [
                'mod' => $mod,
                'values' => $values,
                'act_disabled' => (isset($values['act'][2]) and $values['act'][2] == 1) ? ' disabled="disabled"' : ''
            ]);

            foreach ($weight_list as $new_weight) {
                $xtpl->assign('WEIGHT', [
                    'key' => $new_weight,
                    'selected' => $new_weight == $values['weight'][0] ? ' selected="selected"' : ''
                ]);
                $xtpl->parse('main.bad_modules.loop.weight');
            }

            if (!empty($values['del'])) {
                $xtpl->parse('main.bad_modules.loop.delete');
            }

            $xtpl->parse('main.bad_modules.loop');
        }

        $xtpl->parse('main.bad_modules');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * show_funcs_theme()
 *
 * @param mixed $contents
 * @return
 */
function show_funcs_theme($contents)
{
    global $global_config, $module_file;

    $xtpl = new XTemplate('show_funcs_theme.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('NV_BASE_SITEURL', NV_BASE_SITEURL);
    $xtpl->assign('CONTENT', $contents);

    if (!empty($contents['ajax'][0])) {
        $xtpl->parse('main.ajax0');
        $xtpl->parse('main.loading0');
    }

    if (!empty($contents['ajax'][1])) {
        $xtpl->parse('main.ajax1');
        $xtpl->parse('main.loading1');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}

/**
 * setup_modules()
 *
 * @param mixed $array_head
 * @param mixed $array_modules
 * @param mixed $array_virtual_head
 * @param mixed $array_virtual_modules
 * @return
 */
function setup_modules($array_head, $array_modules, $array_virtual_head, $array_virtual_modules)
{
    global $global_config, $module_file, $lang_global, $lang_module;

    $xtpl = new XTemplate('setup_modules.tpl', NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $xtpl->assign('GLANG', $lang_global);
    $xtpl->assign('LANG', $lang_module);
    $xtpl->assign('CAPTION', $array_head['caption']);

    foreach ($array_head['head'] as $thead) {
        $xtpl->assign('HEAD', $thead);
        $xtpl->parse('main.head');
    }

    $a = 0;
    foreach ($array_modules as $mod => $values) {
        $xtpl->assign('ROW', [
            'stt' => ++$a,
            'values' => $values
        ]);

        $xtpl->parse('main.loop');
    }

    if (!empty($array_virtual_modules)) {
        $xtpl->assign('VCAPTION', $array_virtual_head['caption']);

        foreach ($array_virtual_head['head'] as $thead) {
            $xtpl->assign('VHEAD', $thead);
            $xtpl->parse('main.vmodule.vhead');
        }

        $a = 0;
        foreach ($array_virtual_modules as $mod => $values) {
            $xtpl->assign('VROW', [
                'stt' => ++$a,
                'values' => $values
            ]);
            if (!empty($values['url_setup'])) {
                $xtpl->parse('main.vmodule.loop.setup');
            }
            $xtpl->parse('main.vmodule.loop');
        }

        $xtpl->parse('main.vmodule');
    }

    $xtpl->parse('main');

    return $xtpl->text('main');
}
