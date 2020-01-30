<?php

/**
 * @Project NUKEVIET 4.x
 * @Author VINADES.,JSC <contact@vinades.vn>
 * @Copyright (C) 2014 VINADES.,JSC. All rights reserved
 * @License GNU/GPL version 2 or any later version
 * @Createdate 2-2-2010 12:55
 */

if (!defined('NV_IS_FILE_THEMES')) {
    die('Stop!!!');
}

$set_layout_site = false;
$select_options = [];
$theme_array = nv_scandir(NV_ROOTDIR . '/themes', [$global_config['check_theme'], $global_config['check_theme_mobile']]);

if ($global_config['idsite']) {
    $theme = $db->query('SELECT t1.theme FROM ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site_cat t1 INNER JOIN ' . $db_config['dbsystem'] . '.' . $db_config['prefix'] . '_site t2 ON t1.cid=t2.cid WHERE t2.idsite=' . $global_config['idsite'])->fetchColumn();
    if (!empty($theme)) {
        $array_site_cat_theme = explode(',', $theme);

        $result = $db->query('SELECT DISTINCT theme FROM ' . NV_PREFIXLANG . '_modthemes WHERE func_id=0');
        while (list($theme) = $result->fetch(3)) {
            $array_site_cat_theme[] = $theme;
        }
        $theme_array = array_intersect($theme_array, $array_site_cat_theme);
    }
}

foreach ($theme_array as $themes_i) {
    if (file_exists(NV_ROOTDIR . '/themes/' . $themes_i . '/config.ini')) {
        $select_options[NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=setuplayout&amp;selectthemes=' . $themes_i] = $themes_i;
    }
}

$selectthemes_old = $nv_Request->get_string('selectthemes', 'cookie', $global_config['site_theme']);
$selectthemes = $nv_Request->get_string('selectthemes', 'get', $selectthemes_old);

if (!in_array($selectthemes, $theme_array)) {
    $selectthemes = 'default';
}

if ($selectthemes_old != $selectthemes) {
    $nv_Request->set_Cookie('selectthemes', $selectthemes, NV_LIVE_COOKIE_TIME);
}

$layout_array = nv_scandir(NV_ROOTDIR . '/themes/' . $selectthemes . '/layout', $global_config['check_op_layout']);

if (!empty($layout_array)) {
    $layout_array = preg_replace($global_config['check_op_layout'], '\\1', $layout_array);
}
$array_layout_func_default = [];

if (file_exists(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini')) {
    $xml = simplexml_load_file(NV_ROOTDIR . '/themes/' . $selectthemes . '/config.ini');
    $layoutdefault = ( string )$xml->layoutdefault;
    $layout = $xml->xpath('setlayout/layout');

    for ($i = 0, $count = sizeof($layout); $i < $count; ++$i) {
        $layout_name = ( string )$layout[$i]->name;

        if (in_array($layout_name, $layout_array)) {
            $layout_funcs = $layout[$i]->xpath('funcs');

            for ($j = 0, $sizeof = sizeof($layout_funcs); $j < $sizeof; ++$j) {
                $mo_funcs = ( string )$layout_funcs[$j];
                $mo_funcs = explode(':', $mo_funcs);
                $m = $mo_funcs[0];
                $arr_f = explode(',', $mo_funcs[1]);

                foreach ($arr_f as $f) {
                    $array_layout_func_default[$m][$f] = $layout_name;
                }
            }
        }
    }

    $page_title = $nv_Lang->getModule('setup_layout') . ':' . $selectthemes;

    $tpl = new \NukeViet\Template\Smarty();
    $tpl->setTemplateDir(NV_ROOTDIR . '/themes/' . $global_config['module_theme'] . '/modules/' . $module_file);
    $tpl->assign('LANG', $nv_Lang);
    $tpl->assign('FORM_ACTION', NV_BASE_ADMINURL . 'index.php?' . NV_LANG_VARIABLE . '=' . NV_LANG_DATA . '&amp;' . NV_NAME_VARIABLE . '=' . $module_name . '&amp;' . NV_OP_VARIABLE . '=' . $op);

    if ($nv_Request->isset_request('save', 'post') and $nv_Request->isset_request('func', 'post')) {
        nv_insert_logs(NV_LANG_DATA, $module_name, $nv_Lang->getModule('setup_layout') . ' theme: "' . $selectthemes . '"', '', $admin_info['userid']);

        $func_arr_save = $nv_Request->get_array('func', 'post');

        foreach ($func_arr_save as $func_id => $layout_name) {
            if (in_array($layout_name, $layout_array)) {
                $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_modthemes SET layout=:layout WHERE func_id = :func_id AND theme= :theme');
                $sth->bindParam(':layout', $layout_name, PDO::PARAM_STR);
                $sth->bindParam(':func_id', $func_id, PDO::PARAM_INT);
                $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
                $sth->execute();
            }
        }

        $set_layout_site = true;

        $tpl->assign('SET_LAYOUT_SITE', true);
    } elseif ($nv_Request->isset_request('saveall', 'post') and $nv_Request->isset_request('layout', 'post')) {
        $layout = $nv_Request->get_string('layout', 'post');
        $module = $nv_Request->get_string('block_module', 'post');
        if (in_array($layout, $layout_array)) {
            if (empty($module)) {
                //Setup layout for all module
                $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_modthemes SET layout= :layout WHERE func_id IN (SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func=1) AND theme= :theme');
                $sth->bindParam(':layout', $layout, PDO::PARAM_STR);
                $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
                $sth->execute();
                $set_layout_site = true;
            } elseif (isset($site_mods[$module])) {
                //Setup layout for module
                $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_modthemes SET layout= :layout WHERE func_id IN (SELECT func_id FROM ' . NV_MODFUNCS_TABLE . ' WHERE in_module = :in_module AND show_func=1) AND theme= :theme');
                $sth->bindParam(':layout', $layout, PDO::PARAM_STR);
                $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
                $sth->bindParam(':in_module', $module, PDO::PARAM_STR);
                $sth->execute();
                $set_layout_site = true;
            }
            $tpl->assign('SET_LAYOUT_SITE', true);
        }
    }

    $array_layout_func_data = [];
    $sth = $db->prepare('SELECT func_id, layout FROM ' . NV_PREFIXLANG . '_modthemes WHERE theme= :theme');
    $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
    $sth->execute();
    while (list($func_id, $layout) = $sth->fetch(3)) {
        $array_layout_func_data[$func_id] = $layout;
    }

    if (!isset($array_layout_func_data[0])) {
        $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_modthemes
            (func_id, layout, theme) VALUES
            (0, :layout, :theme)');
        $sth->bindParam(':layout', $layoutdefault, PDO::PARAM_STR);
        $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
        $sth->execute();

        $set_layout_site = true;
    } elseif ($array_layout_func_data[0] != $layoutdefault) {
        $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_modthemes SET layout= :layout WHERE func_id=0 AND theme= :theme');
        $sth->bindParam(':layout', $layoutdefault, PDO::PARAM_STR);
        $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
        $sth->execute();

        $set_layout_site = true;
    }

    $array_layout_func = [];
    $fnresult = $db->query('SELECT func_id, func_name, func_custom_name, in_module FROM ' . NV_MODFUNCS_TABLE . ' WHERE show_func=1 ORDER BY subweight ASC');
    while (list($func_id, $func_name, $func_custom_name, $in_module) = $fnresult->fetch(3)) {
        if (isset($array_layout_func_data[$func_id]) and !empty($array_layout_func_data[$func_id])) {
            $layout_name = $array_layout_func_data[$func_id];

            if (!in_array($layout_name, $layout_array)) {
                $layout_name = $layoutdefault;

                $sth = $db->prepare('UPDATE ' . NV_PREFIXLANG . '_modthemes SET layout= :layout WHERE func_id= :func_id AND theme= :theme');
                $sth->bindParam(':layout', $layout_name, PDO::PARAM_STR);
                $sth->bindParam(':func_id', $func_id, PDO::PARAM_INT);
                $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
                $sth->execute();

                $set_layout_site = true;
            }
        } else {
            $layout_name = (isset($array_layout_func_default[$in_module][$func_name])) ? $array_layout_func_default[$in_module][$func_name] : $layoutdefault;
            $sth = $db->prepare('INSERT INTO ' . NV_PREFIXLANG . '_modthemes (func_id, layout, theme) VALUES (:func_id, :layout, :theme)');
            $sth->bindParam(':func_id', $func_id, PDO::PARAM_INT);
            $sth->bindParam(':layout', $layout_name, PDO::PARAM_STR);
            $sth->bindParam(':theme', $selectthemes, PDO::PARAM_STR);
            $sth->execute();

            $set_layout_site = true;
        }

        $array_layout_func[$in_module][$func_name] = [$func_id, $func_custom_name, $layout_name];
    }

    if ($set_layout_site) {
        $nv_Cache->delMod('themes');
        $nv_Cache->delMod('modules');
    }

    $tpl->assign('LAYOUT_ARRAY', $layout_array);
    $tpl->assign('ARRAY_LAYOUT_FUNC', $array_layout_func);

    $rows = $db->query('SELECT title, custom_title FROM ' . NV_MODULES_TABLE . ' ORDER BY weight ASC')->fetchAll();
    $number_func = sizeof($rows);

    $array_modules = [];
    foreach ($rows as $row) {
        if (isset($array_layout_func[$row['title']])) {
            $array_modules[] = $row;
        }
    }
    $tpl->assign('ARRAY_MODULES', $array_modules);

    $contents = $tpl->fetch('setuplayout.tpl');
}

include NV_ROOTDIR . '/includes/header.php';
echo nv_admin_theme($contents);
include NV_ROOTDIR . '/includes/footer.php';
